<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\User\Entities\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
    ];
});
