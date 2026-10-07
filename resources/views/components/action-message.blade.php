@blaze

@props([
    'on',
])

@if(session('status') === $on)
<div
    role="status"
    aria-live="polite"
    x-data="{ shown: true }"
    x-init="() => { $dispatch('{{ $on }}'); setTimeout(() => { shown = false }, 2000); }"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    {{ $attributes->class(['text-sm']) }}
>
    {{ $slot->isEmpty() ? __('Saved.') : $slot }}
</div>
@endif
