@props([
  'for',
  'value' => null,
  'bag' => 'default',
])

<?php $for = \App\View\Components\Control::sessionPath($for); ?>

@error($for, $bag)
  <div {{ $attributes->class([
    'text-sm font-medium text-red-600 dark:text-red-400'
  ])->merge([
    'id' => $for.'_error',
  ]) }}>
    @if ($slot->isEmpty())
      {{ $value ?? $message }}
    @else
      {{ $slot }}
    @endif
  </div>
@enderror
