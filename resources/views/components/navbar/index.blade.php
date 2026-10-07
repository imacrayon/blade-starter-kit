@blaze

@props([
    'scrollable' => false,
])

<nav {{ $attributes->class([
    'flex items-center gap-7 py-2',
    '-mx-3 px-3 overflow-x-auto overflow-y-hidden scrollbar-hidden' => $scrollable
]) }}>
    {{ $slot }}
</nav>
