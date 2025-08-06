<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function create()
    {
        return view('teams.create', [
            'team' => new Team,
        ]);
    }

    public function store(Request $request)
    {
        $team = Team::forUser($request->user(), $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return to_route('teams.show', $team);
    }

    public function show(Request $request, Team $team)
    {
        return view('teams.show', [
            'team' => $team,
            'users' => User::search($request->q)
                ->query(function ($query) use ($team) {
                    $query->join('memberships', 'users.id', '=', 'memberships.user_id')
                        ->where('memberships.team_id', $team->id)
                        ->select('users.*', 'memberships.role as membership_role');
                })
                ->orderBy('membership_role')
                ->orderBy('name')
                ->paginate(),
        ]);
    }

    public function edit(Team $team): View
    {
        return view('teams.edit', ['team' => $team]);
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $team->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return to_route('teams.show', $team);
    }

    public function destroy(Team $team)
    {
        DB::transaction(function () use ($team) {
            $team->users()->where('team_id', $team->id)->update(['team_id' => null]);
            $team->users()->detach();
            $team->delete();
        });

        return to_route('admin.teams.index');
    }
}
