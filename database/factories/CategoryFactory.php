<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Group;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    return [
        // 'group_id' => factory(Group::class),
        'name' => Str::random(5),
    ];
});
