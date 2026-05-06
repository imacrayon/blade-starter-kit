@props([
    'id',
    'justify' => 'right',
    'align' => 'top',
])

<div id="{{ $id }}" popover="auto" data-popover data-align="{{ $align }}" data-justify="{{ $justify }}" {{ $attributes->class([
    '[:where(&)]:min-w-48 [:where(&)]:p-[.3125rem]',
    'rounded-lg shadow-lg dark:inset-shadow-xs dark:inset-shadow-white/5',
    'outline-1 outline-black/8 dark:outline-black',
    'bg-white dark:bg-gray-800',
    'text-gray-700 dark:text-gray-300',
]) }}>
    {{ $slot }}
</div>
