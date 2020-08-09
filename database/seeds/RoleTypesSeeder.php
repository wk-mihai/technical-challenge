<?php

use App\Models\Role;
use App\Models\RoleType;
use App\Models\Type;
use Illuminate\Database\Seeder;

class RoleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::get();
        $types = Type::get();

        foreach ($roles as $role) {
            if ($role->slug === 'admin') {
                continue;
            }

            foreach ($types as $key => $type) {
                if ($role->slug === 'user' && $key > 0) {
                    break;
                }

                RoleType::firstOrCreate([
                    'role_id' => $role->id,
                    'type_id' => $type->id,
                ]);
            }
        }
    }
}
