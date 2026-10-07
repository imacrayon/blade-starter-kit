@blaze

@props(['method', 'action', 'hasFiles' => false, 'confirm' => null])

@php $method = strtolower($method); @endphp

<form method="{{ $method === 'get' ? 'get' : 'post' }}" action="{{ $action }}" {{ $attributes->merge([
    'enctype' => $hasFiles ? 'multipart/form-data' : null,
    'onsubmit' => $confirm ? 'return confirm('.Js::from($confirm).')' : null,
]) }}>
    @if ($method !== 'get')
        @csrf
    @endif
    @if (! in_array($method, ['get', 'post']))
        @method($method)
    @endif
    {{ $slot }}
</form>
