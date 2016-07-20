<?php

use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\Role;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [ 'name' => 'Administrator' ],
            [ 'name' => 'Users' ],
        ];

        foreach ($groups as $group)
        {
            Group::create($group);
        }

        $group = Group::find(1);

        $roles = Role::all()
            ->lists('id')
            ->toArray();

        $group->roles()->attach($roles);
    }
}
