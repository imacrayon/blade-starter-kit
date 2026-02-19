@props([
    'id',
    'justify' => 'right',
    'align' => 'top',
])

<div id="{{ $id }}" popover="auto" data-popover data-align="{{ $align }}" data-justify="{{ $justify }}" {{ $attributes->class([
    '[:where(&)]:min-w-48 [:where(&)]:p-[.3125rem]',
    'rounded-lg shadow-xs',
    'border border-gray-200 dark:border-gray-600',
    'bg-white dark:bg-gray-700',
    'text-gray-700 dark:text-gray-300',
    'focus:outline-hidden',
]) }}>
    {{ $slot }}
</div>
