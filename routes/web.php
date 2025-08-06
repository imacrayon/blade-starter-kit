<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AppController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ResentInvitationController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/invitations/{invitation:code}', [InvitationController::class, 'show'])->name('teams.invitations.show');

Route::middleware(['auth'])->prefix('app')->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/team', [Settings\TeamController::class, 'update'])->name('settings.team.update');

    Route::get('/invitations/{invitation:code}', [MembershipController::class, 'create'])->name('teams.memberships.create');
    Route::post('/invitations/{invitation:code}', [MembershipController::class, 'store'])->name('teams.memberships.store');

    Route::middleware(['verified'])->group(function () {
        Route::get('/', AppController::class)->name('app');

        Route::get('teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
        Route::get('teams/{team}', [TeamController::class, 'show'])->name('teams.show')->can('view', 'team');
        Route::get('teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit')->can('update', 'team');
        Route::put('teams/{team}', [TeamController::class, 'update'])->name('teams.update')->can('update', 'team');

        Route::get('teams/{team}/invitations', [InvitationController::class, 'index'])->name('teams.invitations.index')->can('update', 'team');
        Route::post('teams/{team}/invitations', [InvitationController::class, 'store'])->name('teams.invitations.store')->can('update', 'team');

        Route::get('teams/{team}/users/{user}', [MembershipController::class, 'edit'])->name('teams.memberships.edit')->can('update', 'team');
        Route::put('teams/{team}/users/{user}', [MembershipController::class, 'update'])->name('teams.memberships.update')->can('update', 'team');
        Route::delete('teams/{team}/users/{user}', [MembershipController::class, 'destroy'])->name('teams.memberships.destroy')->can('update', 'team');

        Route::post('invitations/{invitation}/resend', ResentInvitationController::class)->name('invitations.resend')->can('update', 'invitation');
        Route::delete('invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy')->can('delete', 'invitation');

        Route::middleware('can:admin')->group(function () {
            Route::redirect('admin', 'admin/users');
            Route::get('admin/users', [Admin\UserController::class, 'index'])->name('admin.users.index');
            Route::get('admin/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('admin/users/{user}', [Admin\UserController::class, 'update'])->name('admin.users.update');
            Route::delete('admin/users/{user}', [Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

            Route::get('admin/teams', [Admin\TeamController::class, 'index'])->name('admin.teams.index');
            Route::delete('admin/teams/{team}', [Admin\TeamController::class, 'destroy'])->name('admin.teams.destroy');

            Route::post('impersonation', [Admin\ImpersonationController::class, 'store'])->name('admin.impersonation.store');
        });

        Route::delete('impersonation', [Admin\ImpersonationController::class, 'destroy'])->name('impersonation.destroy');
    });
});

require __DIR__.'/auth.php';
