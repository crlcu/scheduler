<?php

namespace App\Console\Commands\Run;

use App\Console\Commands\Run\Base as Command;

use Artisan;
use App\Models\Task;

class Multiple extends Command
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
        $tasks = Task::enabled()->isDue()->get();

        foreach ($tasks as $task)
        {
            Artisan::queue('run:task', ['task' => $task->id]);
        }
    }
}
