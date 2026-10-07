@blaze

@props([
    'name' => null,
    'id' => null,
    'label' => '',
    'description' => '',
    'bag' => 'default',
])

<?php if ($label): ?>
<x-field>
    <x-label :for="$id" :value="$label" />
    <?php if ($description) : ?>
        <x-description :for="$id" :value="$description" />
    <?php endif; ?>
    <x-error :for="$name" :bag="$bag" />
    {{ $slot }}
</x-field>
<?php else: ?>
{{ $slot }}
<?php endif; ?>
