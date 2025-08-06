<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function store(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        $request->session()->put('impersonator_id', $request->user()->id);
        $request->session()->put('impersonating', $user->name);

        $request->session()->regenerate(destroy: true);

        Auth::login($user);

        return to_route('app');
    }

    public function destroy(Request $request)
    {
        abort_if($request->session()->missing('impersonator_id'), 404);

        $user = User::findOrFail($request->session()->pull('impersonator_id'));

        $request->session()->forget('impersonating');
        $request->session()->regenerate(destroy: true);

        Auth::login($user);

        return to_route('admin.users.index');
    }
}
