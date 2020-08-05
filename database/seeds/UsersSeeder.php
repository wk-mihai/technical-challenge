<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@challenge.local',
                'password' => bcrypt('admin')
            ],
            [
                'name'     => 'Pilot',
                'email'    => 'pilot@challenge.local',
                'password' => bcrypt('pilot')
            ],
            [
                'name'     => 'User',
                'email'    => 'user@challenge.local',
                'password' => bcrypt('user')
            ],
        ];

        array_map(function ($user) {
            return User::firstOrCreate($user);
        }, $users);
    }
}
