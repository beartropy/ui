<?php

return [
    'default' => [
        // Input wrapper
        'wrapper' => 'relative w-full',

        // Label
        'label' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1',
        'label_error' => 'block text-sm font-medium text-red-600 dark:text-red-400 mb-1',

        // Input trigger
        'base' => 'w-full flex items-center justify-between border rounded-lg shadow-sm transition duration-150 ease-in-out focus:ring-2 focus:ring-offset-2',
        'sizes' => [
            'sm' => 'px-2 py-1 text-xs',
            'md' => 'px-3 py-2 text-sm',
            'lg' => 'px-4 py-3 text-base',
        ],
        'colors' => [
            'default' => 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-beartropy-500 focus:border-beartropy-500',
            'error' => 'border-red-300 dark:border-red-600 bg-white dark:bg-gray-800 text-red-900 dark:text-red-100 focus:ring-red-500 focus:border-red-500',
        ],

        // Dropdown
        'dropdown_wrapper' => 'absolute z-50 mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-2',
        'select' => 'block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-beartropy-500 focus:border-beartropy-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white',
        'option_text' => 'text-gray-900 dark:text-gray-100',

        // List UI
        'list_wrapper' => 'flex items-start justify-center gap-2 h-56',
        'list_column' => 'flex flex-col h-full overflow-y-auto beartropy-thin-scrollbar w-16 text-center scroll-smooth',
        'list_item' => 'py-1 px-2 cursor-pointer rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 transition-colors',
        'list_item_active' => 'bg-beartropy-500 text-white font-bold hover:bg-beartropy-600 dark:hover:bg-beartropy-600',
    ],
];
