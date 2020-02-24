<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class AuthTest
 * @package Tests\Feature
 */
class AuthTest extends TestCase
{
    /**
     *
     */
    public function test_requires_an_email()
    {
        $this->json('POST', 'api/auth/login', ['password' => 'qwerty'])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email'
                ],
            ]);
    }

    /**
     *
     */
    public function test_requires_an_password()
    {
        $this->json('POST', 'api/auth/login')
            ->assertJsonStructure([
                'errors' => [
                    'password'
                ],
            ]);
    }

    /**
     *
     */
    public function test_returns_a_validation_if_credentials_dont_match()
    {
        $this->json('POST', 'api/auth/login',
            [
                'email' => 'wrong@phpuzem.com',
                'password' => '12345678',
            ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['These credentials do not match our records.']
                ]
            ]);
    }

    /**
     *
     */
    public function test_returns_a_validation_if_credentials_do_match()
    {
        Artisan::call('passport:install');
        $user = factory(User::class)->create();

        $this->json('POST', 'api/auth/login',
            [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     *
     */
    public function test_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/auth/user')
            ->assertStatus(401);
    }

    /**
     *
     */
    public function test_returns_user_detail()
    {
        Artisan::call('passport:install');
        Passport::actingAs(
            $user = factory(User::class)->create()
        );

        $this->json('GET', 'api/auth/user')
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['name', 'email', 'id', 'created_at', 'updated_at']
            ]);
    }
}
