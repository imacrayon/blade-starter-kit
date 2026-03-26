@props(['datetime'])

{{-- It's important that this file does NOT have a newline at the end. --}}
<local-time datetime="{{ $datetime->toW3cString() }}" {{ $attributes->merge([
    'day' => 'numeric',
    'month' => 'short',
    'year' => 'numeric',
    'hour' => 'numeric',
    'minute' => '2-digit',
  ]) }}>{{ $datetime }}</local-time>
