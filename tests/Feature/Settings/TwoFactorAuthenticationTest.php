<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);
});

test('two factor settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->be($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('settings.two-factor.edit'))
        ->assertOk()
        ->assertSee('Two-Factor Authentication')
        ->assertSee('Disabled');
});

test('two factor settings page requires password confirmation when enabled', function () {
    $user = User::factory()->create();

    $this->be($user)
        ->get(route('settings.two-factor.edit'))
        ->assertRedirectToRoute('password.confirm');
});

test('two factor settings page returns forbidden response when two factor is disabled', function () {
    config(['fortify.features' => []]);

    $user = User::factory()->create();

    $this->be($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('settings.two-factor.edit'))
        ->assertForbidden();
});
