<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'code' => ['nullable', 'string', 'exists:invitations,code'],
        ])->validate();

        $invitation = isset($input['code']) ? Invitation::where('code', $input['code'])->first() : null;

        return DB::transaction(function () use ($input, $invitation) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'role' => UserRole::MEMBER,
                'password' => Hash::make($input['password']),
            ]);

            if (is_null($invitation?->accept($user))) {
                Team::forUser($user);
            }

            return $user;
        });
    }
}
