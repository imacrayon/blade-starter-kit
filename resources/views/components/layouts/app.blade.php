<x-layouts.app.sidebar :title="$title ?? null">
    <x-container class="[grid-area:main] max-w-full py-6 lg:py-8">
        {{ $slot }}
    </x-container>
</x-layouts.app.sidebar>

{{-- <x-layouts.app.header :title="$title ?? null">
    <x-container class="[grid-area:main] py-6 lg:py-8">
        {{ $slot }}
    </x-container>
</x-layouts.app.header> --}}
