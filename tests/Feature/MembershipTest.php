<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_join_team(): void
    {
        $team = Team::factory()->create();
        $invitation = Invitation::factory()->for($team)->create();
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->post(route('teams.memberships.store', $invitation));

        $response->assertRedirect(route('app'));
        $this->assertTrue($user->team->is($team));
    }

    public function test_user_can_leave_team(): void
    {
        $user = User::factory()->create();
        $team = $user->team;

        $response = $this
            ->be($user)
            ->delete(route('teams.memberships.destroy', [$team, $user]));

        $response->assertRedirect(route('app'));
        $this->assertCount(0, $team->users);
        $this->assertTrue($user->fresh()->team->isNot($team));
    }

    public function test_team_admin_can_update_team_member_role(): void
    {
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
            $this->assertCount(2, $team->users->filter(fn ($user) => $user->membership->role === UserRole::ADMIN));
        });
    }

    public function test_team_admin_can_remove_team_member(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team);

        $response = $this
            ->be($admin)
            ->delete(route('teams.memberships.destroy', [$admin->team, $member]));

        $response
            ->assertRedirect(route('teams.show', $admin->team))
            ->assertSessionHasNoErrors();
        $this->assertCount(1, $admin->team->fresh()->users);
    }

    public function test_sole_team_admin_cannot_change_their_role(): void
    {
        $admin = User::factory()->create();

        $response = $this
            ->be($admin)
            ->put(route('teams.memberships.update', [$admin->team, $admin]), [
                'role' => UserRole::MEMBER->value,
            ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_sole_team_admin_cannot_leave_if_there_are_multiple_team_members(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team);

        $response = $this
            ->be($admin)
            ->delete(route('teams.memberships.destroy', [$admin->team, $admin]));

        $response->assertSessionHasErrors('role');
    }
}
