<?php

use App\Models\Team;
use App\Models\User;

test('admin can delete a user and their memberships are removed', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $extraTeam = Team::factory()->create();
    $user->joinTeam($extraTeam);

    $this
        ->be($admin)
        ->delete(route('admin.users.destroy', $user));

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('memberships', ['user_id' => $user->id]);
});
