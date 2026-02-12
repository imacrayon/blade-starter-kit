<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

test('admin can impersonate another user', function () {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->create();
    $response = $this
        ->be($admin)
        ->post(route('admin.impersonation.store'), [
            'user_id' => $target->id,
        ]);
    $response->assertRedirectToRoute('app');
    $this->assertAuthenticatedAs($target);
    expect(session('impersonator_id'))->toEqual($admin->id);
    expect(session('impersonating'))->toEqual($target->name);
});

test('impersonator can stop impersonating', function () {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->create();

    // Start impersonation
    $this
        ->actingAs($admin)
        ->post(route('admin.impersonation.store'), [
            'user_id' => $target->id,
        ]);
    $this->assertAuthenticatedAs($target);

    // Stop impersonation
    session(['impersonator_id' => $admin->id, 'impersonating' => $target->name]);
    $response = $this
        ->be($target)
        ->delete(route('impersonation.destroy'));
    $response->assertRedirect(route('admin.users.index'));
    $this->assertAuthenticatedAs($admin);
    expect(session('impersonator_id'))->toBeNull();
    expect(session('impersonating'))->toBeNull();
});

test('non admin cannot impersonate', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();
    $response = $this
        ->be($user)
        ->post(route('admin.impersonation.store'), [
            'user_id' => $target->id,
        ]);
    $response->assertForbidden();
});

test('cannot stop impersonating if not impersonating', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user);
    $response = $this->delete(route('impersonation.destroy'));
    $response->assertNotFound();
});

test('admin cannot impersonate another admin', function () {
    $admin = User::factory()->admin()->create();
    $targetAdmin = User::factory()->admin()->create();

    $response = $this
        ->be($admin)
        ->post(route('admin.impersonation.store'), [
            'user_id' => $targetAdmin->id,
        ]);

    $response->assertForbidden();
    $this->assertAuthenticatedAs($admin);
});

test('impersonation is logged', function () {
    Log::spy();

    $admin = User::factory()->admin()->create();
    $target = User::factory()->create();

    $this
        ->be($admin)
        ->post(route('admin.impersonation.store'), [
            'user_id' => $target->id,
        ]);

    Log::shouldHaveReceived('info')->with('Impersonation started', [
        'admin_id' => $admin->id,
        'target_user_id' => $target->id,
    ]);
});

test('stopping impersonation is logged', function () {
    Log::spy();

    $admin = User::factory()->admin()->create();
    $target = User::factory()->create();

    session(['impersonator_id' => $admin->id, 'impersonating' => $target->name]);

    $this
        ->be($target)
        ->delete(route('impersonation.destroy'));

    Log::shouldHaveReceived('info')->with('Impersonation ended', [
        'admin_id' => $admin->id,
        'impersonated_user_id' => $target->id,
    ]);
});
