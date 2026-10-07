@blaze

@props(['name' => null, 'param' => 'sort'])

@php $classes = 'py-1.5 px-3 [:where(&)]:text-left text-sm font-medium whitespace-nowrap text-gray-900 dark:text-white'; @endphp

<?php if ($name): ?>
    @php
        $sortValue = request($param);
        $active = $sortValue === "{$name}:asc" || $sortValue === "{$name}:desc";
        $ascending = $sortValue === "{$name}:asc";
        $href = request()->fullUrlWithQuery([
            $param => $name.':'.($ascending ? 'desc' : 'asc'),
            'page' => null,
        ]);
    @endphp
    <th {{ $attributes->class($classes)->merge(['aria-sort' => $active ? ($ascending ? 'ascending' : 'descending') : null]) }}>
        <a href="{{ $href }}" class="group inline-flex items-center gap-1">
            {{ $slot }}
            <x-dynamic-component
                :component="$active ? ($ascending ? 'phosphor-caret-up' : 'phosphor-caret-down') : 'phosphor-caret-up-down'"
                aria-hidden="true" width="12" height="12"
                class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
            />
        </a>
    </th>
<?php else: ?>
    <th {{ $attributes->class($classes) }}>
        {{ $slot }}
    </th>
<?php endif; ?>
