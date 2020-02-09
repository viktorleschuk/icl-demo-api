<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OrderItem;
use Faker\Generator as Faker;

$factory->define(OrderItem::class, function (Faker $faker) {
    return [
        'order_id' => factory(\App\Order::class),
        'name' => implode(" ", $faker->words(rand(1, 3))),
        'quantity' => rand(1, 100),
        'price' => $faker->randomFloat(1, 0, 1000),
    ];
});
