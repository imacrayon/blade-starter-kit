<?php

use App\Models\User;

test('profile page is displayed', function () {
    $this->be(User::factory()->create());

    $this->get(route('settings.profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->put(route('settings.profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->put(route('settings.profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->delete(route('settings.profile.edit'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->delete(route('settings.profile.edit'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirectBack();

    expect($user->fresh())->not->toBeNull();
});

test('account deletion is rate limited', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        $this->be($user)->delete(route('settings.profile.destroy'), [
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->be($user)->delete(route('settings.profile.destroy'), [
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(429);
});
