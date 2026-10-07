<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /** @return array<int, Password|ValidationRule|array<mixed>|string> */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }

    /** @return array<int, Password|ValidationRule|array<mixed>|string> */
    protected function currentPasswordRules(): array
    {
        return ['required', 'string', 'current_password'];
    }
}
