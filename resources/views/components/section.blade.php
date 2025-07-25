<div {{ $attributes->merge(['class' => 'sm:mx-px relative bg-gray-50 [:where(&)]:rounded-[0.75rem] [:where(&)]:p-1']) }}>
    @isset($head)
        <div class="px-3 py-1">
            {{ $head }}
        </div>
    @endisset
    {{ $slot }}
</div>
