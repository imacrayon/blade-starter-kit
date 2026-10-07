@blaze
{{-- No trailing newline: it would render as whitespace after this inline element. --}}

@props(['datetime', 'format' => null])

@php
$formatting = match ($format) {
    'short' => [
        'day' => 'numeric',
        'month' => 'numeric',
        'year' => 'numeric',
        'hour' => 'numeric',
        'minute' => '2-digit',
    ],
    'date' => [
        'day' => 'numeric',
        'month' => 'numeric',
        'year' => 'numeric',
    ],
    'time' => [
        'hour' => 'numeric',
        'minute' => '2-digit',
        'timezone' => 'short',
    ],
    default => [
        'day' => 'numeric',
        'month' => 'short',
        'year' => 'numeric',
        'hour' => 'numeric',
        'minute' => '2-digit',
        'timezone' => 'short',
    ],
};
@endphp

<local-time datetime="{{ $datetime?->toW3cString() }}" {{ $attributes->merge($formatting) }}>{{ $datetime }}</local-time>