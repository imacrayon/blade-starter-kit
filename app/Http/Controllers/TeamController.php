<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function create(): View
    {
        return view('teams.create', [
            'team' => new Team,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $team = Team::forUser($request->user(), $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return to_route('teams.show', $team);
    }

    public function show(Team $team): View
    {
        return view('teams.show', ['team' => $team]);
    }

    public function edit(Team $team): View
    {
        return view('teams.edit', ['team' => $team->loadCount(Team::memberCounts())]);
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $team->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return to_route('teams.members.index', $team);
    }

    public function destroy(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'team_name' => ['required', Rule::in([$team->name])],
        ], [
            'team_name.in' => __('The team name does not match.'),
        ]);

        $team->delete();

        return to_route('app');
    }
}
