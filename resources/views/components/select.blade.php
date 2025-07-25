@props([
    'size' => 'base',
])

@php
$classes = [
    'appearance-none block w-full',
    'ps-3 pe-10',
    'bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]',
    'text-gray-700 disabled:text-gray-500 placeholder-gray-400 disabled:placeholder-gray-400/70 dark:text-gray-300 dark:disabled:text-gray-400 dark:placeholder-gray-400 dark:disabled:placeholder-gray-500',
    'rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-white/10 dark:disabled:border-white/5',
    'shadow-xs disabled:shadow-none dark:shadow-none',
    'aria-invalid:border-red-500',
    match ($size) {
        'base' => 'text-base sm:text-sm py-2 h-10 leading-[1.375rem]',
        'sm' => 'text-sm py-1.5 h-8 leading-[1.125rem]',
        'xs' => 'text-xs py-1.5 h-6 leading-[1.125rem]',
    },
];
@endphp

<x-has-field>
    <?php if (!$multiple): ?>
        <select {{ $controlAttributes }} {{ $attributes->class($classes) }}>
            <?php if ($placeholder): ?>
            <option value disabled {{ $isSelected('') ? 'selected' : '' }}>{{ $placeholder }}</option>
            <?php endif; ?>
            @if(! empty($options))
            @foreach($options as $v => $l)
                <option {{ $isSelected($v) ? 'selected' : '' }} value="{{ $v }}">{{ $l }}</option>
            @endforeach
            @else
            {{ $slot }}
            @endif
        </select>
    <?php else: ?>
        <div {{ $attributes->class(['overflow-x-hidden divide-y divide-gray-200 overflow-y-scroll !h-33 !p-0'] + $classes) }}>
            <?php if (! empty($options)): ?>
            @foreach($options as $v => $l)
                <x-label for="{{ $id }}_{{ $loop->iteration }}" class="flex items-center space-x-2 w-full py-2 px-3 bg-white hover:bg-gray-50">
                <x-checkbox name="{{ $id }}" id="{{ $id }}_{{ $loop->iteration }}" value="{{ $v }}" :checked="$isSelected($v)" />
                <span>{{ $l }}</span>
                </x-label>
            @endforeach
            <?php else: ?>
            {{ $slot }}
            <?php endif; ?>
        </div>
    <?php endif; ?>
</x-has-field>
