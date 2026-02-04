@props([
    'href' => '',
    'variant' => 'secondary',
    'size' => 'base',
    'before' => '',
    'after' => '',
])

@php
$classes = [
    'inline-flex items-center justify-center gap-2',
    'relative aria-pressed:z-10', // Button group behavior
    'font-medium whitespace-nowrap',
    'disabled:opacity-75 dark:disabled:opacity-75 disabled:cursor-default disabled:pointer-events-none',
    match ($size) { // Size...
        'base' => 'h-10 text-sm rounded-lg [:where(&)]:px-4',
        'sm' => 'h-8 text-sm rounded-md [:where(&)]:px-3',
        'xs' => 'h-6 text-xs rounded-md [:where(&)]:px-2',
    },
    match ($variant) { // Background color...
        'primary' => 'bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]',
        'secondary' => 'bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600/75 aria-pressed:bg-[var(--color-accent)] aria-pressed:hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)]',
        'danger' => 'bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-500',
        default => '',
    },
    match ($variant) { // Text color...
        'primary' => 'text-[var(--color-accent-foreground)]',
        'secondary' => 'text-gray-800 dark:text-white aria-pressed:text-[var(--color-accent-foreground)]',
        'danger' => 'text-white',
        'link' => '!h-auto p-0 underline text-(--color-accent-content) decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_30%)] hover:decoration-current',
        default => '',
    },
    match ($variant) { // Border color...
        'primary' => 'border border-black/10 dark:border-0',
        'secondary' => 'border border-gray-200 hover:border-gray-200 border-b-gray-300/80 dark:border-gray-600 dark:hover:border-gray-600 aria-pressed:border-black/10 dark:aria-pressed:border-0',
         default => '',
    },
    match ($variant) { // Shadows...
        'primary' => 'shadow-[inset_0px_1px_--theme(--color-white/.2)]',
        'secondary' => match ($size) {
            'base' => 'shadow-xs',
            'sm' => 'shadow-xs',
            'xs' => 'shadow-none',
        },
        'danger' => 'shadow-[inset_0px_1px_var(--color-red-500),inset_0px_2px_--theme(--color-white/.15)] dark:shadow-none',
        default => '',
    },
]
@endphp

{{-- It's important that this file does NOT have a newline at the end. --}}
<?php if ($href): ?>
  <a href="{{ $href }}" {{ $attributes->class($classes) }}>
    <?php if (is_string($before) && $before !== ''): ?>
        <x-dynamic-component :component="$before" aria-hidden="true" width="20" height="20" class="shrink-0 opacity-40 group-hover:opacity-60 -ml-0.5" />
    <?php else: ?>
        {{ $before }}
    <?php endif; ?>
    {{ $slot }}
    <?php if (is_string($after) && $after !== ''): ?>
        <x-dynamic-component :component="$after" aria-hidden="true" width="20" height="20" class="shrink-0 opacity-40 group-hover:opacity-60 -mr-0.5" />
    <?php else: ?>
        {{ $after }}
    <?php endif; ?>
  </a>
<?php else: ?>
  <button {{ $attributes->class($classes) }}>
    <?php if (is_string($before) && $before !== ''): ?>
        <x-dynamic-component :component="$before" aria-hidden="true" width="20" height="20" class="shrink-0 opacity-40 group-hover:opacity-60 -ml-0.5" />
    <?php else: ?>
        {{ $before }}
    <?php endif; ?>
    {{ $slot }}
    <?php if (is_string($after) && $after !== ''): ?>
        <x-dynamic-component :component="$after" aria-hidden="true" width="20" height="20" class="shrink-0 opacity-40 group-hover:opacity-60 -mr-0.5" />
    <?php else: ?>
        {{ $after }}
    <?php endif; ?>
  </button>
<?php endif; ?>