<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_team_and_its_members_move_to_another_team(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        $team = $member->team;
        $invitation = Invitation::factory()->create(['team_id' => $team->id]);

        $this
            ->be($admin)
            ->delete(route('admin.teams.destroy', $team))
            ->assertRedirectToRoute('admin.teams.index');

        $this->assertModelMissing($team);
        $this->assertModelMissing($invitation);
        $this->assertDatabaseMissing('memberships', ['team_id' => $team->id]);

        $this
            ->be($member->fresh())
            ->get(route('app'))
            ->assertOk();
        $this->assertNotSame($team->id, $member->fresh()->team_id);
    }
}
