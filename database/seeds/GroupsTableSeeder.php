<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert(['name' => 'Administrator']);
        DB::table('group_role')->insert([
        	['group_id' => 1, 'role_id' => 1],
        	['group_id' => 1, 'role_id' => 2],
        	['group_id' => 1, 'role_id' => 3],
        	['group_id' => 1, 'role_id' => 4],
        ]);
    }
}
