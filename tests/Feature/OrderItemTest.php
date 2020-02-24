<?php

namespace Tests\Feature;

use App\Order;
use App\OrderItem;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class AuthTest
 * @package Tests\Feature
 */
class OrderItemTest extends TestCase
{
    /**
     * @var mixed
     */
    protected $user;
    protected $order;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install', ['--force' => true]);

        Passport::actingAs(
            $this->user = factory(User::class)->create()
        );

        $this->order = factory(Order::class)->create(['user_id' => $this->user->getKey()]);
    }

    public function test_index_success()
    {
        factory(OrderItem::class, 10)->create(['order_id' => $this->order->getKey()]);
        $this->json('GET', 'api/orders/' . $this->order->getKey() . '/items')
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_import_invalid_file_format()
    {
        $this->json('POST', 'api/orders/' . $this->order->getKey() . '/items/import',
            ['file' => UploadedFile::fake()->create('document.pdf')]
        )
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'file' => ['The file must be a file of type: csv, txt.']
                ]
            ]);
    }
}
