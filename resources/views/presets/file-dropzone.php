<?php

return [
    'default_color' => 'beartropy',
    'colors' => [
        'beartropy' => [
            'wrapper'        => 'flex flex-col',
            'dropzone'       => 'relative flex items-center justify-center p-4 rounded-xl transition min-h-[12.5rem]
                                border-2 border-dashed border-beartropy-300
                                hover:border-beartropy-500 bg-beartropy-100 dark:bg-beartropy-800 hover:bg-beartropy-50/80 dark:hover:bg-beartropy-900/80
                                cursor-pointer',
            'icon'           => 'w-6 h-6 mb-2 text-beartropy-400 mr-5 mt-1',
            'label'          => 'text-gray-800 dark:text-gray-300 -mb-0.5 ml-1 text-[15px] font-semibold',
            'filename'       => 'text-beartropy-700 dark:text-beartropy-200 text-sm font-medium',
            'filelist'       => 'z-0 flex flex-col items-center',
            'preview'        => 'h-16 w-16 object-cover rounded shadow border border-beartropy-200',
            'error'          => 'text-red-500 text-xs mt-1',
            'emptyText'      => 'text-beartropy-400 select-none',
            'disabled'       => 'opacity-60 cursor-not-allowed',
        ],
    ]
];
