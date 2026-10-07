<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);
    }

    public function test_security_page_is_displayed()
    {
        $this->be(User::factory()->create())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'))
            ->assertOk()
            ->assertSee('Use at least 8 characters.')
            ->assertSee('passwordrules="minlength: 8;"', false);
    }

    public function test_security_page_requires_password_confirmation()
    {
        $response = $this->be(User::factory()->create())
            ->get(route('settings.security.edit'));

        $response->assertRedirectToRoute('password.confirm');
    }

    public function test_security_page_requires_a_verified_email()
    {
        $this->be(User::factory()->unverified()->create())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'))
            ->assertRedirectToRoute('verification.notice');
    }

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->put(route('settings.security.update'), [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'password-updated')
            ->assertRedirectBack();

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_invalid_two_factor_confirmation_code_keeps_the_setup_form(): void
    {
        $this->be(User::factory()->create())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post(route('two-factor.enable'));

        $this->from(route('settings.security.edit'))
            ->followingRedirects()
            ->post(route('two-factor.confirm'), ['code' => '000000'])
            ->assertSee('Enter the 6-digit code from your authenticator app')
            ->assertSee('The provided two factor authentication code was invalid.');
    }

    public function test_abandoned_two_factor_setup_is_replaced_when_enabling_again(): void
    {
        $user = User::factory()->create();
        $user->forceFill([
            'two_factor_secret' => $secret = encrypt('test-secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        ])->save();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'))
            ->assertOk()
            ->assertSee('Enable 2FA')
            ->assertSee('<input type="hidden" name="force" value="1">', false);

        $this->assertSame($secret, $user->fresh()->two_factor_secret);
    }

    public function test_two_factor_setup_survives_the_redirect_after_enabling(): void
    {
        $user = User::factory()->create();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'));

        $this->from(route('settings.security.edit'))
            ->followingRedirects()
            ->post(route('two-factor.enable'))
            ->assertSee('Enter the 6-digit code from your authenticator app');

        $this->assertNotNull($user->fresh()->two_factor_secret);
    }

    public function test_setup_key_is_shown_when_two_factor_does_not_require_confirmation(): void
    {
        Features::twoFactorAuthentication(['confirm' => false, 'confirmPassword' => true]);

        $this->be(User::factory()->create())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->from(route('settings.security.edit'))
            ->followingRedirects()
            ->post(route('two-factor.enable'))
            ->assertSee('Setup key')
            ->assertDontSee('Enter the 6-digit code from your authenticator app');
    }

    public function test_security_page_omits_disabled_features(): void
    {
        config(['fortify.features' => []]);

        $this->be(User::factory()->create())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'))
            ->assertOk()
            ->assertDontSee('Passkeys')
            ->assertDontSee('Two-Factor Authentication');
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->put(route('settings.security.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirectBack();
    }
}
