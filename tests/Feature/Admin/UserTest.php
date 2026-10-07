<?php

namespace Tests\Feature\Admin;

use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_index_redirects_to_users(): void
    {
        $this
            ->be(User::factory()->admin()->create())
            ->get('/admin')
            ->assertRedirect(route('admin.users.index'));
    }

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

    public function test_admin_cannot_delete_the_sole_admin_of_a_team_with_other_members(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

        $this
            ->be($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertSessionHasErrors('team');
        $this->assertModelExists($user);

        $user->team->users()->updateExistingPivot($member, ['role' => UserRole::ADMIN]);

        $this
            ->delete(route('admin.users.destroy', $user))
            ->assertSessionHasNoErrors();
        $this->assertModelMissing($user);
    }
}
