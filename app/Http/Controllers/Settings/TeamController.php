<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings.teams', [
            'user' => $request->user(),
            'teams' => $request->user()->load(['teams' => fn ($query) => $query->withCount(Team::memberCounts())])->teams,
        ]);
    }

    public function destroy(Request $request, Team $team): RedirectResponse
    {
        $request->user()->leaveTeam($team);

        return to_route('settings.teams.index');
    }
}
