@blaze(fold: true)

@props([
    'size' => null,
])

<div {{ $attributes->class([
    match ($size) {
        'xl' => 'text-lg',
        'lg' => 'text-base',
        default => 'text-sm',
        'sm' => 'text-xs',
    },
    '[:where(&)]:text-gray-500 dark:[:where(&)]:text-gray-400',
]) }} data-subheading>
    {{ $slot }}
</div>
