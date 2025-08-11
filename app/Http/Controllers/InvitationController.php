<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function index(Team $team)
    {
        return view('teams.invitations.index', [
            'team' => $team,
            'invitation' => $team->invitations()->make(),
            'invitations' => $team->invitations()->latest()->get(),
        ]);
    }

    public function store(Request $request, Team $team)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $invitation = $team->invitations()->firstOrNew([
            'email' => $request->email,
        ], [
            'role' => $request->role,
        ]);
        $invitation->sender()->associate($request->user());
        $invitation->save();
        $invitation->send();

        return to_route('teams.invitations.index', $team);
    }

    public function show(Request $request, Invitation $invitation)
    {
        $user = $request->user();

        if (is_null($user)) {
            return view('teams.invitations.show', [
                'invitation' => $invitation,
            ]);
        }

        if ($user->belongsToTeam($invitation->team)) {
            Auth::logout();
        }

        return to_route('teams.memberships.create', $invitation);
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return back();
    }
}
