@blaze

@props([
    'id' => null,
    'label' => '',
    'description' => '',
])

<?php if ($label): ?>
<div class="flex gap-x-2.5 has-disabled:opacity-60" onclick="if (! event.target.closest('label, input')) document.getElementById('{{ $id }}').click()">
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
