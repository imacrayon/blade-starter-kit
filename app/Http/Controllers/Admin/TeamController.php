<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.teams.index', [
            'teams' => Team::search($request->q)
                ->query(fn ($q) => $q->withCount('users'))
                ->orderBy('name')
                ->paginate(),
        ]);
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return to_route('admin.teams.index');
    }
}
