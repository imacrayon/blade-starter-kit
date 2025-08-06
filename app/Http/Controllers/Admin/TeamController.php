<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.teams.index', [
            'teams' => Team::search($request->q)
                ->query(fn ($q) => $q->withCount('users'))
                ->orderBy('name')
                ->paginate(),
        ]);
    }

    public function create()
    {
        return view('admin.teams.create', [
            'team' => new Team,
        ]);
    }

    public function store(Request $request)
    {
        $team = Team::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return to_route('teams.show', $team);
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return to_route('admin.teams.index');
    }
}
