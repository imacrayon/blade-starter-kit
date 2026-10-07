@blaze

@props([
    'variant' => 'primary'
])

<nav {{ $attributes->class(['flex flex-col overflow-visible min-h-auto']) }}>
    {{ $slot }}
</nav>
