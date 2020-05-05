<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Group::class, function (Faker $faker) {
    return [
        // 'creator_id' => factory(User::class),
        'group_token' => Str::random(5),
        'name' => Str::random(5),
        'description' => Str::random(10)
    ];
});
