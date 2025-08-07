<select
    {{ $attributes->merge([
        'class' => '
            block w-full rounded border border-neutral-300 dark:border-neutral-700
            bg-white dark:bg-gray-900/50
            text-base text-gray-800 dark:text-gray-100
            px-3 py-2 h-10 min-h-[2.5rem]
            focus:ring-2 focus:ring-beartropy-500 focus:border-beartropy-500
            outline-none transition-all shadow-sm box-border
            disabled:opacity-60 disabled:cursor-not-allowed disabled:bg-neutral-100 dark:disabled:bg-neutral-800
        '
    ]) }}
>
    {{ $slot }}
</select>
