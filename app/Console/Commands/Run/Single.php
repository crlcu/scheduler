<?php

namespace App\Console\Commands\Run;

use Illuminate\Console\Command;
use Mail;
use Log;
use App\Models\Task;

class Single extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:task {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $task = $this->argument('task');
        
        if (!is_a($task, 'App\Models\Task'))
        {
            $task = Task::findOrFail($this->argument('task'));
        }

        $this->info(sprintf('Running %s (%s)', $task->name, $task->command));

        if ($task->run())
        {
            $this->info('OK');
        }
        else 
        {
            $this->error('FAILED');
        }
    }
}
