<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\Invitation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::loginView(function (Request $request) {
            // Return to the join page after logging in. Registering accepts the invitation instead.
            if ($invitation = Invitation::findPendingByCode($request->query('code'))) {
                redirect()->setIntendedUrl(route('teams.members.create', $invitation));
            }

            return view('auth.login', ['invitation' => $invitation]);
        });
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::registerView(function (Request $request) {
            if ($invitation = Invitation::findPendingByCode($request->query('code'))) {
                $request->session()->forget('url.intended');
            }

            return view('auth.register', ['invitation' => $invitation]);
        });
        Fortify::resetPasswordView(fn () => view('auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', fn (Request $request) => [
            Limit::perMinute(10)->by('session:'.$request->session()->getId()),
            Limit::perMinute(120)->by('ip:'.$request->ip()),
        ]);
    }
}
