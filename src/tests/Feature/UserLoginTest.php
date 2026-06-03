<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_email_is_required()
    {
        $response = $this->from('/login')
        ->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_is_required()
    {
        $response = $this->from('/login')
        ->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'role' => 'user',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')
        ->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
