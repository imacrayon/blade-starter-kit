@blaze

<div {{ $attributes->class('flow-root') }}>
  <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="inline-block min-w-full py-2 align-middle px-4 sm:px-6 lg:px-8">
      <div class="overflow-clip min-w-max bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg shadow-xs">
        <table class="relative min-w-full">
          <thead {{ $head->attributes }}>
            {{ $head }}
          </thead>
          <tbody {{ $body->attributes->class('border-t border-gray-800/10 dark:border-gray-800 divide-y divide-gray-800/10 dark:divide-gray-800') }}>
            {{ $body }}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
