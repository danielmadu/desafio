<?php

namespace Tests\Feature;

use App\Listeners\CreateWallet;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'document' => '123456789',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_created_wallet()
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password',
            'document' => '987654321',
            'password_confirmation' => 'password',
        ]);

        /** @var User $user */
        $user = $this->app->make('auth')->guard(null)->user();

        /** @var Wallet $wallet */
        $wallet = Wallet::query()->where('user_id', $user->id)->first();

        $this->assertEquals(0.0, $wallet->total_amount);
    }

    public function test_create_wallet_listener()
    {
        Event::fake();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'document' => '123456789',
            'password_confirmation' => 'password',
        ]);

        Event::assertListening(
            Registered::class,
            CreateWallet::class
        );
    }
}
