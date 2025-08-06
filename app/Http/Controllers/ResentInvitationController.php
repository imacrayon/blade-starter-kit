<?php

namespace App\Http\Controllers;

use App\Models\Invitation;

class ResentInvitationController extends Controller
{
    public function __invoke(Invitation $invitation)
    {
        $invitation->send();

        return back()->announce(__('Invitation resent'));
    }
}
