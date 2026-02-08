<?php

$template = fn (string $c) => [
    'wrapper'       => "relative bg-{$c}-300/50 dark:bg-{$c}-800 rounded-[2rem] transition overflow-hidden p-1.5",
    'wrapper_error' => 'border-red-500 ring-red-200 dark:ring-red-900',
    'label'         => "font-semibold text-{$c}-700 dark:text-{$c}-300/80 text-sm tracking-wide ml-1",
    'label_error'   => 'font-semibold text-red-500 text-sm tracking-wide ml-1',
    'input'         => "w-full border-none !outline-none bg-transparent py-2 px-3 text-{$c}-800 dark:text-{$c}-100 placeholder:text-gray-400 min-h-0 resize-none overflow-hidden max-h-60 mr-1",
    'footer'        => 'px-3 pb-2 pt-1 flex items-center justify-between',
    'help'          => 'text-xs mt-1 text-neutral-400 dark:text-neutral-500',
    'error'         => 'text-xs mt-1 text-red-500',
    'border'        => "border border-{$c}-300 dark:border-{$c}-700 shadow-sm focus-within:ring-2 focus-within:ring-{$c}-400 dark:focus-within:ring-{$c}-500",
];

$colors = [
    'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal',
    'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink',
    'rose', 'slate', 'gray', 'zinc', 'neutral', 'stone',
];

$result = [
    'beartropy' => array_merge($template('beartropy'), [
        'wrapper' => 'relative bg-gray-300/50 dark:bg-gray-900 rounded-[2rem] transition overflow-hidden p-1.5',
        'border'  => 'border border-gray-200 dark:border-gray-700/50 shadow-sm focus-within:ring-2 focus-within:ring-beartropy-400 dark:focus-within:ring-beartropy-500',
    ]),
    'primary' => array_merge($template('beartropy'), [
        'wrapper' => 'relative bg-gray-300/50 dark:bg-gray-900 rounded-[2rem] transition overflow-hidden p-1.5',
        'label'   => 'font-semibold text-gray-700 dark:text-gray-300 text-sm ml-1',
        'border'  => 'border border-gray-200 dark:border-gray-700/50 shadow-sm focus-within:ring-1 focus-within:ring-beartropy-500',
    ]),
    'white' => array_merge($template('zinc'), [
        'wrapper' => 'relative bg-white dark:bg-zinc-900 rounded-[2rem] transition overflow-hidden p-1.5',
        'border'  => 'border border-zinc-200 dark:border-zinc-800 shadow-sm focus-within:ring-2 focus-within:ring-zinc-200 dark:focus-within:ring-zinc-700',
    ]),
];

foreach ($colors as $c) {
    $result[$c] = $template($c);
}

return ['colors' => $result];
