<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\UserRole;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function index(Team $team): View
    {
        return view('teams.invitations.index', [
            'team' => $team,
            'invitation' => $team->invitations()->make(),
            'invitations' => $team->invitations()->pending()->latest()->get(),
        ]);
    }

    public function store(Request $request, Team $team): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['bail', 'required', 'email', 'max:255', function (string $attribute, string $value, Closure $fail) use ($team) {
                if ($team->users()->whereRaw('lower(users.email) = ?', [Str::lower($value)])->exists()) {
                    $fail(__('This user is already a member of the team.'));
                }
            }],
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $team->invitations()
            ->firstOrNew(['email' => Str::lower($validated['email'])])
            ->fill(['role' => $validated['role']])
            ->send($request->user());

        return to_route('teams.invitations.index');
    }

    public function show(Request $request, Invitation $invitation): View|RedirectResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return view('teams.invitations.show', [
                'invitation' => $invitation,
            ]);
        }

        if ($user->belongsToTeam($invitation->team)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return to_route('teams.members.create', $invitation);
    }

    public function destroy(Team $team, Invitation $invitation): RedirectResponse
    {
        $invitation->delete();

        return back();
    }
}
