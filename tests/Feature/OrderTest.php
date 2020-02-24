<?php

namespace Tests\Feature;

use App\Order;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class AuthTest
 * @package Tests\Feature
 */
class OrderTest extends TestCase
{
    /**
     * @var mixed
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install', ['--force' => true]);

        Passport::actingAs(
            $this->user = factory(User::class)->create()
        );

    }

    public function test_index_success()
    {
        factory(Order::class, 5)->create(['user_id' => $this->user->getKey()]);

        $this->json('GET', 'api/orders')
            ->assertStatus(200)
            ->assertJsonStructure(['meta', 'links', 'data'])
            ->assertJsonCount(5, 'data');
    }

    public function test_filter_and_pagination()
    {
        foreach (range('a', 'z') as $item) {
            factory(Order::class)->create(['user_id' => $this->user->getKey(), 'client_name' => str_repeat($item, 5)]);
        }

        //Filtering
        $this->json('GET', 'api/orders', ['filter' => 'aaaaa'])
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        //Paginating
        $this->json('GET', 'api/orders', ['page' => '2'])
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson(['meta' => [
                'current_page' => 2,
                'from' => 11,
                'last_page' => 3,
                'per_page' => 10,
                'to' => 20,
                'total' => 26
            ]]);
    }

    public function test_create_order_validation_failure()
    {
        $this->json('POST', 'api/orders', [
            'client_name' => 'aa'
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'client_name' => ['The client name must be at least 3 characters.'],
                    'client_phone' => ['The client phone field is required.'],
                    'client_address' => ['The client address field is required.']
                ]
            ]);
    }

    public function test_create_order_success()
    {
        $this->json('POST', 'api/orders', [
            'client_name' => 'John Doe',
            'client_phone' => '12345678',
            'client_address' => 'USA'
        ])
            ->assertStatus(201)
            ->assertJson([
                'client_name' => 'John Doe',
                'client_phone' => '12345678',
                'client_address' => 'USA',
                'user_id' => $this->user->getKey(),
                'total_sum' => 0
            ]);
    }

    public function test_update_order_success()
    {
        $order = factory(Order::class)->create(['user_id' => $this->user->getKey()]);

        $this->json('PUT', 'api/orders/' . $order->getKey(), [
            'client_name' => 'John Doe',
            'client_phone' => '12345678',
            'client_address' => 'USA'
        ])
            ->assertStatus(200)
            ->assertJson([
                'client_name' => 'John Doe',
                'client_phone' => '12345678',
                'client_address' => 'USA',
                'user_id' => $this->user->getKey(),
                'total_sum' => 0
            ]);
    }
}
