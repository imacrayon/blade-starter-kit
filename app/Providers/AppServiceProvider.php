<?php

namespace App\Providers;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();

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
