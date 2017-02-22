<?php

namespace App\Console\Commands\App;

use Illuminate\Console\Command;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the app.';

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
        # install dependencies
        shell_exec('composer install');

        # apply migrations
        $this->call('migrate');

        # give permissions
        $this->info('Running chmod');
        shell_exec('chmod 777 resources/lang -R');
        shell_exec('chmod 777 storage -R');
        shell_exec('chmod 777 bootstrap/cache -R');

        # clear the cache
        $this->info('Clearing the cache');
        $this->call('cache:clear');
        $this->call('debugbar:clear');
        $this->call('view:clear');

        # cache config and routes
        $this->info('Caching config and routes');
        $this->call('config:cache');
        $this->call('route:cache');
    }
}
