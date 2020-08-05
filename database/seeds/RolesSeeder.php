<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
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
                'slug'               => 'admin',
                'name'               => 'Admin',
                'can_view_trainings' => true,
            ],
            [
                'slug'               => 'pilot',
                'name'               => 'Pilot',
                'can_view_trainings' => true,
            ],
            [
                'slug'               => 'user',
                'name'               => 'User',
                'can_view_trainings' => false,
            ]
        ];

        array_map(function ($role) {
            return Role::firstOrCreate($role);
        }, $roles);
    }
}
