<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sign_in_and_sign_out(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/login')->assertOk();
        $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->post('/login', ['email' => 'admin@admin.com', 'password' => '123123'])
            ->assertRedirect('/admin');
        $this->get('/admin')->assertOk()->assertSee('Painel administrativo');
        $this->get('/login')->assertRedirect('/admin');

        $this->post('/logout')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }
}