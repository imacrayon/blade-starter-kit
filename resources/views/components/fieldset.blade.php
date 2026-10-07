@blaze

@props([
    'for' => null,
    'bag' => 'default',
])

@php
$field = $for ? \App\Field::for($for, bag: $bag) : null;
$invalid = (bool) $field?->invalid();
@endphp

<fieldset
    @if($invalid) aria-invalid="true" @endif
    {{ $attributes->class('space-y-1')->merge(array_filter([
        'aria-describedby' => $invalid ? $field->errorId() : null,
    ])) }}>
    {{ $slot }}
</fieldset>
