@blaze(fold: true, safe: ['id', 'justify', 'align'])

@props([
    'id',
    'justify' => 'right',
    'align' => 'top',
])

<div id="{{ $id }}" popover="auto" data-popover data-align="{{ $align }}" data-justify="{{ $justify }}" {{ $attributes->class([
    '[:where(&)]:min-w-48 [:where(&)]:p-[.3125rem]',
    'rounded-lg shadow-lg',
    'outline-1 outline-black/8 dark:outline-gray-800',
    'bg-white dark:bg-gray-900',
    'text-gray-700 dark:text-gray-300',
]) }}>
    {{ $slot }}
</div>
