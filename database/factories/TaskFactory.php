<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'category_id' => 1,
        'creator_id' => 1,
        'name' => $faker->unique()->text(8),
        'description' => $faker->text(20),
        'score' => $faker->randomNumber(2),
        'remain_times' => 20,
        'confirmed' => 0,
        'expired_at' => $faker->dateTimeInInterval('-1 month', '+1 month'),
        'created_at' => $faker->dateTimeInInterval('-1 day', '+1 day'),
        'updated_at' => $faker->dateTimeInInterval('now', 'now')
    ];
});
