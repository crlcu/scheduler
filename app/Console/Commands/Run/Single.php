<?php

namespace App\Console\Commands\Run;

use App\Console\Commands\Run\Base as Command;

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
