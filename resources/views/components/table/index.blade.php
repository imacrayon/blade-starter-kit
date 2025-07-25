<div {{ $attributes->class('flow-root') }}>
  <div class="-mx-6 -my-2 overflow-x-auto lg:-mx-8">
    <div class="inline-block min-w-full py-2 align-middle px-6 lg:px-8">
      <div class="overflow-hidden bg-white rounded-lg shadow-xs ring-1 ring-gray-200/50">
        <table class="relative min-w-full">
          <thead {{ $head->attributes }}>
            {{ $head }}
          </thead>
          <tbody {{ $body->attributes->class('border-t border-gray-300 divide-y divide-gray-200 text-gray-600') }}">
            {{ $body }}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
