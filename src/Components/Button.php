<?php

namespace Beartropy\Ui\Components;

class Button extends BeartropyComponent
{
    public function __construct(
        public ?string $type = null,
        public ?string $href = null,
        public ?bool $disabled = false,
        public ?string $iconStart = null,
        public ?string $iconEnd = null,
        public ?string $label = null,
        public ?bool $spinner = true,
        public ?string $iconSet = null,
        public ?string $iconVariant = null,
    ) {
        $this->iconSet = $iconSet ?? config('beartropyui.icons.set', 'heroicons');
        $this->iconVariant = $iconVariant ?? config('beartropyui.icons.variant', 'outline');
    }

    public function render()
    {
        return view('beartropy-ui::button');
    }

    public function detectForInput(&$wrapper, &$spinnerColor)
    {
        $forInput = isset($this->attributes['for-input-start']) || isset($this->attributes['for-input-end']);
        if ($forInput) {
            $wrapper = 'h-full  px-3 flex items-center transition bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-0 hover:border-blue-400 dark:hover:border-blue-500 hover:ring-1 hover:rounded focus:ring-1'
                .(isset($this->attributes['for-input-start']) ? ' rounded-l-md border-r border-gray-300 dark:border-gray-700 !-ml-4' : '')
                .(isset($this->attributes['for-input-end']) ? ' rounded-r-md border-l border-gray-300 dark:border-gray-700 ' : '');
            $spinnerColor = 'text-gray-700 dark:text-gray-100 button-spinner-class';
        }
    }
}
