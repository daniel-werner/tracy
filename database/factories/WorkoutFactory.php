<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Workout::class, function (Faker $faker) {
    return [
        'type' => $faker->numberBetween(1,2),
        'title' => $faker->text(50),
        'status' => \App\Workout::STATUS_ACTIVE
    ];
});
