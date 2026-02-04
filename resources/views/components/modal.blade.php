@props([
    'id',
    'open' => false,
    'closable' => true,
])

<dialog id="{{ $id }}" {{ $attributes->merge([
    'x-init' => $open ? '$el.showModal()' : '',
    'x-on:modal:open.document' => "\$event.detail == '{$id}' && \$el.showModal()",
]) }}>
    {{ $slot }}
    <?php if ($closable): ?>
        <form id="{{ $id }}_close" method="dialog" class="p-3 absolute top-0 right-0">
            <button class="p-1.5 text-sm rounded-md inline-flex bg-transparent text-gray-400 hover:text-gray-800 hover:bg-gray-800/5 dark:text-gray-500 dark:hover:text-white dark:hover:bg-white/15">
                <x-phosphor-x aria-hidden="true" width="20" height="20" />
                <span class="sr-only">Close modal</span>
            </button>
        </form>
    <?php endif; ?>
</dialog>
