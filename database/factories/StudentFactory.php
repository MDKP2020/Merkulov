<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
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

$factory->define(Student::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);

    return [
        'name' => $faker->firstName($gender),
        'surname' => $faker->lastName($gender),
        'patronymic' => $faker->middleName($gender),
        'transcript' => $faker->randomNumber(8),
        'status' => $faker->randomKey(\App\Student::STATUSES),
        'academic_degree' =>\App\Student::ACADEMIC_DEGREES[rand(0,3)],
    ];
});
