<div {{ $attributes->class('flow-root') }}>
  <div class="-mx-6 -my-2 overflow-x-auto lg:-mx-8">
    <div class="inline-block min-w-full py-2 align-middle px-6 lg:px-8">
      <div class="overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg shadow-xs">
        <table class="relative min-w-full">
          <thead {{ $head->attributes }}>
            {{ $head }}
          </thead>
          <tbody {{ $body->attributes->class('border-t border-gray-800/10 dark:border-white/10 divide-y divide-gray-800/10 dark:divide-white/20') }}">
            {{ $body }}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
