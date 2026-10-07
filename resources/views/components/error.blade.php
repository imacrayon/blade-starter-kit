@blaze

@props([
    'for',
    'value' => null,
    'bag' => 'default',
])

@php
$field = \App\Field::for($for, bag: $bag);
$message = $field->error();
@endphp

@if ($message !== null)
    <div {{ $attributes->class([
        'text-sm font-medium text-red-600 dark:text-red-400'
    ])->merge([
        'id' => $field->errorId(),
    ]) }}>
        @if ($slot->isEmpty())
            {{ $value ?? $message }}
        @else
            {{ $slot }}
        @endif
    </div>
@endif
