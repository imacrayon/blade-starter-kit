<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('user avatar accessor returns local proxy url', function () {
    $user = User::factory()->create();

    expect($user->avatar)->toBe(route('avatars.show', $user));
    expect($user->avatar)->not->toContain($user->email);
});

test('avatar proxy returns an image for a valid user', function () {
    Http::fake([
        'unavatar.io/*' => Http::response('fake-image-bytes', 200, [
            'Content-Type' => 'image/png',
        ]),
    ]);

    $user = User::factory()->create();

    $response = $this->get(route('avatars.show', $user));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');
    expect($response->content())->toBe('fake-image-bytes');
});

test('avatar proxy caches the fetched image', function () {
    Http::fake([
        'unavatar.io/*' => Http::response('fake-image-bytes', 200, [
            'Content-Type' => 'image/png',
        ]),
    ]);

    $user = User::factory()->create();

    $this->get(route('avatars.show', $user))->assertOk();
    $this->get(route('avatars.show', $user))->assertOk();

    Http::assertSentCount(1);
});

test('avatar proxy returns 404 for non-existent user', function () {
    $this->get(route('avatars.show', 999))->assertNotFound();
});
