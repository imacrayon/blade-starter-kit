<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_team(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->post(route('teams.store', [
                'name' => 'Test Team',
            ]));

        $response->assertRedirect();
        $this->assertSame('Test Team', $user->fresh()->team->name);
    }

    public function test_team_admin_can_view_team(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

        $response = $this
            ->be($user)
            ->get(route('teams.members.index', $user->team));

        $response
            ->assertOk()
            ->assertSee($member->name);
    }

    public function test_team_member_can_view_team(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

        $response = $this
            ->be($member)
            ->get(route('teams.members.index', $user->team));

        $response
            ->assertOk()
            ->assertSee($user->name);
    }

    public function test_team_member_cannot_update_team(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team, UserRole::MEMBER);

        $this
            ->be($member)
            ->put(route('teams.update', $admin->team), ['name' => 'Renamed'])
            ->assertForbidden();
        $this->assertNotSame('Renamed', $admin->team->fresh()->name);
    }

    public function test_leave_is_hidden_on_the_users_only_team(): void
    {
        $user = User::factory()->create();

        $this->be($user)->get(route('teams.edit', $user->team))
            ->assertOk()
            ->assertDontSee(route('settings.teams.destroy', $user->team));
    }

    public function test_only_team_admins_see_the_delete_team_form(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team, UserRole::MEMBER);

        $action = 'action="'.route('teams.destroy', $admin->team).'"';

        $this->be($admin)->get(route('teams.edit', $admin->team))->assertOk()->assertSee($action, false);
        $this->be($member)->get(route('teams.edit', $admin->team))->assertOk()->assertDontSee($action, false);
    }

    public function test_team_admin_can_delete_team(): void
    {
        $admin = User::factory()->create();
        $team = Team::forUser($admin, ['name' => 'Doomed']);
        $member = User::factory()->create()->joinTeam($team, UserRole::MEMBER);
        Invitation::factory()->for($team)->create(['sender_id' => $admin->id]);

        $this
            ->be($admin)
            ->delete(route('teams.destroy', $team), ['team_name' => 'Doomed'])
            ->assertRedirectToRoute('app');

        $this->assertModelMissing($team);
        $this->assertDatabaseMissing('invitations', ['team_id' => $team->id]);

        $this->be($member->fresh())->get(route('app'))->assertOk();
        $this->assertNotNull($member->fresh()->team_id);
    }

    public function test_team_member_cannot_delete_team(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team, UserRole::MEMBER);

        $this
            ->be($member)
            ->delete(route('teams.destroy', $admin->team), ['team_name' => $admin->team->name])
            ->assertForbidden();
        $this->assertModelExists($admin->team);
    }

    public function test_team_name_must_be_confirmed_to_delete_team(): void
    {
        $admin = User::factory()->create();

        $this
            ->be($admin)
            ->delete(route('teams.destroy', $admin->team), ['team_name' => 'Wrong'])
            ->assertSessionHasErrors('team_name');
        $this->assertModelExists($admin->team);
    }

    public function test_visiting_a_team_does_not_make_it_current(): void
    {
        $user = User::factory()->create();
        $current = $user->team;
        $other = Team::factory()->create();
        $user->joinTeam($other)->switchTeam($current);

        $this
            ->be($user->fresh())
            ->get(route('teams.show', $other))
            ->assertOk();

        $this->assertSame($current->id, $user->fresh()->team_id);
    }

    public function test_user_can_switch_to_a_team_they_belong_to(): void
    {
        $user = User::factory()->create();
        $other = Team::factory()->create();
        $user->joinTeam($other)->switchTeam(Team::factory()->create());

        $this
            ->be($user->fresh())
            ->put(route('teams.current.update', $other))
            ->assertRedirect(route('teams.show', $other));

        $this->assertSame($other->id, $user->fresh()->team_id);
    }

    public function test_admin_cannot_switch_to_a_team_they_do_not_belong_to(): void
    {
        $admin = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $this
            ->be($admin)
            ->get(route('teams.members.index', $team))
            ->assertOk();

        $this
            ->put(route('teams.current.update', $team))
            ->assertForbidden();

        $this->assertNotSame($team->id, $admin->fresh()->team_id);
    }

    public function test_teams_get_distinct_non_empty_slugs(): void
    {
        $first = Team::factory()->create(['name' => '!!!']);
        $second = Team::factory()->create(['name' => '!!!']);

        $this->assertNotSame('', $first->slug);
        $this->assertNotSame($first->slug, $second->slug);
    }
}
