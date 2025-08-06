@props([
    'scrollable' => false,
])

<nav {{ $attributes->class([
    'flex items-center gap-7 py-3',
    'overflow-x-auto overflow-y-hidden' => $scrollable
]) }}>
    {{ $slot }}
</nav>
