<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two-factor authentication is not enabled.');
        }

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);
    }

    public function test_two_factor_settings_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.two-factor.edit'))
            ->assertOk()
            ->assertSee('Two-Factor Authentication')
            ->assertSee('Disabled');
    }

    public function test_two_factor_settings_page_requires_password_confirmation_when_enabled(): void
    {
        $user = User::factory()->create();

        $this->be($user)
            ->get(route('settings.two-factor.edit'))
            ->assertRedirectToRoute('password.confirm');
    }

    public function test_two_factor_settings_page_returns_forbidden_response_when_two_factor_is_disabled(): void
    {
        config(['fortify.features' => []]);

        $user = User::factory()->create();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.two-factor.edit'))
            ->assertForbidden();
    }
}
