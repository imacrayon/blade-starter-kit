<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request, Team $team): View
    {
        return view('teams.members.index', [
            'team' => $team,
            'users' => User::search($request->q)
                ->query(function ($query) use ($team) {
                    $query->join('memberships', 'users.id', '=', 'memberships.user_id')
                        ->where('memberships.team_id', $team->id)
                        ->select('users.*', 'memberships.role as membership_role')
                        ->orderBy('membership_role')
                        ->orderBy('first_name')
                        ->orderBy('last_name');
                })
                ->paginate(),
        ]);
    }

    public function create(Invitation $invitation): View
    {
        return view('teams.members.create', [
            'invitation' => $invitation,
        ]);
    }

    public function store(Request $request, Invitation $invitation): RedirectResponse
    {
        $invitation->accept($request->user());

        return redirect()->intended(route('app'));
    }

    public function edit(Team $team, string $user): View
    {
        return view('teams.members.edit', [
            'team' => $team,
            'user' => $team->users()->findOrFail($user),
        ]);
    }

    public function update(Request $request, Team $team, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        throw_if(
            $request->enum('role', UserRole::class) !== UserRole::ADMIN && $team->isSoleAdmin($user),
            ValidationException::withMessages(['role' => __('Teams must have at least one admin.')])
        );

        $team->users()->updateExistingPivot($user, [
            'role' => $request->role,
        ]);

        return to_route('teams.members.index');
    }

    public function destroy(Request $request, Team $team, User $user): RedirectResponse
    {
        throw_if(
            $team->needsAnotherAdmin($user),
            ValidationException::withMessages(['role' => __('Teams must have at least one admin.')]),
        );

        $leaving = $request->user()->is($user);

        if ($leaving) {
            Gate::authorize('leave', $team);
        }

        $user->leaveTeam($team);

        if ($leaving) {
            return to_route('app');
        }

        if (! $team->exists) {
            return to_route('admin.teams.index');
        }

        return to_route('teams.members.index');
    }
}
