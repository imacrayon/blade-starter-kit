@props(['datetime', 'format' => 'datetime'])

{{-- It's important that this file does NOT have a newline at the end. --}}
<relative-time datetime="{{ $datetime->toW3cString() }}" year="numeric" weekday="" {{ $attributes->merge(['format' => $format]) }}>{{ $datetime }}</relative-time>