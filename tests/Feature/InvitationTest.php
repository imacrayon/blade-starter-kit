<?php

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Support\Facades\Mail;

test('team admins can send invitations', function () {
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
});

test('team admin can resend invitation', function () {
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
});

test('team members cannot send invitations', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create()->joinTeam($team, UserRole::MEMBER);

    $response = $this
        ->be($user)
        ->post(route('teams.invitations.store', $team), [
            'email' => 'invitee@example.com',
            'role' => UserRole::MEMBER->value,
        ]);

    $response->assertForbidden();
});

test('team admin can revoke invitation', function () {
    $user = User::factory()->create();
    $invitation = Invitation::factory()->create(['team_id' => $user->team->id]);

    $response = $this
        ->be($user)
        ->delete(route('invitations.destroy', $invitation));

    $response->assertRedirectBack();
    $this->assertModelMissing($invitation);
});

test('user can register with invitation code', function () {
    $invitation = Invitation::factory()->create([
        'role' => UserRole::MEMBER::ADMIN,
    ]);

    $response = $this->post($invitation->url(), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('app'));
    $this->assertAuthenticated();
    $user = User::latest('id')->first();
    expect($user->teams)->toHaveCount(1);
    expect($user->team_id)->toBe($invitation->team_id);
    expect($user->teams->first()->membership->role)->toBe($invitation->role);
});