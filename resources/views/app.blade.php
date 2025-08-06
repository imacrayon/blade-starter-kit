<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <svg class="absolute stroke-gray-900/20 dark:stroke-gray-100/20">
                <defs>
                    <pattern id="placeholder-pattern" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                    </pattern>
                </defs>
            </svg>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                <svg class="absolute inset-0 size-full">
                    <rect stroke="none" fill="url(#placeholder-pattern)" width="100%" height="100%"></rect>
                </svg>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                <svg class="absolute inset-0 size-full">
                    <rect stroke="none" fill="url(#placeholder-pattern)" width="100%" height="100%"></rect>
                </svg>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                <svg class="absolute inset-0 size-full">
                    <rect stroke="none" fill="url(#placeholder-pattern)" width="100%" height="100%"></rect>
                </svg>
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <svg class="absolute inset-0 size-full">
                <rect stroke="none" fill="url(#placeholder-pattern)" width="100%" height="100%"></rect>
            </svg>
        </div>
    </div>
</x-layouts.app>
