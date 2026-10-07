<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk()
            ->assertSee('Use at least 8 characters.')
            ->assertSee('passwordrules="minlength: 8;"', false);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'code' => '',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', 'test@example.com')->first();
        $response->assertRedirect(route('app'));
    }
}
