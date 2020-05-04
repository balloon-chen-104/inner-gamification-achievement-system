<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Task;
use App\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class),
        'creator_id' => factory(User::class),
        'name' => $faker->unique()->text(8),
        'description' => $faker->text(20),
        'score' => $faker->randomNumber(2),
        'remain_times' => 20,
        'confirmed' => 0,
        'expired_at' => $faker->dateTimeInInterval('-1 month', '+1 month'),
        'created_at' => $faker->dateTimeInInterval('-1 day', 'now'),
        'updated_at' => $faker->dateTimeInInterval('now', 'now')
    ];
});
