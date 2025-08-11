<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $team = Team::findOrFail($request->team_id);
        abort_unless($request->user()->belongsToTeam($team), '403');

        $request->user()->update(['team_id' => $team->id]);

        return to_route('teams.show', $team);
    }
}
