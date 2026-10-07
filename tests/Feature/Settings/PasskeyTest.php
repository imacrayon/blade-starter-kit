<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasskeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_passkeys_are_listed_on_the_security_page(): void
    {
        $user = User::factory()->withPasskey('Work Laptop')->create();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('settings.security.edit'))
            ->assertOk()
            ->assertSee('Work Laptop');
    }

    public function test_passkey_endpoints_are_advertised(): void
    {
        $this->getJson('.well-known/passkey-endpoints')
            ->assertOk()
            ->assertExactJson([
                'enroll' => route('settings.security.edit'),
                'manage' => route('settings.security.edit'),
            ]);
    }

    public function test_registration_options_require_password_confirmation(): void
    {
        $this->be(User::factory()->create())
            ->get(route('passkey.registration-options'))
            ->assertRedirectToRoute('password.confirm');
    }

    public function test_passkey_can_be_deleted(): void
    {
        $user = User::factory()->withPasskey()->create();
        $passkey = $user->passkeys->first();

        $this->be($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete(route('passkey.destroy', $passkey))
            ->assertRedirect();

        $this->assertModelMissing($passkey);
    }
}
