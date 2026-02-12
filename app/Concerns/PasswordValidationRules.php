<?php

namespace App\Concerns;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }

    protected function currentPasswordRules(): array
    {
        return ['required', 'string', 'current_password'];
    }
}
