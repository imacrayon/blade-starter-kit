<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-100 antialiased dark:bg-linear-to-b dark:from-gray-950 dark:to-gray-900">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium">
                    <x-app-logo width="36" height="36" />
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="space-y-6">
                    <div class="rounded-xl border border-gray-200 bg-white dark:bg-gray-950 dark:border-gray-800 text-gray-800 shadow-xs">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
