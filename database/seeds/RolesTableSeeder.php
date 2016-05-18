<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name'          => 'manage-roles',
                'description'   => 'Can manage roles.',
            ],
            [
                'name'          => 'manage-groups',
                'description'   => 'Can manage groups.',
            ],
            [
                'name'          => 'manage-users',
                'description'   => 'Can manage users.',
            ],
            [
                'name'          => 'feature-notifications',
                'description'   => 'Notifications feature.',
            ],
        ]);
    }
}
