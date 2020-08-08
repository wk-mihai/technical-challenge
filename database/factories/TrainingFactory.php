<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Training;
use App\Models\Type;
use Faker\Generator as Faker;

$factory->define(Training::class, function (Faker $faker) {
    return [
        'type_id' => Type::all()->random()->id,
        'name'    => $faker->title,
        'content' => $faker->realText(2000),
    ];
});
