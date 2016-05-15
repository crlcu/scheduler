<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use Carbon\Carbon;
use Cron\CronExpression;
use Mail;
use Symfony\Component\Process\Process;
use SSH;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'command', 'cron_expression', 'next_due', 'is_one_time_only', 'is_via_ssh', 'ssh_config_json', 'is_enabled'];
    protected $appends = ['average', 'details', 'ssh'];

    protected $execution;

    /**
     * Accessors & Mutators
     */
    public function getAverageAttribute($value)
    {
        if (!$this->executions->count())
        {
            return '0';
        }

        $total = $this->executions->sum(function($execution) {
            return $execution->updated_at->diffInSeconds($execution->created_at);
        });

        return sprintf('%.2f', $total / $this->executions->count());
    }

    public function getDetailsAttribute($value)
    {
        return $this->is_via_ssh ? json_decode($this->ssh_config_json, true) : ['run' => 'localy'];
    }

    public function getSshAttribute($value)
    {
        return json_decode($this->ssh_config_json ? : '[]', true);
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
        return $query->where('user_id', '=', Auth::id());
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
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function last_run()
    {
        return $this->hasOne('App\Models\TaskExecution')
            ->orderBy('id', 'desc');
    }
    
    public function executions()
    {
        return $this->hasMany('App\Models\TaskExecution');
    }


    /**
     * Methods
     */
    public function run()
    {
        if ($this->is_via_ssh)
        {
            return $this->runis_via_ssh();
        }

        return $this->runViaProcess();
    }

    protected function runis_via_ssh()
    {
        $this->running();

        $stream = SSH::connect($task->ssh)->run($task->command, function($buffer)
        {
            $this->execution->result .= $buffer;
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
    }

    protected function done($status = 'completed')
    {
        $this->execution->update([
            'status' => $status,
        ]);

        $task = $this;
        $user = $this->user;

        Mail::send('emails.tasks.execution', ['task' => $task], function ($mail) use($task, $user) {
            $mail->to($user->email, $user->name)
                ->subject(sprintf('Result for "%s" task - %s', $task->name, $task->execution->status));
        });
    }
}
