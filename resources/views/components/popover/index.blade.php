@props([
    'justify' => 'right',
    'align' => 'top',
])

@php
$x = match ($justify) {
    'left' => 'left-0 origin-left rtl:origin-right',
    'right' => 'right-0 origin-right rtl:origin-left',
};
$y = match ($align) {
    'top' => 'mt-1.5 top-full',
    'bottom' => 'mb-1.5 bottom-full',
};
@endphp

<div x-data="popover" {{ $attributes->class(['relative']) }}>
    {{ $slot }}
    <div x-cloak {{ $menu->attributes->class([
        $x, $y,
        'absolute z-50',
        '[:where(&)]:min-w-48 px-[.3125rem]',
        'rounded-lg shadow-xs',
        'border border-gray-200 dark:border-gray-600',
        'bg-white dark:bg-gray-700',
        'focus:outline-hidden',
    ]) }}>
        {{ $menu }}
    </div>
</div>
