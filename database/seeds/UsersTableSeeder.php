<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'group_id'	=> 1,
        	'name'		=> 'Administrator',
        	'email'		=> 'admin@scheduler.com',
        	'password'	=> bcrypt('password'),
        ]);
    }
}
