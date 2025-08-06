<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'team_id' => ['required', Rule::in($request->user()->teams->pluck('id'))],
        ]);

        $request->user()->update(['team_id' => $request->team_id]);

        return to_route('teams.show', $request->team_id);
    }
}
