<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_login_and_token_revocation(): void
    {
        $data = [
            'name' => 'Pessoa Teste',
            'email' => 'Pessoa@Example.com',
            'password' => 'test-password',
            'password_confirmation' => 'test-password',
            'is_admin' => true,
        ];

        $registered = $this->postJson('/api/auth/register', $data)
            ->assertCreated()
            ->assertJsonPath('user.email', 'pessoa@example.com')
            ->assertJsonMissingPath('user.password')
            ->assertJsonMissingPath('user.is_admin');

        $user = User::firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->postJson('/api/auth/register', $data)->assertUnprocessable()->assertJsonValidationErrors('email');

        $this->postJson('/api/auth/login', ['email' => $data['email'], 'password' => 'wrong'])
            ->assertUnauthorized();
        $login = $this->postJson('/api/auth/login', $data)->assertOk();
        $token = $login->json('token');
        $this->assertNotSame($registered->json('token'), $token);

        $this->getJson('/api/auth/me')->assertUnauthorized();
        Auth::forgetGuards();
        $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"])
            ->assertOk()->assertJsonPath('user.id', $user->id);
        Auth::forgetGuards();
        $this->postJson('/api/auth/logout', [], ['Authorization' => "Bearer {$token}"])->assertNoContent();
        Auth::forgetGuards();
        $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"])->assertUnauthorized();
    }

    public function test_admin_cannot_use_mobile_login(): void
    {
        $this->seed();

        $this->postJson('/api/auth/login', ['email' => 'admin@admin.com', 'password' => '123123'])
            ->assertUnauthorized();
    }
}