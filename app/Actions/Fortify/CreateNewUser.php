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

    /** @param  array<string, string>  $input */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $invitation = Invitation::findPendingByCode($input['code'] ?? null);

        if (! $invitation && filled($input['code'] ?? null)) {
            session()->put('invitation_invalid', true);
        }

        return DB::transaction(function () use ($input, $invitation) {
            $user = User::create([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
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
