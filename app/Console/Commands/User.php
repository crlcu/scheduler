<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User as UserModel;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "user {action : [create|changepassword]}
                                {--u|--user_id= : User id}
                                {--g|group_id=2 : Group id}
                                {--name= : Name}
                                {--e|email= : Email}
                                {--p|password= : Password}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage users.';

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
        $action = $this->argument('action');
        $ok = $this->$action();

        if ($ok)
        {
            $this->info('DONE');
        }
        else
        {
            $this->error('FAILED');
        }

        return $ok;
    }

    protected function create()
    {
        $this->info('Creating user ...');

        $user = UserModel::create([
            'group_id'  => $this->option('group_id'),
            'name'      => $this->option('name'),
            'email'     => $this->option('email'),
            'password'  => bcrypt($this->option('password')),
        ]);

        return true;
    }

    protected function changepassword()
    {
        $this->info('Changing password ...');

        $user = UserModel::findOrFail($this->option('user_id'));

        $user->update([
            'password' => bcrypt($this->option('password'))
        ]);

        return true;
    }
}
