<?php

namespace Tests\Feature\Settings;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $this->be(User::factory()->create());

        $this->get(route('settings.profile.edit'))->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->put(route('settings.profile.update'), [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('settings.profile.edit'));

        $user->refresh();

        $this->assertSame('Test', $user->first_name);
        $this->assertSame('User', $user->last_name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_email_verification_status_is_unchanged_when_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->put(route('settings.profile.update'), [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('settings.profile.edit'));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->delete(route('settings.profile.edit'), [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_deleting_an_account_deletes_teams_left_without_members(): void
    {
        $user = User::factory()->create();
        $personalTeam = $user->team;
        $sharedTeam = Team::factory()->create();
        $user->joinTeam($sharedTeam);
        User::factory()->create()->joinTeam($sharedTeam, UserRole::ADMIN);

        $this
            ->be($user)
            ->delete(route('settings.profile.destroy'), ['password' => 'password'])
            ->assertSessionHasNoErrors();

        $this->assertModelMissing($personalTeam);
        $this->assertModelExists($sharedTeam);
    }

    public function test_user_who_sent_an_invitation_can_delete_their_account(): void
    {
        $user = User::factory()->create();
        $invitation = Invitation::factory()->create(['sender_id' => $user->id]);

        $this
            ->be($user)
            ->delete(route('settings.profile.destroy'), ['password' => 'password'])
            ->assertSessionHasNoErrors();

        $this->assertModelMissing($user);
        $this->assertModelMissing($invitation);
    }

    public function test_sole_admin_of_a_team_with_other_members_cannot_delete_their_account(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create()->joinTeam($user->team, UserRole::MEMBER);

        $this
            ->be($user)
            ->delete(route('settings.profile.destroy'), ['password' => 'password'])
            ->assertSessionHasErrors('team');
        $this->assertModelExists($user);

        $user->team->users()->updateExistingPivot($member, ['role' => UserRole::ADMIN]);

        $this
            ->be($user->fresh())
            ->delete(route('settings.profile.destroy'), ['password' => 'password'])
            ->assertSessionHasNoErrors();
        $this->assertModelMissing($user);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->be($user)
            ->delete(route('settings.profile.edit'), [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirectBack();

        $this->assertNotNull($user->fresh());
    }
}
