<div class="[grid-area:banner]">
    <section role="status" class="sr-only" id="announcements" x-sync>
        @foreach(Session::pull('announcements', []) as $notification)
            <p>{{ $notification['content'] }}</p>
        @endforeach
    </section>
    @if($impersonating = Session::get('impersonating'))
        <div role="alert" class="bg-yellow-100 py-1">
            <x-container class="flex items-center justify-between gap-x-6">
                <p class="text-sm leading-6 text-yellow-800">
                    Impersonating {{ $impersonating }}.
                </p>
                <div class="flex flex-1 justify-end">
                    <x-form method="delete" action="{{ route('impersonation.destroy') }}">
                        <button class="flex-none rounded-full bg-yellow-800 px-3.5 py-1 text-sm font-medium text-yellow-100 hover:bg-yellow-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-900">Stop impersonating</button>
                    </x-form>
                </div>
            </x-container>
        </div>
    @endif
</div>
