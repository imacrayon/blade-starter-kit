<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Features;

class TwoFactorAuthenticationController extends Controller
{
    public function edit(Request $request): View
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), 403);

        return view('settings.two-factor', [
            'user' => $request->user(),
        ]);
    }
}
