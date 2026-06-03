<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);
    }
}
