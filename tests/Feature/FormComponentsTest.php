<?php

namespace Tests\Feature;

use App\Field;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class FormComponentsTest extends TestCase
{
    /**
     * Render a Blade string with an optional error bag and flashed old input.
     *
     * @param  array<string, string>  $errors
     * @param  array<string, mixed>  $old
     */
    private function render(string $template, array $errors = [], array $old = [], string $bag = 'default'): string
    {
        $errorBag = new ViewErrorBag;

        if ($errors !== []) {
            $errorBag->put($bag, new MessageBag($errors));
        }

        View::share('errors', $errorBag);

        $this->app['request']->setLaravelSession($this->app['session.store']);

        if ($old !== []) {
            session()->put('_old_input', $old);
        }

        return Blade::render($template);
    }

    public function test_input_renders_name_id_and_default_type(): void
    {
        $html = $this->render('<x-input name="email" />');

        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('id="email"', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringNotContainsString('?>', $html);
    }

    public function test_input_uses_a_passed_type(): void
    {
        $html = $this->render('<x-input name="email" type="email" />');

        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringNotContainsString('type="text"', $html);
    }

    public function test_password_input_renders_a_visibility_toggle(): void
    {
        $html = $this->render('<x-input name="password" type="password" />');

        $this->assertStringContainsString('type="password"', $html);
        $this->assertStringContainsString("x-bind:type=\"visible ? 'text' : 'password'\"", $html);
        $this->assertStringContainsString('x-bind:aria-pressed="visible"', $html);
        $this->assertStringContainsString('Show password', $html);
        $this->assertStringNotContainsString('Show password', $this->render('<x-input name="email" type="email" />'));
    }

    public function test_input_marks_an_invalid_field(): void
    {
        $html = $this->render('<x-input name="email" label="Email" />', ['email' => 'Email is required.']);

        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('aria-describedby="email_error"', $html);
        $this->assertStringContainsString('Email is required.', $html);
    }

    public function test_labelled_input_renders_an_error_from_its_bag(): void
    {
        $html = $this->render('<x-input name="code" label="Code" bag="confirm" />', ['code' => 'Invalid code.'], bag: 'confirm');

        $this->assertStringContainsString('aria-describedby="code_error"', $html);
        $this->assertStringContainsString('id="code_error"', $html);
        $this->assertStringContainsString('Invalid code.', $html);
    }

    public function test_input_links_its_description(): void
    {
        $html = $this->render('<x-input name="email" label="Email" description="We never share it." />');

        $this->assertStringContainsString('aria-describedby="email_description"', $html);
        $this->assertStringContainsString('id="email_description"', $html);
        $this->assertStringContainsString('We never share it.', $html);
        $this->assertStringNotContainsString('aria-invalid="true"', $html);
    }

    public function test_input_links_both_error_and_description(): void
    {
        $html = $this->render(
            '<x-input name="email" label="Email" description="Hint" />',
            ['email' => 'Bad'],
        );

        $this->assertStringContainsString('aria-describedby="email_error email_description"', $html);
    }

    public function test_input_prefers_old_input_over_the_value(): void
    {
        $html = $this->render(
            '<x-input name="email" value="default@example.com" />',
            old: ['email' => 'flashed@example.com'],
        );

        $this->assertStringContainsString('value="flashed@example.com"', $html);
        $this->assertStringNotContainsString('default@example.com', $html);
    }

    public function test_input_derives_an_html_safe_id_from_a_bracketed_name(): void
    {
        $html = $this->render('<x-input name="docs[]" label="Documents" />', ['docs' => 'Required']);

        $this->assertStringContainsString('name="docs[]"', $html);
        $this->assertStringContainsString('id="docs"', $html);
        $this->assertStringContainsString('aria-describedby="docs_error"', $html);
        $this->assertStringContainsString('id="docs_error"', $html);
        $this->assertStringNotContainsString('id="docs[]"', $html);
        $this->assertStringNotContainsString('docs[]_error', $html);
    }

    public function test_textarea_renders_the_value_as_content(): void
    {
        $html = $this->render('<x-textarea name="bio" value="Hello" />');

        $this->assertStringContainsString('name="bio"', $html);
        $this->assertStringContainsString('>Hello</textarea>', $html);
    }

    public function test_textarea_preserves_a_falsy_old_value(): void
    {
        // A literal "0" must survive instead of falling through to the slot.
        $html = $this->render('<x-textarea name="qty">fallback</x-textarea>', old: ['qty' => '0']);

        $this->assertStringContainsString('>0</textarea>', $html);
        $this->assertStringNotContainsString('fallback', $html);
    }

    public function test_checkbox_reflects_the_checked_state(): void
    {
        $checked = $this->render('<x-checkbox name="agree" :checked="true" />');
        $unchecked = $this->render('<x-checkbox name="agree" :checked="false" />');

        $this->assertStringContainsString('value="1" checked', $checked);
        $this->assertStringNotContainsString('value="1" checked', $unchecked);
    }

    public function test_radio_renders_core_attributes(): void
    {
        $html = $this->render('<x-radio name="role" id="role_0" value="manager" />');

        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('name="role"', $html);
        $this->assertStringContainsString('id="role_0"', $html);
        $this->assertStringContainsString('value="manager"', $html);
    }

    public function test_grouped_radio_is_marked_invalid_via_its_field_name(): void
    {
        // The element id (role_0) differs from the field name (role); aria-invalid
        // must still fire because the shared field is invalid.
        $html = $this->render(
            '<x-radio name="role" id="role_0" value="manager" />',
            ['role' => 'Required'],
        );

        $this->assertStringContainsString('aria-invalid="true"', $html);
    }

    public function test_select_renders_options_and_marks_the_selected_one(): void
    {
        $html = $this->render('<x-select name="fruit" value="b" :options="[\'a\' => \'Apple\', \'b\' => \'Banana\']" />');

        $this->assertStringContainsString('selected value="b">Banana', $html);
        $this->assertStringContainsString('value="a">Apple', $html);
    }

    public function test_select_renders_a_placeholder(): void
    {
        $html = $this->render('<x-select name="fruit" placeholder="Choose..." :options="[\'a\' => \'Apple\']" />');

        $this->assertStringContainsString('Choose...', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_select_expands_an_enum_class_into_options(): void
    {
        $html = $this->render('<x-select name="role" :options="\App\UserRole::class" />');

        $this->assertStringContainsString('value="admin">Admin', $html);
        $this->assertStringContainsString('value="member">Member', $html);
    }

    public function test_multiple_select_renders_checkboxes_with_selection(): void
    {
        $html = $this->render('<x-select name="fruit[]" multiple :options="[\'a\' => \'Apple\', \'b\' => \'Banana\']" :value="[\'b\']" />');

        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('name="fruit[]"', $html);
        $this->assertStringContainsString('value="b" checked', $html);
        $this->assertStringNotContainsString('value="a" checked', $html);
    }

    public function test_multiple_select_groups_off_the_name_without_an_explicit_id(): void
    {
        $html = $this->render(
            '<x-select name="specialties[]" multiple :options="[\'1\' => \'CPR\', \'2\' => \'First Aid\']" />',
            ['specialties' => 'Required'],
        );

        $this->assertStringContainsString('id="specialties_1"', $html);
        $this->assertStringContainsString('id="specialties_2"', $html);
        $this->assertStringContainsString('aria-describedby="specialties_error"', $html);
        $this->assertStringNotContainsString('specialties[]_error', $html);
    }

    public function test_error_renders_the_message_keyed_on_the_field(): void
    {
        $html = $this->render('<x-error for="email" />', ['email' => 'Email is required.']);

        $this->assertStringContainsString('id="email_error"', $html);
        $this->assertStringContainsString('Email is required.', $html);
    }

    public function test_error_normalizes_a_bracketed_field_name(): void
    {
        $html = $this->render('<x-error for="docs[]" />', ['docs' => 'Required']);

        $this->assertStringContainsString('id="docs_error"', $html);
        $this->assertStringNotContainsString('docs[]_error', $html);
    }

    public function test_get_form_omits_the_csrf_token_and_method(): void
    {
        $html = $this->render('<x-form method="get" action="/search" />');

        $this->assertStringContainsString('method="get"', $html);
        $this->assertStringNotContainsString('_token', $html);
        $this->assertStringNotContainsString('_method', $html);
    }

    public function test_post_form_includes_the_csrf_token_only(): void
    {
        $html = $this->render('<x-form method="post" action="/teams" />');

        $this->assertStringContainsString('method="post"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringNotContainsString('_method', $html);
    }

    public function test_put_form_spoofs_its_method(): void
    {
        $html = $this->render('<x-form method="PUT" action="/teams/1" />');

        $this->assertStringContainsString('method="post"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringContainsString('name="_method" value="put"', $html);
    }

    public function test_fieldset_is_marked_invalid_via_its_field_name(): void
    {
        $html = $this->render('<x-fieldset for="role" />', ['role' => 'Required']);

        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('aria-describedby="role_error"', $html);
    }

    public function test_fieldset_without_a_field_is_not_marked(): void
    {
        $html = $this->render('<x-fieldset />', ['role' => 'Required']);

        $this->assertStringNotContainsString('aria-invalid', $html);
        $this->assertStringNotContainsString('aria-describedby', $html);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function inlineComponents(): array
    {
        return [
            'button' => ['<x-button>Save</x-button>.', '</button>.'],
            'link' => ['<x-link href="#">Log in</x-link>.', '</a>.'],
            'badge' => ['<x-badge>New</x-badge>.', '</span>.'],
            'time' => ['<x-time :datetime="now()" />.', '</local-time>.'],
        ];
    }

    #[DataProvider('inlineComponents')]
    public function test_inline_component_renders_no_trailing_whitespace(string $template, string $expected): void
    {
        $this->assertStringContainsString($expected, $this->render($template));
    }

    public function test_link_renders_an_anchor_element(): void
    {
        $this->assertStringContainsString('<a class="', $this->render('<x-link href="#">Log in</x-link>'));
    }

    public function test_validation_key_normalizes_array_names(): void
    {
        $this->assertSame('specialties', Field::for('specialties[]')->validationKey());
        $this->assertSame('a.b', Field::for('a[b]')->validationKey());
    }

    public function test_field_id_produces_html_safe_ids(): void
    {
        $this->assertSame('specialties', Field::fieldId('specialties[]'));
        $this->assertSame('a_b', Field::fieldId('a[b]'));
        $this->assertSame('aircraft_1_tail_number', Field::fieldId('aircraft[1][tail_number]'));
        $this->assertSame('role', Field::fieldId('role'));
    }

    public function test_field_for_resolves_the_element_id_from_the_name(): void
    {
        $field = Field::for('specialties[]');

        $this->assertSame('specialties[]', $field->name);
        $this->assertSame('specialties', $field->id);
        $this->assertSame('specialties', $field->validationKey());
        $this->assertSame('specialties_error', $field->errorId());
        $this->assertSame('specialties_description', $field->descriptionId());
    }

    public function test_field_for_honors_an_explicit_id_as_the_collision_escape_hatch(): void
    {
        // Two controls share name="location"; an explicit id keeps the element id
        // unique while the error stays keyed on the name so both point at one error.
        $field = Field::for('location', 'location_address');

        $this->assertSame('location_address', $field->id);
        $this->assertSame('location_error', $field->errorId());
        $this->assertSame('location_address_description', $field->descriptionId());
    }

    public function test_invalid_keys_off_the_field_name(): void
    {
        View::share('errors', tap(new ViewErrorBag, fn ($bag) => $bag->put('default', new MessageBag(['role' => 'x']))));

        $this->assertTrue(Field::for('role')->invalid());
        $this->assertFalse(Field::for('other')->invalid());
    }
}
