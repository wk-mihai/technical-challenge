<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    $name = ucfirst($faker->unique()->word);

    return [
        'slug' => \Illuminate\Support\Str::slug($name),
        'name' => $name,
    ];
});
