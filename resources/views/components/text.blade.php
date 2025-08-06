@props([
    'size' => null,
])

{{-- It's important that this file does NOT have a newline at the end. --}}
<div {{ $attributes->class([
    '[:where(&)]:text-gray-600 [:where(&)]:dark:text-white/70',
    match ($size) {
        'xl' => 'text-lg',
        'lg' => 'text-base',
        default => 'text-sm',
        'sm' => 'text-xs',
    }])
}}>{{ $slot }}</div>
