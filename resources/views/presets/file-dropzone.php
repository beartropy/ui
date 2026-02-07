<?php

$template = fn (string $c) => [
    'dropzone'       => "border-2 border-dashed border-{$c}-300 dark:border-{$c}-700 bg-{$c}-50/50 dark:bg-{$c}-950/30 rounded-xl transition cursor-pointer",
    'dropzone_hover' => "hover:border-{$c}-400 dark:hover:border-{$c}-600 hover:bg-{$c}-50 dark:hover:bg-{$c}-900/50",
    'dropzone_drag'  => "border-{$c}-500 bg-{$c}-100/80 dark:bg-{$c}-900/60 ring-2 ring-{$c}-300/50",
    'dropzone_error' => 'border-red-400 dark:border-red-600',
    'icon'           => "text-{$c}-400 dark:text-{$c}-500",
    'text'           => "text-{$c}-500 dark:text-{$c}-400",
    'subtext'        => "text-{$c}-400/70 dark:text-{$c}-500/70",
    'label'          => 'text-gray-800 dark:text-gray-200 text-sm font-medium mb-1',
    'label_error'    => 'text-red-500 text-sm font-medium mb-1',
    'file_item'      => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
    'file_name'      => 'text-gray-700 dark:text-gray-200',
    'file_size'      => 'text-gray-400 dark:text-gray-500',
    'progress'       => "bg-{$c}-500",
    'progress_track' => 'bg-gray-200 dark:bg-gray-700',
    'remove'         => 'text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400',
    'clear_all'      => 'text-red-500 hover:underline',
    'disabled'       => 'opacity-60 cursor-not-allowed pointer-events-none',
];

$colors = [
    'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal',
    'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink',
    'rose', 'slate', 'gray', 'zinc', 'neutral', 'stone',
];

$result = [
    'beartropy' => $template('beartropy'),
    'primary'   => $template('beartropy'),
];

foreach ($colors as $c) {
    $result[$c] = $template($c);
}

return ['colors' => $result];
