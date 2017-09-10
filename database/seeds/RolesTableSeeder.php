<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'          => 'is-admin',
                'description'   => 'Has admin privileges.',
            ],
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
            [
                'name'          => 'custom-notifications',
                'description'   => 'Custom notifications feature.',
            ],
            [
                'name'          => 'advanced-notifications',
                'description'   => 'Advanced notifications feature.',
            ],
        ];

        foreach ($roles as $role)
        {
            Role::create($role);
        }
    }
}
