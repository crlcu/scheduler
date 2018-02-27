<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;
use Sofa\Eloquence\Eloquence;

use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Cron\CronExpression;
use Event;
use Exception;
use Symfony\Component\Process\Process;
use SSH;

use App\Events\TaskRunning;

use App\Util\CronSchedule;

use App\Models\Observers\TaskObserver;

class Task extends Model
{
    use SoftDeletes, RevisionableTrait, Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'command', 'cron_expression', 'next_due', 'is_one_time_only', 'is_via_ssh', 'ssh_config_json', 'is_concurrent', 'is_enabled',
        'chart_y',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['average', 'average_for_humans', 'cron_for_humans', 'details', 'has_notifications', 'status_class', 'ssh', 'schedule'];

    protected $execution;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'      => 10,
        'next_due'  => 10,
    ];

    /**
     * Initialize the observer
     * @return [TaskObserver]
     */
    public static function boot()
    {
        parent::boot();

        parent::observe(new TaskObserver);
    }
    
    /**
     * Accessors & Mutators
     */
    public function getAverageAttribute($value)
    {
        $executions = $this->executions()->completed()->get();
        
        if (!$executions->count())
        {
            return 0;
        }

        $total = $executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $executions->count());
    }

    public function getAverageForHumansAttribute($value)
    {
        $average = $this->average;

        $hours = (int)($average / 3600);
        $minutes = (int)(($average - $hours * 3600) / 60);
        $seconds = (int)($average - ($hours * 3600 + $minutes * 60));

        return CarbonInterval::hour($hours)->minutes($minutes)->seconds($seconds)->forHumans();
    }

    public function getCronForHumansAttribute($value)
    {
        $expression = $this->next_due;

        if (!$this->is_one_time_only)
        {
            $schedule = CronSchedule::fromCronString($this->cron_expression);
            $expression = $schedule->asNaturalLanguage();
        }

        return $expression;
    }

    public function getDetailsAttribute($value)
    {
        return $this->is_via_ssh ? json_decode($this->ssh_config_json, true) : ['run' => 'localy'];
    }

    public function getHasNotificationsAttribute($value)
    {
        return $this->notifications->count() > 0;
    }

    public function getPingUrlAttribute()
    {
        return sprintf('%s?token=%s', action('TasksController@ping', $this->id), md5($this->id . $this->created_at));
    }

    public function getStatusClassAttribute($value)
    {
        return $this->is_enabled ? $this->last_run && $this->last_run->status_class : 'grey lighten-4';
    }

    public function getSshAttribute($value)
    {
        return json_decode($this->ssh_config_json ? : '[]', true);
    }

    public function getScheduleAttribute($value)
    {
        if ($this->is_one_time_only)
        {
            return $this->next_due;
        }

        return $this->cron_expression;
    }

    public function getTypeAttribute($value)
    {
        return $this->is_via_ssh ? 'ssh' : 'process';
    }


    /**
     * Scopes
     */
    public function scopeForCurrentUser($query)
    {
        $user = Auth::user();

        if ($user->hasRole('is-admin'))
        {
            return $query;
        }

        return $query->where('user_id', '=', $user->id);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', '=', 1);
    }

    public function scopeIsDue($query)
    {
        return $query->where('next_due', '<', Carbon::now());
    }


    /**
     * Relations
     */
    public function executions()
    {
        return $this->hasMany('App\Models\TaskExecution');
    }

    public function last_run()
    {
        return $this->hasOne('App\Models\TaskExecution')
            ->orderBy('id', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\TaskNotification');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    /**
     * Methods
     */
    public function run()
    {
        if (!$this->is_concurrent && $this->last_run['is_running'])
        {
            return false;
        }

        // Mark task execution as running
        $this->running();

        // Start task execution 
        $ok = $this->is_via_ssh ? $this->runViaSSH() : $this->runViaProcess();

        // Mark task execution as done
        $this->execution->done($ok ? 'completed' : 'failed');

        return $ok;
    }

    protected function runViaSSH()
    {
        try {
            $stream = SSH::connect($this->ssh)->run($this->command, function($buffer, $connection)
            {
                $this->execution->result .= $buffer;
                $this->execution->save();
            });
        } catch(Exception $e) {
            $this->execution->result .= $e->getMessage();
            return false;
        }

        return true;
    }

    protected function runViaProcess()
    {
        $process = new Process($this->command);
        $process->setTimeout(3600);
        
        $process->start();

        $this->storePid($process->getPid());

        $process->wait(function ($type, $buffer) {
            $this->execution->result .= $buffer;
            $this->execution->save();
        });

        # Remove trailing newline
        $this->execution->update([ 'result' => rtrim($this->execution->result)]);

        if (!$process->isSuccessful()) {
            // $this->execution->result .= $process->getErrorOutput();

            return false;
        }

        return true;
    }

    protected function storePid($pid)
    {
        $this->execution->pid = $pid;
        $this->execution->save();
    }

    protected function running()
    {
        if (!$this->is_one_time_only)
        {
            // Update next due date
            $cron = CronExpression::factory($this->cron_expression);
            $this->update(['next_due' => $cron->getNextRunDate()->format('Y-m-d H:i:s')]);
        } elseif ($this->is_one_time_only && $this->next_due <= Carbon::now()) {
            $this->update(['is_enabled' => 0]);
        }

        $this->execution = TaskExecution::create([
            'task_id'   => $this->id,
            'status'    => 'running'
        ]);
        
        Event::fire(new TaskRunning($this));
    }

}
