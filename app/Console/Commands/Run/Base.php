<?php

namespace App\Console\Commands\Run;

use Illuminate\Console\Command;

use Symfony\Component\Process\Process;
use SSH;

use App\Models\TaskExecution;

class Base extends Command
{
    /**
     * The last execution object-
     *
     * @var App\Models\TaskExecution
     */
    protected $execution;

    /**
     * The string output of tasks that run via SSH
     *
     * @var string
     */
    protected $outputString;

    protected function process($task)
    {
        if ($task->viaSSH)
        {
            return $this->processViaSSH($task);
        }

        return $this->processViaProcess($task);
    }

    protected function processViaSSH($task)
    {
        $this->startExecution($task);

        $stream = SSH::connect($task->ssh)->run($task->command, function($buffer)
        {
            $this->addOutput($buffer);
        });

        $this->executionCompleted($this->outputString);

        dd($stream);

        return true;
    }

    protected function processViaProcess($task)
    {
        $process = new Process($task->command);

        $process->start();

        $this->startExecution($task, $process->getPid());

        $process->wait(function ($type, $buffer) {
            $this->addOutput($buffer);
        });

        if (!$process->isSuccessful()) {
            $this->executionFailed($process->getErrorOutput());

            return false;
        }

        $this->executionCompleted($process->getOutput());

        return true;
    }

    protected function addOutput($output)
    {
        $this->outputString .= $output;

        $this->execution->update([
            'result' => $this->outputString,
        ]);
    }

    protected function startExecution($task, $pid = 'n/a')
    {
        $task->updateNextDue();
        
        $this->execution = TaskExecution::create([
            'task_id'   => $task->id,
            'status'    => 'running'
        ]);

        $this->info(sprintf('Running %s (%s) PID: %s', $task->name, $task->command, $pid));
    }

    protected function executionFailed($output)
    {
        $this->execution->update([
            'status'    => 'failed',
            'result'    => $output,
        ]);
    }

    protected function executionCompleted($output)
    {
        $this->execution->update([
            'status'    => 'completed',
            'result'    => $output,
        ]);

        $this->info($output);
    }
}
