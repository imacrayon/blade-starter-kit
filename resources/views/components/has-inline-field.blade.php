@aware([
    'id',
    'label',
    'description'
])

<?php if ($label): ?>
<div class="flex gap-x-2.5">
    {{ $slot }}
    <div>
        <x-label :for="$id" :value="$label" />
        <?php if ($description) : ?>
            <x-description :for="$id" :value="$description" />
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
{{ $slot }}
<?php endif; ?>
