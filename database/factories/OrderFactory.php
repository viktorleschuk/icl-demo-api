<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'client_name' => $faker->name,
        'client_phone' => $faker->phoneNumber,
        'client_address' => $faker->address
    ];
});
