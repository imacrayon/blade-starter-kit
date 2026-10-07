@blaze(fold: true, unsafe: ['attributes', 'before', 'after'])
{{-- No trailing newline: it would render as whitespace after this inline element. --}}

@props([
    'variant' => 'secondary',
    'size' => 'base',
    'icon' => false,
    'before' => '',
    'after' => '',
    'as' => 'button'
])

@php
if ($attributes->has('href')) {
    $as = 'a';
}

$classes = [
    'inline-flex items-center justify-center outline-offset-1',
    'relative aria-pressed:z-10', // Button group behavior
    'font-medium whitespace-nowrap',
    'disabled:opacity-75 dark:disabled:opacity-75 disabled:cursor-default disabled:pointer-events-none',
    match ($size) { // Size...
        'lg' => 'h-12 text-base rounded-lg [:where(&)]:px-5 gap-2',
        'base' => 'h-10 text-sm rounded-lg [:where(&)]:px-4 gap-2',
        'sm' => 'h-8 text-sm rounded-md [:where(&)]:px-3 gap-1.5',
        'xs' => 'h-6 text-xs rounded-md [:where(&)]:px-2 gap-1',
    },
    $icon ? 'p-0 aspect-square' : '',
    match ($variant) { // Background color...
        'primary' => 'bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]',
        'secondary' => 'bg-white hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 aria-pressed:bg-[var(--color-accent)] aria-pressed:hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]',
        'danger' => 'bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-500',
        default => '',
    },
    match ($variant) { // Text color...
        'primary' => 'text-[var(--color-accent-foreground)]',
        'secondary' => 'text-gray-800 dark:text-gray-300 aria-pressed:text-[var(--color-accent-foreground)]',
        'danger' => 'text-white',
        'link' => '!h-auto p-0 underline text-(--color-accent-content) decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_30%)] hover:decoration-current',
        default => '',
    },
    match ($variant) { // Border color...
        'primary' => 'border border-black/10 dark:border-0',
        'secondary' => 'border border-gray-200 hover:border-gray-200 border-b-gray-300/80 dark:border-gray-800 dark:hover:border-white/15 aria-pressed:border-black/10 dark:aria-pressed:border-0',
        'danger' => 'border border-black/10 dark:border-0',
         default => '',
    },
    match ($variant) { // Shadows...
        'primary' => 'shadow-[inset_0px_1px_--theme(--color-white/.4)]',
        'secondary' => match ($size) {
            'lg' => 'shadow-xs',
            'base' => 'shadow-xs',
            'sm' => 'shadow-xs',
            'xs' => 'shadow-none',
        },
        'danger' => 'shadow-[inset_0px_1px_--theme(--color-white/.4)]',
        default => '',
    },
];

$beforeClasses = $icon
    ? 'shrink-0'
    : 'shrink-0 opacity-80 group-hover:opacity-90 -ml-0.5';

$afterClasses = $icon
    ? 'shrink-0'
    : 'shrink-0 opacity-80 group-hover:opacity-90 -mr-0.5';

$iconSize = match($size) {
    'lg' => '20',
    'base' => '20',
    'sm' => '20',
    'xs' => '16',
};
@endphp

<{{ $as }} {{ $attributes->class($classes) }}>
    <?php if (is_string($before) && $before !== ''): ?>
        <x-dynamic-component :component="$before" aria-hidden="true" :width="$iconSize" :height="$iconSize" :class="$beforeClasses" />
    <?php else: ?>
        {{ $before }}
    <?php endif; ?>
    {{ $slot }}
    <?php if (is_string($after) && $after !== ''): ?>
        <x-dynamic-component :component="$after" aria-hidden="true" :width="$iconSize" :height="$iconSize" :class="$afterClasses" />
    <?php else: ?>
        {{ $after }}
    <?php endif; ?>
</{{ $as }}>