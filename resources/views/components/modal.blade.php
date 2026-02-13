@props([
    'id',
    'open' => false,
    'closable' => true,
])

<dialog id="{{ $id }}" {{ $attributes }} data-modal @if($open) open @endif>
    {{ $slot }}
    <?php if ($closable): ?>
        <div class="p-3 absolute top-0 right-0">
            <button commandfor="{{ $id }}" command="close" class="p-1.5 text-sm rounded-md inline-flex bg-transparent text-gray-400 hover:text-gray-800 hover:bg-gray-800/5 dark:text-gray-500 dark:hover:text-white dark:hover:bg-white/15">
                <x-phosphor-x aria-hidden="true" width="20" height="20" />
                <span class="sr-only">Close modal</span>
            </button>
        </div>
    <?php endif; ?>
</dialog>
