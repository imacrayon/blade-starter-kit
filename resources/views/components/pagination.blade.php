@props(['paginator'])

<div {{ $attributes }}>
  @if ($paginator->hasPages())
    {{ $paginator->withQueryString()->links() }}
  @else
    <div class="py-1 px-3">
      <p class="text-sm">
        {{ __('Showing') }}
        <span class="font-medium">{{ $count = $paginator->count() }}</span>
        {{ __('of') }}
        <span class="font-medium">{{ $count }}</span>
        {{ __('results') }}
      </p>
    </div>
  @endif
</div>
