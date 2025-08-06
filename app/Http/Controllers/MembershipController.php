<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MembershipController extends Controller
{
    public function create(Invitation $invitation)
    {
        return view('teams.memberships.create', [
            'invitation' => $invitation,
        ]);
    }

    public function store(Request $request, Invitation $invitation)
    {
        $invitation->accept($request->user());

        return redirect()->intended(route('app'));
    }

    public function edit(Team $team, $user)
    {
        return view('teams.memberships.edit', [
            'team' => $team,
            'user' => $team->users()->findOrFail($user),
        ]);
    }

    public function update(Request $request, Team $team, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        if ($request->enum('role', UserRole::class) !== UserRole::ADMIN) {
            $admins = $team->users->filter(fn ($user) => $user->membership->role === UserRole::ADMIN);
            throw_if(
                $admins->count() === 1 && $admins->contains($user),
                ValidationException::withMessages(['role' => 'Teams must have at least one admin.'])
            );
        }

        $team->users()->updateExistingPivot($user, [
            'role' => $request->role,
        ]);

        return to_route('teams.show', $team);
    }

    public function destroy(Request $request, Team $team, User $user): RedirectResponse
    {
        if ($team->users->count() !== 1) {
            $admins = $team->users->filter(fn ($user) => $user->membership->role === UserRole::ADMIN);
            throw_if(
                $admins->count() === 1 && $admins->contains($user),
                ValidationException::withMessages(['role' => 'Teams must have at least one admin.']),
            );
        }

        $user->leaveTeam($team);

        if ($request->user()->is($user)) {
            return to_route('app');
        }

        return to_route('teams.show', $team);
    }
}
