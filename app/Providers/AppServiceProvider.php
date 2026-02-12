<?php

namespace App\Providers;

use App\Models\User;
use App\UserRole;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict();

        Date::use(CarbonImmutable::class);

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        RedirectResponse::macro('announce', function ($text, $type = 'ghost') {
            $this->session->push('announcements', [
                'id' => uniqid(),
                'type' => $type,
                'content' => $text,
            ]);

            return $this;
        });

        Gate::define('admin', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });
    }
}
