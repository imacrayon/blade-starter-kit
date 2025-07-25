@props([
    'stashable' => null,
    'sticky' => null,
])

@php
if ($sticky) {
    $attributes = $attributes->class([
        'sticky top-0 max-h-dvh overflow-y-auto overscroll-contain lg:sticky!',
    ]);
}

if ($stashable) {
    $attributes = $attributes->class([
        'max-lg:data-mobile-cloak:hidden [[data-show-stashed-sidebar]_&]:data-mobile-cloak:block',
        'z-20 left-0 fixed! top-0 min-h-dvh max-h-dvh',
        'transition-transform -translate-x-full',
        '[[data-show-stashed-sidebar]_&]:translate-x-0! lg:translate-x-0!',
    ]);
}
@endphp

<?php if ($stashable) : ?>
<div
    class="z-10 fixed inset-0 bg-black/10 hidden [[data-show-stashed-sidebar]_&]:block lg:[[data-show-stashed-sidebar]_&]:hidden"
    x-init="" x-on:click="document.body.removeAttribute('data-show-stashed-sidebar')"
></div>
<?php endif; ?>

<div {{ $attributes->class([
    '[grid-area:sidebar]',
    'z-1 flex flex-col gap-4 [:where(&)]:w-64 p-4',
]) }}>
    {{ $slot }}
</div>
