<?php

namespace Beartropy\Ui\Components;

/**
 * ToggleTheme component.
 *
 * A dark/light mode toggle that persists to `localStorage` and syncs via
 * a custom `theme-change` event. Three display modes: bare icon, rounded
 * button, or square button. Includes a global `<script>` that applies the
 * saved theme before CSS loads (prevents FOUC) and re-applies on Livewire
 * navigation. An Alpine.js `x-data` block handles the toggle interaction,
 * rotation animation, and external event synchronization.
 *
 * @property string      $size             Size preset (xs, sm, md, lg, xl, 2xl).
 * @property string      $mode             Display mode: icon, button, square-button.
 * @property string      $class            Additional wrapper classes.
 * @property bool        $inheritColor     Inherit parent text color instead of defaults.
 * @property string|null $iconColorLight   Tailwind color class for the light-mode icon.
 * @property string|null $iconColorDark    Tailwind color class for the dark-mode icon.
 * @property string|null $borderColorLight Border classes in light mode (button/square-button).
 * @property string|null $borderColorDark  Border classes in dark mode (button/square-button).
 * @property string|null $iconLight        Heroicon name for light mode (replaces default SVG).
 * @property string|null $iconDark         Heroicon name for dark mode (replaces default SVG).
 * @property string|null $label            Visible label text (button mode only).
 * @property string      $labelPosition    Label position: left or right.
 * @property string|null $labelClass       Custom label CSS classes.
 * @property string|null $ariaLabel        Custom aria-label (defaults to localized 'Toggle theme').
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

        $buttonClasses = "flex items-center gap-2 rounded-full border-2 bg-white dark:bg-gray-900 transition hover:bg-gray-100 dark:hover:bg-gray-800 shadow-sm focus:outline-none cursor-pointer {$buttonPadding}";
        $squareButtonClasses = "flex items-center justify-center border-2 transition shadow-sm focus:outline-none cursor-pointer rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 {$squareButtonSize}";

        $iconLightClasses = "{$iconSize} " . ($this->iconColorLight ?? $defaultIconLight);
        $iconDarkClasses  = "{$iconSize} " . ($this->iconColorDark ?? $defaultIconDark);

        $hasIconLightSlot = isset($__data['iconLight']) && $__data['iconLight'] instanceof \Illuminate\View\ComponentSlot;
        $hasIconDarkSlot  = isset($__data['iconDark']) && $__data['iconDark'] instanceof \Illuminate\View\ComponentSlot;

        // Label
        $hasLabel = filled($this->label);
        $labelClasses = $this->labelClass
            ?? ($this->inheritColor
                ? "text-inherit"
                : "text-sm text-gray-700 dark:text-gray-200");
        $ariaLabel = $this->ariaLabel ?? ($hasLabel ? $this->label : __('beartropy-ui::ui.toggle_theme'));

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
