<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Symfony\Component\Process\Process;
use SSH;

use App\Models\Task;
use App\Models\TaskExecution;

class Runner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tasks';

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tasks = Task::all();

        foreach ($tasks as $task)
        {
            if ($this->process($task))
            {
                $this->info('OK');
            }
            else 
            {
                $this->error('FAILED');
            }
        }
    }

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

        SSH::connect($task->ssh)->run($task->command, function($line)
        {
            $this->addOutput($line);
        });

        $this->executionCompleted($this->outputString);

        return true;
    }

    protected function processViaProcess($task)
    {
        $process = new Process($task->command);

        $process->start();

        $this->startExecution($task, $process->getPid());

        while ($process->isRunning()) {
            // waiting for process to finish
        }

        // executes after the command finishes
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
    }

    protected function startExecution($task, $pid = 'n/a')
    {
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
    }
}
