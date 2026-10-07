<?php

namespace Tests\Feature;

use App\Field;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class PasswordRequirementsTest extends TestCase
{
    public function test_requirements_text_uses_the_default_minimum_length(): void
    {
        $this->assertSame('Use at least 8 characters.', Field::passwordRequirementsText());
    }

    public function test_requirements_text_reflects_the_configured_rules(): void
    {
        Password::defaults(fn () => Password::min(12)->mixedCase()->numbers()->symbols());

        $this->assertSame(
            'Use at least 12 characters, including upper and lowercase letters, a number and a symbol.',
            Field::passwordRequirementsText(),
        );
    }
}
