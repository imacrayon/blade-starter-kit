<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.users.index', [
            'users' => User::search($request->q)
                ->query(fn ($q) => $q->withCount('teams'))
                ->orderBy('name')
                ->paginate(),
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::enum(UserRole::class)],
        ]));

        return to_route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return to_route('admin.users.index');
    }
}
