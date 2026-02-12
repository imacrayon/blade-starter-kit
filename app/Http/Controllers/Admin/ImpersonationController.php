<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->input('user_id'));

        abort_if($user->role === UserRole::ADMIN, 403, 'Cannot impersonate an admin user.');

        Log::info('Impersonation started', [
            'admin_id' => $request->user()->id,
            'target_user_id' => $user->id,
        ]);

        $request->session()->put('impersonator_id', $request->user()->id);
        $request->session()->put('impersonating', $user->name);

        $request->session()->regenerate(destroy: true);

        Auth::login($user);

        return to_route('app');
    }

    public function destroy(Request $request): RedirectResponse
    {
        abort_if($request->session()->missing('impersonator_id'), 404);

        $impersonator = User::findOrFail($request->session()->pull('impersonator_id'));

        Log::info('Impersonation ended', [
            'admin_id' => $impersonator->id,
            'impersonated_user_id' => $request->user()->id,
        ]);

        $request->session()->forget('impersonating');
        $request->session()->regenerate(destroy: true);

        Auth::login($impersonator);

        return to_route('admin.users.index');
    }
}
