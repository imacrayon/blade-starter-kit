<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
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
            ->post(route('teams.members.store', $invitation));

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->team->is($team));
    }

    public function test_user_can_leave_team(): void
    {
        $user = User::factory()->create();
        $team = $user->team;
        $otherTeam = Team::factory()->create();
        $user->joinTeam($otherTeam)->update(['team_id' => $team->id]);

        $response = $this
            ->be($user)
            ->delete(route('teams.members.destroy', [$team, $user]));

        $response->assertRedirect(route('app'));
        $this->assertModelMissing($team);
        $this->assertTrue($user->fresh()->team->is($otherTeam));
    }

    public function test_user_cannot_leave_their_only_team(): void
    {
        $user = User::factory()->create();

        $this
            ->be($user)
            ->delete(route('teams.members.destroy', [$user->team, $user]))
            ->assertForbidden();

        $this->assertTrue($user->team->users()->whereKey($user->id)->exists());
    }

    public function test_site_admin_removing_the_last_member_deletes_the_team(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $team = $user->team;

        $this
            ->be($admin)
            ->delete(route('teams.members.destroy', [$team, $user]))
            ->assertRedirect(route('admin.teams.index'));

        $this->assertModelMissing($team);
    }

    public function test_team_admin_can_update_team_member_role(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team);

        $response = $this
            ->be($admin)
            ->put(route('teams.members.update', [$admin->team, $member]), [
                'role' => UserRole::ADMIN->value,
            ]);

        $response
            ->assertRedirect(route('teams.members.index', $admin->team))
            ->assertSessionHasNoErrors();

        tap($admin->team->fresh(), function ($team) {
            $this->assertCount(2, $team->users->filter(fn ($user) => $user->membership->role === UserRole::ADMIN));
        });
    }

    public function test_removing_a_team_member_asks_for_confirmation(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create(['first_name' => 'Conan', 'last_name' => "O'Brien"])->joinTeam($admin->team);

        $this
            ->be($admin)
            ->get(route('teams.members.edit', [$admin->team, $member]))
            ->assertOk()
            ->assertSee('onsubmit="return confirm(&#039;Conan O\u0027Brien will be removed from this team&#039;)"', false);
    }

    public function test_team_admin_can_remove_team_member(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team);

        $response = $this
            ->be($admin)
            ->delete(route('teams.members.destroy', [$admin->team, $member]));

        $response
            ->assertRedirect(route('teams.members.index', $admin->team))
            ->assertSessionHasNoErrors();
        $this->assertCount(1, $admin->team->fresh()->users);
    }

    public function test_sole_team_admin_cannot_change_their_role(): void
    {
        $admin = User::factory()->create();

        $response = $this
            ->be($admin)
            ->put(route('teams.members.update', [$admin->team, $admin]), [
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
            ->delete(route('teams.members.destroy', [$admin->team, $admin]));

        $response->assertSessionHasErrors('role');
    }

    public function test_sole_team_admin_cannot_be_deleted_while_other_members_remain(): void
    {
        $admin = User::factory()->create();
        User::factory()->create()->joinTeam($admin->team);

        try {
            $admin->delete();
            $this->fail('Deleting the sole admin should fail.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('team', $e->errors());
        }

        $this->assertModelExists($admin);
    }
}
