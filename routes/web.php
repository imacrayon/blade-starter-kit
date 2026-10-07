<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CurrentTeamController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ResentInvitationController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Settings\AppearanceController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('/', 'home')->name('home');

Route::get('invitations/{invitation:code}', [InvitationController::class, 'show'])->name('teams.invitations.show');

if (Features::enabled(Features::passkeys())) {
    Route::get('.well-known/passkey-endpoints', fn () => [
        'enroll' => route('settings.security.edit'),
        'manage' => route('settings.security.edit'),
    ])->name('well-known.passkeys');
}

Route::middleware(['auth'])->group(function () {
    Route::get('memberships/{invitation:code}', [TeamMemberController::class, 'create'])->name('teams.members.create');
    Route::post('memberships/{invitation:code}', [TeamMemberController::class, 'store'])->name('teams.members.store');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('settings.profile.destroy')->middleware('throttle:6,1');
    Route::get('settings/security', [SecurityController::class, 'edit'])->name('settings.security.edit')->middleware(['verified', 'password.confirm']);
    Route::put('settings/security', [SecurityController::class, 'update'])->name('settings.security.update')->middleware(['verified', 'throttle:6,1']);
    Route::get('settings/appearance', [AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    Route::delete('impersonation', [Admin\ImpersonationController::class, 'destroy'])->name('impersonation.destroy');

    Route::middleware(['verified'])->group(function () {
        Route::get('app', AppController::class)->name('app');

        Route::get('settings/teams', [Settings\TeamController::class, 'index'])->name('settings.teams.index');
        Route::get('settings/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('settings/teams', [TeamController::class, 'store'])->name('teams.store');
        Route::delete('settings/teams/{team}', [Settings\TeamController::class, 'destroy'])->name('settings.teams.destroy')->can('leave', 'team');

        Route::middleware('can:admin')->prefix('admin')->group(function () {
            Route::redirect('/', '/admin/users');
            Route::get('users', [Admin\UserController::class, 'index'])->name('admin.users.index');
            Route::get('users/{user}/edit', [Admin\UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('users/{user}', [Admin\UserController::class, 'update'])->name('admin.users.update');
            Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

            Route::get('teams', [Admin\TeamController::class, 'index'])->name('admin.teams.index');
            Route::delete('teams/{team}', [Admin\TeamController::class, 'destroy'])->name('admin.teams.destroy');

            Route::post('impersonation', [Admin\ImpersonationController::class, 'store'])->name('admin.impersonation.store');
        });

        Route::prefix('teams/{team}')->middleware(EnsureTeamMembership::class)->scopeBindings()->group(function () {
            Route::get('/', [TeamController::class, 'show'])->name('teams.show');
            Route::get('edit', [TeamController::class, 'edit'])->name('teams.edit');
            Route::put('/', [TeamController::class, 'update'])->name('teams.update')->can('update', 'team');
            Route::delete('/', [TeamController::class, 'destroy'])->name('teams.destroy')->can('destroy', 'team');
            Route::put('current', CurrentTeamController::class)->name('teams.current.update')->can('switch', 'team');

            Route::get('invitations', [InvitationController::class, 'index'])->name('teams.invitations.index')->can('update', 'team');
            Route::post('invitations', [InvitationController::class, 'store'])->name('teams.invitations.store')->can('update', 'team');
            Route::post('invitations/{invitation}/resend', ResentInvitationController::class)->name('teams.invitations.resend')->can('update', 'team');
            Route::delete('invitations/{invitation}', [InvitationController::class, 'destroy'])->name('teams.invitations.destroy')->can('update', 'team');

            Route::get('members', [TeamMemberController::class, 'index'])->name('teams.members.index');
            Route::get('members/{user}', [TeamMemberController::class, 'edit'])->name('teams.members.edit')->can('update', 'team');
            Route::put('members/{user}', [TeamMemberController::class, 'update'])->name('teams.members.update')->can('update', 'team');
            Route::delete('members/{user}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy')->can('update', 'team');
        });
    });
});
