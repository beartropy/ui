<input
    type="range"
    {{ $attributes->merge([
        'class' => '
            w-full h-2 bg-neutral-200 dark:bg-neutral-800 rounded-lg appearance-none cursor-pointer
            accent-beartropy-600 transition
            focus:outline-none focus:ring-2 focus:ring-beartropy-500
        '
    ]) }}
>
