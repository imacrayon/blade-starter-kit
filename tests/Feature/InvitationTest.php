<?php

namespace Tests\Feature;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_admins_can_send_invitations(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->post(route('teams.invitations.store', $user->team), [
                'email' => 'invitee@example.com',
                'role' => UserRole::MEMBER->value,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('invitations', [
            'email' => 'invitee@example.com',
            'role' => UserRole::MEMBER->value,
            'team_id' => $user->team->id,
        ]);
        Mail::assertSent(InvitationMail::class, function ($mail) {
            return $mail->hasTo('invitee@example.com');
        });
    }

    public function test_team_admin_can_resend_invitation(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['team_id' => $user->team->id]);

        $response = $this
            ->be($user)
            ->post(route('invitations.resend', $invitation));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectBack();

        Mail::assertSent(InvitationMail::class, function ($mail) use ($invitation) {
            return $mail->hasTo($invitation->email) && $mail->invitation->is($invitation);
        });
    }

    public function test_team_members_cannot_send_invitations(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create()->joinTeam($team, UserRole::MEMBER);

        $response = $this
            ->be($user)
            ->post(route('teams.invitations.store', $team), [
                'email' => 'invitee@example.com',
                'role' => UserRole::MEMBER->value,
            ]);

        $response->assertForbidden();
    }

    public function test_team_admin_can_revoke_invitation(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['team_id' => $user->team->id]);

        $response = $this
            ->be($user)
            ->delete(route('invitations.destroy', $invitation));

        $response->assertRedirectBack();
        $this->assertModelMissing($invitation);
    }
}
