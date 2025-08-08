<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blade Component Prefix
    |--------------------------------------------------------------------------
    | Set the prefix you want to use for all registered Blade components.
    | If left empty, components will be registered as <x-button>, <x-sidebar>, etc.
    | For example, setting 'bt' will register <x-bt-button>, <x-bt-sidebar>, etc.
    */
    'prefix' => env('BEARTROPY_UI_PREFIX', ''),

    'icons' => [

        /*
        |--------------------------------------------------------------------------
        | Default Icon Set
        |--------------------------------------------------------------------------
        | Determines which icon set will be used by default throughout your application.
        | Supported: "heroicons", "lucide", "fontawesome"
        | You can always override this per icon with the "set" attribute.
        */
        'set' => env('BEARTROPY_UI_ICON_SET', 'heroicons'),

        /*
        |--------------------------------------------------------------------------
        | Default Icon Variant (Heroicons Only)
        |--------------------------------------------------------------------------
        | Sets the default style variant for Heroicons. You can choose between
        | "outline" (default) and "solid". Lucide and FontAwesome only have one style.
        | This value can be overridden per icon with the "solid" or "outline" attributes.
        */
        'variant' => env('BEARTROPY_UI_ICON_VARIANT', 'outline'), // outline | solid
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Defaults
    |--------------------------------------------------------------------------
    | Here you can set the default values for each component.
    | These values will be used if no specific attributes are provided when using the component.
    | You can override these defaults by passing attributes directly to the component.
    */
    'component_defaults' => [
        'input' => [
            'color' => env('BEARTROPY_UI_INPUT_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
            'outline' => env('BEARTROPY_UI_INPUT_OUTLINE', true)
        ],
        'textarea' => [
            'color' => env('BEARTROPY_UI_TEXTAREA_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_TEXTAREA_SIZE', 'md'),
            'outline' => env('BEARTROPY_UI_TEXTAREA_OUTLINE', true)
        ],
        'tag' => [
            'color' => env('BEARTROPY_UI_TAG_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_TAG_SIZE', 'md'),
            'outline' => env('BEARTROPY_UI_TAG_OUTLINE', true)
        ],
        'button' => [
            'color' => env('BEARTROPY_UI_BUTTON_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_BUTTON_SIZE', 'md')
        ],
        'badge' => [
            'color' => env('BEARTROPY_UI_BADGE_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_BADGE_SIZE', 'sm'),
        ],
        'checkbox' => [
            'color' => env('BEARTROPY_UI_CHECKBOX_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_CHECKBOX_SIZE', 'md'),
        ],
        'radio' => [
            'color' => env('BEARTROPY_UI_RADIO_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_RADIO_SIZE', 'md'),
        ],
        'toggle' => [
            'color' => env('BEARTROPY_UI_TOGGLE_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_TOGGLE_SIZE', 'md'),
        ],
        'table' => [
            'color' => env('BEARTROPY_UI_TABLE_COLOR', 'beartropy'),
        ],
    ],

    'presets' => [
        'sizes'        => beartropy_preset('sizes'),
        'checkbox'     => beartropy_preset('checkbox'),
        'radio'        => beartropy_preset('radio'),
        'toggle'       => beartropy_preset('toggle'),
        'button'       => beartropy_preset('button'),
        'input'        => beartropy_preset('input'),
        'dropdown'     => beartropy_preset('dropdown'),
        'select'       => beartropy_preset('select'),
        'datetime'     => beartropy_preset('datetime'),
        'table'        => beartropy_preset('table'),
        'textarea'     => beartropy_preset('textarea'),
        'file-dropzone'=> beartropy_preset('file-dropzone'),
        'alert'        => beartropy_preset('alert'),
        'badge'        => beartropy_preset('badge'),
        'card'         => beartropy_preset('card'),
        'nav'          => beartropy_preset('nav'),
        'avatar'       => beartropy_preset('avatar'),
    ],
];
