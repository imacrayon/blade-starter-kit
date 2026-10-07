<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\ComponentAttributeBag;

final class Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $id,
        public readonly string $bag = 'default',
    ) {}

    public static function for(string $name, ?string $id = null, string $bag = 'default'): self
    {
        return new self($name, $id ?? self::fieldId($name), $bag);
    }

    public static function fieldId(string $name): string
    {
        return trim(str_replace(['[', ']'], ['_', ''], $name), '_');
    }

    public function validationKey(): string
    {
        return trim(str_replace(['[', ']'], ['.', ''], $this->name), '.');
    }

    public function old(mixed $value = ''): mixed
    {
        return old($this->validationKey(), $value);
    }

    public function invalid(): bool
    {
        return self::errorBag($this->bag)->has($this->validationKey());
    }

    public function error(): ?string
    {
        return self::errorBag($this->bag)->first($this->validationKey()) ?: null;
    }

    public function errorId(): string
    {
        return self::fieldId($this->name).'_error';
    }

    public function descriptionId(): string
    {
        return self::fieldId($this->id).'_description';
    }

    public function attributes(ComponentAttributeBag $attributes, string $description = ''): ComponentAttributeBag
    {
        $invalid = $this->invalid();

        $describedBy = implode(' ', array_unique(array_filter([
            ...$this->describedByIds($invalid, $description !== ''),
            $attributes->get('aria-describedby'),
        ])));

        return $attributes->except('aria-describedby')->merge(array_filter([
            'name' => $this->name,
            'id' => $this->id,
            'aria-invalid' => $invalid ? 'true' : null,
            'aria-describedby' => $describedBy ?: null,
        ]));
    }

    /** @return list<string> */
    protected function describedByIds(bool $invalid, bool $description): array
    {
        return array_values(array_filter([
            $invalid ? $this->errorId() : null,
            $description ? $this->descriptionId() : null,
        ]));
    }

    protected static function errorBag(string $bag = 'default'): MessageBag
    {
        return View::shared('errors', fn () => Session::get('errors', new ViewErrorBag))->getBag($bag);
    }

    public static function passwordRules(): string
    {
        return Password::default()->toPasswordRulesString();
    }

    public static function passwordRequirementsText(): string
    {
        $rules = Password::default()->appliedRules();

        $requirements = array_values(array_filter([
            match (true) {
                $rules['mixedCase'] => __('upper and lowercase letters'),
                $rules['letters'] => __('letters'),
                default => null,
            },
            $rules['numbers'] ? __('a number') : null,
            $rules['symbols'] ? __('a symbol') : null,
        ]));

        if ($requirements === []) {
            return __('Use at least :min characters.', ['min' => $rules['min']]);
        }

        return __('Use at least :min characters, including :requirements.', [
            'min' => $rules['min'],
            'requirements' => Arr::join($requirements, ', ', __(' and ')),
        ]);
    }
}
