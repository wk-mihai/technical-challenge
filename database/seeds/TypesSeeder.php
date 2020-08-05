<?php

use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'slug' => 'health',
                'name' => 'Health'
            ],
            [
                'slug' => 'safety',
                'name' => 'Safety'
            ]
        ];

        array_map(function ($type) {
            return Type::firstOrCreate($type);
        }, $types);
    }
}
