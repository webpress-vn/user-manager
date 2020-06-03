<?php

use Faker\Generator as Faker;
use NF\Roles\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    $name = $faker->words(rand(1, 2), true);
    return [
        'name' => $name,
        'slug' => Str::slug($faker->numerify($name . ' ###'), '.'),
    ];
});
