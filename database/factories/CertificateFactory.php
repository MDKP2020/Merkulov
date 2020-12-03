<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Certificate;
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

$factory->define(Certificate::class, function (Faker $faker) {
    return [
        'serial' => $faker->randomNumber(4),
        'number' => $faker->randomNumber(8),
        'is_archived' => $faker->boolean
    ];
});
