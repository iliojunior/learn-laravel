<?php

use Faker\Generator as Faker;

$factory->define(App\Todo::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->email,
        'description' => $faker->text
    ];
});
