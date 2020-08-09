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
                'slug' => 'admin',
                'name' => 'Admin'
            ],
            [
                'slug' => 'pilot',
                'name' => 'Pilot'
            ],
            [
                'slug' => 'user',
                'name' => 'User'
            ]
        ];

        array_map(function ($role) {
            return Role::firstOrCreate($role);
        }, $roles);
    }
}
