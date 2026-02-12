<?php

use App\Models\User;
use App\UserRole;

test('user can create team', function () {
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->post(route('teams.store', [
            'name' => 'Test Team',
        ]));

    $response->assertRedirect();
    expect($user->team->name)->toBe('Test Team');
});

test('team admin can view team', function () {
    $user = User::factory()->create();
    $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

    $response = $this
        ->be($user)
        ->get(route('teams.show', $user->team));

    $response
        ->assertOk()
        ->assertSee($member->name);
});

test('team member can view team', function () {
    $user = User::factory()->create();
    $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

    $response = $this
        ->be($member)
        ->get(route('teams.show', $user->team));

    $response
        ->assertOk()
        ->assertSee($user->name);
});