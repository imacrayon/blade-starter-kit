@blaze
{{-- No trailing newline: it would render as whitespace after this inline element. --}}

@props([
    'variant' => 'secondary',
])

<?php
$classes = [
    'inline font-medium underline',
    'hover:decoration-current',
    match ($variant) { // Background color...
        'primary' => 'text-blue-700 decoration-[color-mix(in_oklab,var(--color-blue-700),transparent_30%)]',
        default => 'text-gray-600 decoration-gray-300',
    },
];
?>

<a {{ $attributes->class($classes) }}>{{ $slot }}</a>