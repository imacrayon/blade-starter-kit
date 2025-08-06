@props([
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
])

<?php if ($expandable && $heading): ?>

<details {{ $attributes->class('group/disclosure grid') }} {{ $expanded ? 'open' : '' }}>
    <summary class="mb-[2px] flex h-10 w-full items-center rounded-lg text-gray-500 cursor-default hover:bg-gray-800/5 hover:text-gray-800 lg:h-8 dark:text-white/80 dark:hover:bg-white/7 dark:hover:text-white">
        <div class="pl-3 pr-4">
            <x-phosphor-caret-down aria-hidden="true" class="hidden size-3! group-open/disclosure:block" />
            <x-phosphor-caret-right aria-hidden="true" class="block size-3! group-open/disclosure:hidden" />
        </div>
        <span class="text-sm font-medium leading-none">{{ $heading }}</span>
    </summary>
    <div class="relative space-y-[2px] pl-7">
        <div class="absolute inset-y-[3px] left-0 ml-4 w-px bg-gray-200 dark:bg-white/30"></div>
        {{ $slot }}
    </div>
</details>

<?php elseif ($heading): ?>

<div {{ $attributes->class('grid space-y-[2px]') }}>
    <div class="px-1 py-2 text-xs leading-none text-gray-500">{{ $heading }}</div>
    <div>
        {{ $slot }}
    </div>
</div>

<?php else: ?>

<div {{ $attributes->class('block space-y-[2px]') }}>
    {{ $slot }}
</div>

<?php endif; ?>
