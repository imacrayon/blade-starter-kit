@props(['route' => null, 'name' => null])

<?php if ($name): ?>
    @php
        $param = request('sort', '');
        $dir = $param === "{$name}__asc" ? 'ascending' : 'descending';
    @endphp
    <th {{ $attributes->merge([
        'aria-sort' => Str::startsWith($param, $name) ? $dir : null,
        'class' => 'py-1.5 px-3 text-sm font-medium whitespace-nowrap text-gray-900'
    ]) }}>
        <a href="{{ route($route, ['sort' => $name.'__'.($dir === 'ascending' ? 'desc' : 'asc')]) }}">
            {{ $slot }}
        </a>
    </th>
<?php else: ?>
    <th {{ $attributes->class('py-1.5 px-3 text-sm font-medium whitespace-nowrap text-gray-900') }}>
        {{ $slot }}
    </th>
<?php endif; ?>
