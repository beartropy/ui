<?php

return [
    'misc_needed_options' => [
        'rounded-2xl',
        'rounded-3xl',
        'rounded-none',
    ],
    'xs' => [
        // Inputs, Selects, Textarea, Button, TagInput
        'height'      => 'h-7',    // [Input, Select, Textarea, Button]
        'minHeight'   => 'min-h-[1.75rem]', // [Input, Select, Textarea, Button]
        'font'        => 'text-xs',          // [Todos]
        'px'          => 'px-2',             // [Input, Select, Textarea, Button]
        'py'          => 'py-0.5',           // [Input, Select, Textarea, Button]

        // Checkbox, Radio, Switch
        'box'         => 'w-3 h-3',          // [Checkbox, Radio]
        'dot'         => 'w-1.5 h-1.5',      // [Radio]

        // Toggle (Switch)
        'trackHeight'    => 'h-4',           // [Toggle]
        'trackWidth'     => 'w-7',           // [Toggle]
        'thumb'          => 'w-3 h-3',       // [Toggle]
        'thumbTranslate' => 'peer-checked:translate-x-3', // [Toggle]
        'thumbTop'       => 'top-0.5',       // [Toggle]
        'thumbLeft'      => 'left-0.5',      // [Toggle]

        // Label separation
        'ml'          => 'ml-1',             // [Checkbox, Radio, Toggle]

        // Icons
        'iconSize'    => 'w-3 h-3',
        'avatar' => 'w-6 h-6 text-xs',

        //Dropdown
        'dropdownWidth'       => 'w-40',
        'fabIcon' => 'w-4 h-4',
        'fabButton' => 'w-10 h-10',
        'buttonIcon' => 'w-7 h-7',
        'buttonIconIcon' => 'w-2 h-2',
    ],
    'sm' => [
        // Inputs, Selects, Textarea, Button, TagInput
        'height'      => 'h-8',    // [Input, Select, Textarea, Button]
        'minHeight'   => 'min-h-[2rem]',
        'font'        => 'text-sm',
        'px'          => 'px-3',
        'py'          => 'py-1',

        // Checkbox, Radio, Switch
        'box'         => 'w-4 h-4',
        'dot'         => 'w-2 h-2',

        // Toggle (Switch)
        'trackHeight'    => 'h-5',
        'trackWidth'     => 'w-8',
        'thumb'          => 'w-4 h-4',
        'thumbTranslate' => 'peer-checked:translate-x-[0.77rem]',
        'thumbTop'       => 'top-[0.15rem]',
        'thumbLeft'      => 'left-0.5',

        // Label separation
        'ml'          => 'ml-1',

        'iconSize'    => 'w-4 h-4',
        'avatar' => 'w-8 h-8 text-sm',

        //Dropdown
        'dropdownWidth'       => 'w-48',
        'fabIcon' => 'w-6 h-6',
        'fabButton' => 'w-12 h-12',
        'buttonIcon' => 'w-8 h-8',
        'buttonIconIcon' => 'w-3 h-3',
    ],
    'md' => [
        'height'      => 'h-10',   // [Input, Select, Textarea, Button]
        'minHeight'   => 'min-h-[2.5rem]',
        'font'        => 'text-base',
        'px'          => 'px-3',
        'py'          => 'py-2',
        'box'         => 'w-5 h-5',
        'dot'         => 'w-2 h-2',
        'trackHeight'    => 'h-6',
        'trackWidth'     => 'w-10',
        'thumb'          => 'w-5 h-5',
        'thumbTranslate' => 'peer-checked:translate-x-[1.01rem]',
        'thumbTop'       => 'top-[0.155rem]',
        'thumbLeft'      => 'left-0.5',
        'ml'          => 'ml-1',
        'iconSize'    => 'w-5 h-5',
        'avatar' => 'w-10 h-10 text-base',
        //Dropdown
        'dropdownWidth'       => 'w-64',
        'fabIcon' => 'w-8 h-8',
        'fabButton' => 'w-14 h-14',
        'buttonIcon' => 'w-10 h-10',
        'buttonIconIcon' => 'w-5 h-5',
    ],
    'lg' => [
        'height'      => 'h-12',   // [Input, Select, Textarea, Button]
        'minHeight'   => 'min-h-[3rem]',
        'font'        => 'text-lg',
        'px'          => 'px-4',
        'py'          => 'py-3',
        'box'         => 'w-6 h-6',
        'dot'         => 'w-2.5 h-2.5',

        'trackHeight'    => 'h-7',
        'trackWidth'     => 'w-12',
        'thumb'          => 'w-6 h-6',
        'thumbTranslate' => 'peer-checked:translate-x-[1.3rem]',
        'thumbTop'       => 'top-0.5',
        'thumbLeft'      => 'left-0.5',

        'ml'          => 'ml-2',
        'iconSize'    => 'w-6 h-6',
        'avatar' => 'w-12 h-12 text-lg',
        //Dropdown
        'dropdownWidth'       => 'w-80',
        'fabIcon' => 'w-10 h-10',
        'fabButton' => 'w-16 h-16',
        'buttonIcon' => 'w-12 h-12',
        'buttonIconIcon' => 'w-6 h-6',
    ],
    'xl' => [
        'height'      => 'h-14',   // [Input, Select, Textarea, Button]
        'minHeight'   => 'min-h-[3.5rem]',
        'font'        => 'text-xl',
        'px'          => 'px-4',
        'py'          => 'py-4',
        'box'         => 'w-7 h-7',
        'dot'         => 'w-3 h-3',
        'trackHeight'    => 'h-8',
        'trackWidth'     => 'w-14',
        'thumb'          => 'w-7 h-7',
        'thumbTranslate' => 'peer-checked:translate-x-6',
        'thumbTop'       => 'top-0.5',
        'thumbLeft'      => 'left-0.5',
        'ml'          => 'ml-2',
        'iconSize'    => 'w-7 h-7',
        'avatar' => 'w-16 h-16 text-xl',
        //Dropdown
        'dropdownWidth'       => 'w-96',
        'fabIcon' => 'w-12 h-12',
        'fabButton' => 'w-18 h-18',
        'buttonIcon' => 'w-14 h-14',
        'buttonIconIcon' => 'w-7 h-7',
    ],
];
