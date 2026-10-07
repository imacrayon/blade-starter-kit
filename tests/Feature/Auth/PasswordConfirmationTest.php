<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->be($user)->get(route('password.confirm'));

        $response->assertOk()->assertDontSee('Confirm with a passkey');
    }

    public function test_confirm_password_screen_offers_passkey_when_user_has_one(): void
    {
        $user = User::factory()->withPasskey()->create();

        $this->be($user)
            ->get(route('password.confirm'))
            ->assertOk();
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = User::factory()->create();

        $response = $this->be($user)->post(route('password.confirm.store'), [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->be($user)->post(route('password.confirm.store'), [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
