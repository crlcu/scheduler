<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;
use Cron\CronExpression;
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
        $process->start();

        $process->wait(function ($type, $buffer) {
            $this->execution->result .= $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->failed($process->getErrorOutput());

            return false;
        }

        $this->done();

        return true;
    }

    protected function running()
    {
        // Update next due date
        $cron = CronExpression::factory($this->cron_expression);
        $this->update(['next_due' => $cron->getNextRunDate()->format('Y-m-d H:i:s')]);

        $this->execution = TaskExecution::create([
            'task_id'   => $this->id,
            'status'    => 'running'
        ]);
    }

    protected function done()
    {
        $this->execution->update([
            'status' => 'completed',
        ]);
    }

    protected function failed()
    {
        $this->execution->update([
            'status' => 'failed',
        ]);
    }
}
