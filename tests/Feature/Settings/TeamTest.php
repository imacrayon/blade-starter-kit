<?php

namespace Tests\Feature\Settings;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_teams(): void
    {
        $user = User::factory()->create()->joinTeam(Team::factory()->create());

        $response = $this->be($user)->get(route('settings.teams.index'));

        $response->assertOk();
        $user->teams->each(fn (Team $team) => $response
            ->assertSee($team->name)
            ->assertSee(route('settings.teams.destroy', $team)));
        $response->assertSee(route('teams.create'));
    }

    public function test_user_cannot_leave_their_only_team(): void
    {
        $user = User::factory()->create();

        $this->be($user)->get(route('settings.teams.index'))
            ->assertOk()
            ->assertSee($user->team->name)
            ->assertDontSee(route('settings.teams.destroy', $user->team));

        $this->be($user)
            ->delete(route('settings.teams.destroy', $user->team))
            ->assertForbidden();
        $this->assertTrue($user->team->fresh()->users->contains($user));
    }

    public function test_leave_is_hidden_from_a_site_admin_outside_the_team(): void
    {
        $admin = User::factory()->admin()->create();
        Team::forUser($admin);
        $team = Team::factory()->create();

        $this->be($admin)->get(route('teams.edit', $team))
            ->assertOk()
            ->assertDontSee(route('settings.teams.destroy', $team));
    }

    public function test_leave_is_hidden_from_a_sole_admin_while_other_members_remain(): void
    {
        $admin = User::factory()->create();
        $shared = Team::forUser($admin, ['name' => 'Shared']);
        User::factory()->create()->joinTeam($shared);

        $this->be($admin)->get(route('settings.teams.index'))
            ->assertOk()
            ->assertDontSee(route('settings.teams.destroy', $shared));

        $this->be($admin)->get(route('teams.edit', $shared))
            ->assertOk()
            ->assertDontSee(route('settings.teams.destroy', $shared));
    }

    public function test_last_member_is_warned_that_leaving_deletes_the_team(): void
    {
        $user = User::factory()->create();
        $team = Team::forUser($user, ['name' => 'Side Project']);

        $this->be($user)->get(route('settings.teams.index'))
            ->assertOk()
            ->assertSee('Side Project will be deleted because you are its last member.');

        $this->be($user)->get(route('teams.edit', $team))
            ->assertOk()
            ->assertSee('Side Project will be deleted because you are its last member.');
    }

    public function test_team_member_can_leave_team(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create();
        $current = $member->team;
        $member->joinTeam($admin->team)->update(['team_id' => $current->id]);

        $response = $this->be($member)->delete(route('settings.teams.destroy', $admin->team));

        $response
            ->assertRedirect(route('settings.teams.index'))
            ->assertSessionHasNoErrors();
        $this->assertFalse($admin->team->fresh()->users->contains($member));
        $this->assertTrue($member->fresh()->team->is($current));
    }

    public function test_last_member_leaving_deletes_the_team(): void
    {
        $user = User::factory()->create();
        $remaining = $user->team;
        $team = Team::forUser($user, ['name' => 'Side Project']);
        Invitation::factory()->for($team)->create(['sender_id' => $user->id]);

        $this->be($user)
            ->delete(route('settings.teams.destroy', $team))
            ->assertSessionHasNoErrors();

        $this->assertModelMissing($team);
        $this->assertDatabaseMissing('invitations', ['team_id' => $team->id]);
        $this->assertTrue($user->fresh()->team->is($remaining));
    }

    public function test_sole_admin_cannot_leave_while_other_members_remain(): void
    {
        $admin = User::factory()->create();
        $team = Team::forUser($admin, ['name' => 'Shared']);
        User::factory()->create()->joinTeam($team);

        $this->be($admin)
            ->delete(route('settings.teams.destroy', $team))
            ->assertForbidden();
        $this->assertTrue($team->fresh()->users->contains($admin));
    }

    public function test_user_cannot_leave_team_they_do_not_belong_to(): void
    {
        $this->be(User::factory()->create())
            ->delete(route('settings.teams.destroy', Team::factory()->create()))
            ->assertForbidden();
    }
}
