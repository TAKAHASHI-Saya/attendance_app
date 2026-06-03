<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_login()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
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
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/admin/login')
        ->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
