<?php

use App\Models\Role;
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
        $roles = Role::get();
        $users = [];

        foreach ($roles as $role) {
            $users[] = [
                'role_id'  => $role->id,
                'name'     => $role->name,
                'email'    => "{$role->slug}@challenge.local",
                'password' => bcrypt($role->slug)
            ];
        }

        array_map(function ($user) {
            return User::firstOrCreate($user);
        }, $users);
    }
}
