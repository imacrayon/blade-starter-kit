<?php

namespace Tests\Feature\Admin;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_user_and_their_memberships_are_removed(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $extraTeam = Team::factory()->create();
        $user->joinTeam($extraTeam);

        $this
            ->be($admin)
            ->delete(route('admin.users.destroy', $user));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('memberships', ['user_id' => $user->id]);
    }
}
