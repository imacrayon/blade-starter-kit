<?php

namespace Tests\Feature;

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
        $this->assertSame('Test Team', $user->team->name);
    }

    public function test_team_admin_can_view_team(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

        $response = $this
            ->be($user)
            ->get(route('teams.show', $user->team));

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
            ->get(route('teams.show', $user->team));

        $response
            ->assertOk()
            ->assertSee($user->name);
    }
}
