<label class="flex items-center gap-3 cursor-pointer">
    <input type="file" {{ $attributes->merge(['class' => 'hidden']) }}>
    <span class="
                inline-flex items-center px-4 py-2 rounded
                border border-neutral-300 dark:border-neutral-700
                bg-white dark:bg-gray-900/50
                text-base text-gray-800 dark:text-gray-100
                h-10 min-h-[2.5rem]
                focus:ring-2 focus:ring-beartropy-500 focus:border-beartropy-500
                outline-none transition-all shadow-sm box-border">
        {{ $slot ?? __('beartropy-ui::ui.choose_file') }}
    </span>
</label>
