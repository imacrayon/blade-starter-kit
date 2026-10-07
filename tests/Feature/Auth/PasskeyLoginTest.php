<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasskeyLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_request_passkey_login_options(): void
    {
        $this->getJson(route('passkey.login-options'))
            ->assertOk()
            ->assertJsonStructure(['options' => ['challenge']]);
    }

    public function test_passkey_login_rejects_an_invalid_credential(): void
    {
        $this->post(route('passkey.login'), [
            'credential' => [
                'id' => 'abc',
                'rawId' => 'abc',
                'type' => 'public-key',
                'response' => ['clientDataJSON' => 'invalid'],
            ],
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_passkey_login_is_rate_limited(): void
    {
        $this->withCookie(config('session.cookie'), Str::random(40));

        foreach (range(1, 10) as $attempt) {
            $this->post(route('passkey.login'), ['credential' => ['id' => "credential-{$attempt}"]]);
        }

        $this->post(route('passkey.login'), ['credential' => ['id' => 'credential-11']])
            ->assertTooManyRequests();
    }

    public function test_visitors_sharing_an_ip_have_separate_passkey_limits(): void
    {
        foreach (range(1, 11) as $visitor) {
            $this
                ->withCookie(config('session.cookie'), Str::random(40))
                ->getJson(route('passkey.login-options'))
                ->assertOk();
        }
    }
}
