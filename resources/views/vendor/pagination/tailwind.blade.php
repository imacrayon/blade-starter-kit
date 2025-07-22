<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 cursor-default leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-linear-to-b from-white to-gray-50 border border-gray-200 leading-5 rounded-md hover:bg-white hover:text-gray-700 focus:outline-hidden focus:ring-3 ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-linear-to-b from-white to-gray-50 border border-gray-200 leading-5 rounded-md hover:bg-white hover:text-gray-700 focus:outline-hidden focus:ring-3 ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 cursor-default leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div class="px-3">
            <p class="text-sm text-gray-700 leading-5">
                {!! __('Showing') !!}
                <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-semibold">{{ number_format($paginator->total()) }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <div>
            <span class="relative z-0 inline-flex rounded-sm shadow-xs -space-x-px">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 cursor-default rounded-l-md leading-5" aria-hidden="true">
                            <span class="sr-only">Previous</span>
                            <x-phosphor-caret-left width="20" height="20" aria-hidden="true" />
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-hidden focus:ring-3 ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                      <span class="sr-only">Previous</span>
                      <x-phosphor-caret-left width="20" height="20" aria-hidden="true" />
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-linear-to-b from-white to-gray-50 border border-gray-200 cursor-default leading-5">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative z-10 bg-linear-to-b from-blue-50 to-blue-100 border-blue-200 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-linear-to-b from-white to-gray-50 border border-gray-200 leading-5 hover:bg-white hover:text-gray-700 focus:z-10 focus:outline-hidden focus:ring-3 ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ number_format($page) }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-hidden focus:ring-3 ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                      <span class="sr-only">Next</span>
                      <x-phosphor-caret-right width="20" height="20" aria-hidden="true" />
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-linear-to-b from-white to-gray-50 border border-gray-200 cursor-default rounded-r-md leading-5" aria-hidden="true">
                            <span class="sr-only">Next</span>
                            <x-phosphor-caret-right width="20" height="20" aria-hidden="true" />
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
