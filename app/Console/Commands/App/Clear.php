<?php

namespace App\Console\Commands\App;

use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache files.';

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
        # clear the cache
        $this->info('Clearing the cache');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('debugbar:clear');
        $this->call('route:clear');
        $this->call('view:clear');
    }
}
