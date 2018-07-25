<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// $factory->define(App\User::class, function (Faker\Generator $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->safeEmail,
//         'password' => bcrypt(str_random(10)),
//         'remember_token' => str_random(10),
//     ];
// });

$factory->define(App\Models\Shop\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'title' => $faker->sentence(3, true),
        'description' => $faker->paragraph,
        'code' => $faker->unique()->ean13,
        'price' => $faker->numberBetween(100, 5000),
        'discount' => $faker->numberBetween(0, 100),
    ];
});