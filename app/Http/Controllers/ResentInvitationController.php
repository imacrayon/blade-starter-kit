<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResentInvitationController extends Controller
{
    public function __invoke(Request $request, Team $team, Invitation $invitation): RedirectResponse
    {
        $invitation->send($request->user());

        return back()->announce(__('Invitation resent'));
    }
}
