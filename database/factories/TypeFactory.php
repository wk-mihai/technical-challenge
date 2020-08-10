<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Type;
use Faker\Generator as Faker;

$factory->define(Type::class, function (Faker $faker) {
    $name = ucfirst($faker->unique()->word);

    return [
        'slug' => \Illuminate\Support\Str::slug($name),
        'name' => $name,
    ];
});
