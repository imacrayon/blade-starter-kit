<?php

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;

test('user can join team', function () {
    $team = Team::factory()->create();
    $invitation = Invitation::factory()->for($team)->create();
    $user = User::factory()->create();

    $response = $this
        ->be($user)
        ->post(route('teams.memberships.store', $invitation));

    $response->assertRedirect(route('app'));
    expect($user->team->is($team))->toBeTrue();
});

test('user can leave team', function () {
    $user = User::factory()->create();
    $team = $user->team;

    $response = $this
        ->be($user)
        ->delete(route('teams.memberships.destroy', [$team, $user]));

    $response->assertRedirect(route('app'));
    expect($team->users)->toHaveCount(0);
    expect($user->fresh()->team->isNot($team))->toBeTrue();
});

test('team admin can update team member role', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create()->joinTeam($admin->team);

    $response = $this
        ->be($admin)
        ->put(route('teams.memberships.update', [$admin->team, $member]), [
            'role' => UserRole::ADMIN->value,
        ]);

    $response
        ->assertRedirect(route('teams.show', $admin->team))
        ->assertSessionHasNoErrors();

    tap($admin->team->fresh(), function ($team) {
        expect($team->users->filter(fn ($user) => $user->membership->role === UserRole::ADMIN))->toHaveCount(2);
    });
});

test('team admin can remove team member', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create()->joinTeam($admin->team);

    $response = $this
        ->be($admin)
        ->delete(route('teams.memberships.destroy', [$admin->team, $member]));

    $response
        ->assertRedirect(route('teams.show', $admin->team))
        ->assertSessionHasNoErrors();
    expect($admin->team->fresh()->users)->toHaveCount(1);
});

test('sole team admin cannot change their role', function () {
    $admin = User::factory()->create();

    $response = $this
        ->be($admin)
        ->put(route('teams.memberships.update', [$admin->team, $admin]), [
            'role' => UserRole::MEMBER->value,
        ]);

    $response->assertSessionHasErrors('role');
});

test('sole team admin cannot leave if there are multiple team members', function () {
    $admin = User::factory()->create();
    $member = User::factory()->create()->joinTeam($admin->team);

    $response = $this
        ->be($admin)
        ->delete(route('teams.memberships.destroy', [$admin->team, $admin]));

    $response->assertSessionHasErrors('role');
});