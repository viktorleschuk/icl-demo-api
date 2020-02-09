<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\User::class, 5)
            ->create()
            ->each(function ($user) {
                factory(App\Order::class, rand(1, 5))
                    ->create(['user_id' => $user->getKey()])
                    ->each(function ($order) {
                        factory(App\OrderItem::class, rand(5, 20))
                            ->create(['order_id' => $order->getKey()]);
                    });
            });
    }
}
