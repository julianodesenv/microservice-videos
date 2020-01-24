<?php

/** @var Factory $factory */

use App\Model\Category;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        //'description' => rand(1, 10) % 2 == 0 ? $faker->sentences() : null
        'description' => $faker->text()
    ];
});
