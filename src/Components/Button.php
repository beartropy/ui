<?php

namespace Beartropy\Ui\Components;

/**
 * Button Component.
 *
 * A versatile button component supporting various types, states, icons, and variants.
 */
class Button extends BeartropyComponent
{
    /**
     * Create a new Button component instance.
     *
     * @param string|null $type        Button type (button, submit, reset).
     * @param string|null $href        URL for link-style buttons.
     * @param bool|null   $disabled    Whether the button is disabled.
     * @param string|null $iconStart   Icon at the start of the button.
     * @param string|null $iconEnd     Icon at the end of the button.
     * @param string|null $label       Button text label.
     * @param bool|null   $spinner     Whether to show a loading spinner on wire:loading.
     * @param string|null $iconSet     Icon set to use.
     * @param string|null $iconSet     Icon set to use.
     * @param string|null $iconVariant Icon variant.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Button content.
     * @slot start   Prefix content/icon.
     * @slot end     Suffix content/icon.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     *
     * ### Magic Attributes (Variant)
     * @property bool $solid   Solid background (default).
     * @property bool $outline Outline style.
     * @property bool $ghost   Ghost/Transparent style.
     * @property bool $link    Link style.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     */
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::button');
    }

    /**
     * Modify wrapper and spinner classes if the button is used as an input trigger.
     *
     * Side Effect: Modifies the passed by reference string variables to adjust styling
     * when the button is part of an input group.
     *
     * @param string $wrapper      Reference to wrapper classes string.
     * @param string $spinnerColor Reference to spinner color classes string.
     *
     * @return void
     */
    public function detectForInput(&$wrapper, &$spinnerColor)
    {
        $forInput = isset($this->attributes['for-input-start']) || isset($this->attributes['for-input-end']);
        if ($forInput) {
            $wrapper = 'h-full  px-3 flex items-center transition bg-transparent hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-0 hover:border-blue-400 dark:hover:border-blue-500 hover:ring-1 hover:rounded focus:ring-1'
                . (isset($this->attributes['for-input-start']) ? ' rounded-l-md border-r border-gray-300 dark:border-gray-700 !-ml-4' : '')
                . (isset($this->attributes['for-input-end']) ? ' rounded-r-md border-l border-gray-300 dark:border-gray-700 ' : '');
            $spinnerColor = 'text-gray-700 dark:text-gray-100 button-spinner-class';
        }
    }
}
