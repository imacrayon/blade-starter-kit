@aware([
    'id',
    'label',
    'description'
])

<?php if ($label): ?>
<x-field>
    <x-label :for="$id" :value="$label" />
    <?php if ($description) : ?>
        <x-description :for="$id" :value="$description" />
    <?php endif; ?>
    <x-error :for="$id" />
    {{ $slot }}
</x-field>
<?php else: ?>
{{ $slot }}
<?php endif; ?>
