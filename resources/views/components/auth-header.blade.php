@blaze

@props([
    'title',
    'description',
])

<div {{ $attributes->class('text-center') }}>
    <x-heading level="1" size="xl">{{ $title }}</x-heading>
    <x-subheading>{{ $description }}</x-subheading>
</div>
