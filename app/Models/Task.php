<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;
use Sofa\Eloquence\Eloquence;

use Auth;
use Carbon\Carbon;
use Cron\CronExpression;
use Event;
use Symfony\Component\Process\Process;
use SSH;

use App\Events\TaskRunning;
use App\Events\TaskFailed;
use App\Events\TaskCompleted;

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
        'name', 'command', 'cron_expression', 'next_due', 'is_one_time_only', 'is_via_ssh', 'ssh_config_json', 'is_enabled'
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['average', 'details', 'has_notifications', 'ssh', 'schedule'];

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
        $executions = $this->executions->where('status', 'completed');
        
        if (!$executions->count())
        {
            return '0';
        }

        $total = $executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $executions->count());
    }

    public function getDetailsAttribute($value)
    {
        return $this->is_via_ssh ? json_decode($this->ssh_config_json, true) : ['run' => 'localy'];
    }

    public function getHasNotificationsAttribute($value)
    {
        return $this->notifications->count() > 0;
    }

    public function getSshAttribute($value)
    {
        return json_decode($this->ssh_config_json ? : '[]', true);
    }

    public function getScheduleAttribute($value)
    {
        if ($this->is_one_time_only)
        {
            return 'once';
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
        if ($this->is_via_ssh)
        {
            return $this->runViaSSH();
        }

        return $this->runViaProcess();
    }

    protected function runViaSSH()
    {
        $this->running();

        $stream = SSH::connect($this->ssh)->run($this->command, function($buffer)
        {
            $this->execution->result .= $buffer;
            $this->execution->save();
        });

        $this->done();

        return true;
    }

    protected function runViaProcess()
    {
        $this->running();

        $process = new Process($this->command);
        $process->setTimeout(3600);
        
        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->execution->result .= $buffer;
            $this->execution->save();
        });

        if (!$process->isSuccessful()) {
            $this->execution->result = $process->getErrorOutput();

            $this->done('failed');

            return false;
        }

        $this->done();

        return true;
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

    protected function done($status = 'completed')
    {
        $this->execution->update([
            'status' => $status,
        ]);

        if ($status == 'completed')
        {
            Event::fire(new TaskCompleted($this));
        }
        else
        {
            Event::fire(new TaskFailed($this));
        }
    }
}
