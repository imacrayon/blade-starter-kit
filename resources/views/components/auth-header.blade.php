@props([
    'title',
    'description',
])

<div {{ $attributes->class('text-center') }}">
    <x-heading size="xl">{{ $title }}</x-heading>
    <x-subheading>{{ $description }}</x-subheading>
</div>
