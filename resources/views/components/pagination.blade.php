@props(['paginator'])

<div {{ $attributes }}>
  @if ($paginator->hasPages())
    {{ $paginator->withQueryString()->links() }}
  @else
    <div class="py-1 px-3">
      <p class="text-sm text-gray-700 leading-5">
        Showing
        <span class="font-medium">{{ $count = $paginator->count() }}</span>
        of
        <span class="font-medium">{{ $count }}</span>
        results
      </p>
    </div>
  @endif
</div>
