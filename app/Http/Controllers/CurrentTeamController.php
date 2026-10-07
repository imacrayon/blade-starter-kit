<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrentTeamController extends Controller
{
    public function __invoke(Request $request, Team $team): RedirectResponse
    {
        $request->user()->switchTeam($team);

        return to_route('teams.show', $team);
    }
}
