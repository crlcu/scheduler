<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # create administrator
        User::create([
            'group_id'  => 1,
            'name'      => 'Administrator',
            'email'     => 'admin@scheduler.com',
            'password'  => bcrypt('password'),
        ]);

        # create user
        User::create([
            'group_id'  => 2,
            'name'      => 'User',
            'email'     => 'user@scheduler.com',
            'password'  => bcrypt('password'),
        ]);
    }
}
