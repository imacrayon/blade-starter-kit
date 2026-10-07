<?php

namespace App\Http\Controllers\Settings;

use App\Concerns\PasswordValidationRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Fortify\Fortify;

class SecurityController extends Controller
{
    use PasswordValidationRules;

    public function edit(Request $request): View
    {
        $confirmingTwoFactor = $request->session()->get('status') === Fortify::TWO_FACTOR_AUTHENTICATION_ENABLED
            || $request->session()->get('errors')?->hasBag('confirmTwoFactorAuthentication');

        return view('settings.security', [
            'user' => $request->user(),
            'confirmingTwoFactor' => $confirmingTwoFactor,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
