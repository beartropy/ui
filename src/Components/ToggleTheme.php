<?php

namespace Beartropy\Ui\Components;

/**
 * ToggleTheme component.
 *
 * Renders a button to toggle between light and dark modes.
 *
 * @property string      $size             Size preset.
 * @property string      $mode             Display mode (icon, button, square-button).
 * @property string      $class            Additional classes.
 * @property bool        $inheritColor     Inherit text color.
 * @property string|null $iconColorLight   Color class for light icon.
 * @property string|null $iconColorDark    Color class for dark icon.
 * @property string|null $borderColorLight Border color for light mode.
 * @property string|null $borderColorDark  Border color for dark mode.
 * @property string|null $iconLight        Icon name for light mode.
 * @property string|null $iconDark         Icon name for dark mode.
 * @property string|null $label            Label text.
 * @property string      $labelPosition    Label position (left, right).
 * @property string|null $labelClass       Custom label class.
 * @property string|null $ariaLabel        Aria label.
 */
class ToggleTheme extends BeartropyComponent
{
    /**
     * Create a new ToggleTheme component instance.
     *
     * @param string      $size             Size.
     * @param string      $mode             Mode.
     * @param string      $class            Classes.
     * @param bool        $inheritColor     Inherit color.
     * @param string|null $iconColorLight   Light icon color.
     * @param string|null $iconColorDark    Dark icon color.
     * @param string|null $borderColorLight Light border color.
     * @param string|null $borderColorDark  Dark border color.
     * @param string|null $iconLight        Light icon.
     * @param string|null $iconDark         Dark icon.
     * @param string|null $label            Label.
     * @param string      $labelPosition    Label pos.
     * @param string|null $labelClass       Label class.
     * @param string|null $ariaLabel        Aria label.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot icon-light Custom SVG/content for light mode icon.
     * @slot icon-dark  Custom SVG/content for dark mode icon.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl 2x Extra Large.
     */
    public function __construct(
        public string $size = 'md',
        public string $mode = 'icon', // icon | button | square-button
        public string $class = '',
        public bool $inheritColor = false,
        public ?string $iconColorLight = null,
        public ?string $iconColorDark = null,
        public ?string $borderColorLight = null,
        public ?string $borderColorDark = null,
        public ?string $iconLight = null,
        public ?string $iconDark = null,
        public ?string $label = null,
        public string $labelPosition = 'right', // left | right
        public ?string $labelClass = null,
        public ?string $ariaLabel = null,
    ) {}

    /**
     * Get view data for the component.
     *
     * @param array $__data Blade data attributes.
     * @return object View data object.
     */
    public function getViewData(array $__data = []): object
    {
        $sizes = [
            'xs' => 'w-2 h-2',
            'sm' => 'w-3 h-3',
            'md' => 'w-4 h-4',
            'lg' => 'w-5 h-5',
            'xl' => 'w-6 h-6',
            '2xl' => 'w-8 h-8',
        ];
        $iconSize = $sizes[$this->size] ?? $sizes['md'];

        $buttonPaddings = [
            'xs'  => 'p-1',
            'sm'  => 'p-1.5',
            'md'  => 'p-2',
            'lg'  => 'p-3',
            'xl'  => 'p-4',
            '2xl' => 'p-5',
        ];
        $buttonPadding = $buttonPaddings[$this->size] ?? $buttonPaddings['md'];

        $squareButtonSizes = [
            'xs'  => 'w-7 h-7',
            'sm'  => 'w-8 h-8',
            'md'  => 'w-10 h-10',
            'lg'  => 'w-12 h-12',
            'xl'  => 'w-14 h-14',
            '2xl' => 'w-16 h-16',
        ];
        $squareButtonSize = $squareButtonSizes[$this->size] ?? $squareButtonSizes['md'];

        $defaultIconLight   = 'text-orange-600';
        $defaultIconDark    = 'text-blue-400';
        $defaultBorderLight = 'border-orange-300 dark:border-blue-600';
        $defaultBorderDark  = 'border-orange-400 dark:border-blue-500';

        $buttonClasses = "flex items-center gap-2 rounded-full border-2 bg-white dark:bg-gray-900 transition hover:bg-gray-100 dark:hover:bg-gray-800 shadow-sm focus:outline-none {$buttonPadding}";
        $squareButtonClasses = "flex items-center justify-center border-2 transition shadow-sm focus:outline-none rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 {$squareButtonSize}";

        $iconLightClasses = "theme-rotatable {$iconSize} " . ($this->iconColorLight ?? $defaultIconLight);
        $iconDarkClasses  = "theme-rotatable {$iconSize} " . ($this->iconColorDark ?? $defaultIconDark);

        $hasIconLightSlot = isset($__data['icon-light']);
        $hasIconDarkSlot  = isset($__data['icon-dark']);

        // Label
        $hasLabel = filled($this->label);
        $labelClasses = $this->labelClass
            ?? ($this->inheritColor
                ? "text-inherit"
                : "text-sm text-gray-700 dark:text-gray-200");
        // For accessibility: use ariaLabel when there is no visible label in icon mode
        $ariaLabel = $this->ariaLabel ?? ($hasLabel ? $this->label : 'Toggle theme');

        return (object)[
            'buttonClasses'        => $buttonClasses,
            'squareButtonClasses'  => $squareButtonClasses,
            'iconLightClasses'     => $iconLightClasses,
            'iconDarkClasses'      => $iconDarkClasses,
            'iconLight'            => $this->iconLight,
            'iconDark'             => $this->iconDark,
            'iconSize'             => $iconSize,
            'squareButtonSize'     => $squareButtonSize,
            'hasIconLightSlot'     => $hasIconLightSlot,
            'hasIconDarkSlot'      => $hasIconDarkSlot,
            'mode'                 => $this->mode,
            'class'                => $this->class,
            'borderColorLight'     => $this->borderColorLight ?? $defaultBorderLight,
            'borderColorDark'      => $this->borderColorDark ?? $defaultBorderDark,
            // label
            'hasLabel'             => $hasLabel,
            'label'                => $this->label,
            'labelPosition'        => in_array($this->labelPosition, ['left', 'right']) ? $this->labelPosition : 'right',
            'labelClasses'         => $labelClasses . ' select-none',
            'ariaLabel'            => $ariaLabel,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::toggle-theme');
    }
}
