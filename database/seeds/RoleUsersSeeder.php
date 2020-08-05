<?php

use App\Models\Role;
use App\Models\RoleUsers;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::get();

        foreach ($roles as $role) {
            $user = User::firstWhere('name', $role->name);

            if (is_null($user)) {
                continue;
            }

            RoleUsers::firstOrCreate([
                'user_id' => $user->id,
                'role_id' => $role->id
            ]);
        }
    }
}
