<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InvitationNotification;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_admins_can_send_invitations(): void
    {
        Notification::fake();

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
        Notification::assertSentOnDemand(
            InvitationNotification::class,
            function (InvitationNotification $notification, array $channels, object $notifiable) {
                return $notifiable->routes['mail'] === 'invitee@example.com';
            }
        );
    }

    public function test_existing_team_members_cannot_be_invited(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $member = User::factory()->create()->joinTeam($admin->team);

        $this
            ->be($admin)
            ->post(route('teams.invitations.store', $admin->team), [
                'email' => strtoupper($member->email),
                'role' => UserRole::MEMBER->value,
            ])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('invitations', 0);
        Notification::assertNothingSent();
    }

    public function test_invitation_email_must_be_a_string(): void
    {
        $admin = User::factory()->create();

        $this
            ->be($admin)
            ->post(route('teams.invitations.store', $admin->team), [
                'email' => ['invitee@example.com'],
                'role' => UserRole::MEMBER->value,
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_reinviting_an_email_updates_the_existing_invitation(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $invitation = Invitation::factory()->for($admin->team)->create(['email' => 'invitee@example.com']);

        $this
            ->be($admin)
            ->post(route('teams.invitations.store', $admin->team), [
                'email' => 'Invitee@Example.com',
                'role' => UserRole::ADMIN->value,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('invitations', 1);
        $this->assertSame(UserRole::ADMIN, $invitation->fresh()->role);
    }

    public function test_team_admin_can_resend_invitation(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $invitation = Invitation::factory()->create([
            'team_id' => $user->team->id,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this
            ->be($user)
            ->post(route('teams.invitations.resend', [$user->team, $invitation]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirectBack();

        $invitation->refresh();
        $this->assertTrue($invitation->expires_at->isFuture());
        $this->assertTrue($invitation->sender->is($user));
        Notification::assertSentOnDemand(
            InvitationNotification::class,
            function (InvitationNotification $notification) use ($invitation) {
                return $notification->invitation->is($invitation);
            }
        );
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
            ->delete(route('teams.invitations.destroy', [$user->team, $invitation]));

        $response->assertRedirectBack();
        $this->assertModelMissing($invitation);
    }

    public function test_user_can_register_with_invitation_code(): void
    {
        $invitation = Invitation::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->post(route('register'), [
            'code' => $invitation->code,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
        $user = User::latest('id')->first();
        $this->assertCount(1, $user->teams);
        $this->assertSame($invitation->team_id, $user->team_id);
        $this->assertSame($invitation->role, $user->teams->first()->membership->role);
    }

    public function test_invitee_cannot_access_their_team_until_verified(): void
    {
        $invitation = Invitation::factory()->create();

        $this->post(route('register'), [
            'code' => $invitation->code,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->get(route('app'))->assertRedirectToRoute('verification.notice');
        $this->get(route('teams.show', $invitation->team))->assertRedirectToRoute('verification.notice');

        $user = User::firstWhere('email', 'test@example.com');
        $user->markEmailAsVerified();

        $this->be($user)->get(route('app'))->assertOk()->assertSee($invitation->team->name);
    }

    public function test_registering_with_an_expired_invitation_creates_the_account_and_says_why(): void
    {
        $invitation = Invitation::factory()->create(['expires_at' => now()->subMinute()]);

        $this->post(route('register'), [
            'code' => $invitation->code,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasNoErrors();

        $this->assertAuthenticated();
        $this->assertFalse(User::firstWhere('email', 'test@example.com')->belongsToTeam($invitation->team));

        $this->get(route('verification.notice'))->assertSee('Your invitation is no longer valid.');
        $this->get(route('verification.notice'))->assertDontSee('Your invitation is no longer valid.');
    }

    public function test_auth_screens_show_invitation_context(): void
    {
        $invitation = Invitation::factory()->create();
        $team = $invitation->team->name;

        $this->get(route('teams.invitations.show', $invitation))
            ->assertSee(route('register', ['code' => $invitation->code]))
            ->assertSee(route('login', ['code' => $invitation->code]));

        $this->get(route('login', ['code' => $invitation->code]))
            ->assertSee(route('register', ['code' => $invitation->code]));

        $this->get(route('register', ['code' => $invitation->code]))
            ->assertSee(route('login', ['code' => $invitation->code]));
    }

    public function test_auth_screens_ignore_expired_invitations(): void
    {
        $invitation = Invitation::factory()->create(['expires_at' => now()->subMinute()]);

        $this->get(route('login', ['code' => $invitation->code]))
            ->assertDontSee($invitation->team->name)
            ->assertDontSee(route('register', ['code' => $invitation->code]));

        $this->get(route('register', ['code' => $invitation->code]))
            ->assertDontSee($invitation->team->name)
            ->assertDontSee($invitation->code);
    }

    public function test_expired_invitations_are_not_listed(): void
    {
        $user = User::factory()->create();
        $pending = Invitation::factory()->for($user->team)->create();
        $expired = Invitation::factory()->for($user->team)->create(['expires_at' => now()->subMinute()]);

        $this
            ->be($user)
            ->get(route('teams.invitations.index', $user->team))
            ->assertSee($pending->email)
            ->assertDontSee($expired->email);
    }

    public function test_logging_in_with_an_invitation_lands_on_the_join_page(): void
    {
        $invitation = Invitation::factory()->create();
        $user = User::factory()->create();

        $this->get(route('login', ['code' => $invitation->code]));

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('teams.members.create', $invitation));
    }

    public function test_signing_up_after_viewing_login_accepts_the_invitation(): void
    {
        $invitation = Invitation::factory()->create();

        $this->get(route('login', ['code' => $invitation->code]));
        $this->get(route('register', ['code' => $invitation->code]));

        $this->post(route('register.store'), [
            'code' => $invitation->code,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('app'));

        $this->assertTrue(User::firstWhere('email', 'test@example.com')->belongsToTeam($invitation->team));
    }

    public function test_expired_invitations_are_pruned(): void
    {
        $expired = Invitation::factory()->create(['expires_at' => now()->subMinute()]);
        $pending = Invitation::factory()->create();

        $this->artisan('model:prune', ['--model' => Invitation::class])->assertSuccessful();

        $this->assertModelMissing($expired);
        $this->assertModelExists($pending);
    }

    public function test_expired_invitation_links_are_not_found(): void
    {
        $invitation = Invitation::factory()->create(['expires_at' => now()->subMinute()]);

        $this->get(route('teams.invitations.show', $invitation))->assertNotFound();
    }

    public function test_admin_opening_an_invitation_link_stays_logged_in(): void
    {
        $admin = User::factory()->admin()->create();
        $invitation = Invitation::factory()->create();

        $this
            ->be($admin)
            ->get(route('teams.invitations.show', $invitation))
            ->assertRedirectToRoute('teams.members.create', $invitation);

        $this->assertAuthenticatedAs($admin);
    }
}
