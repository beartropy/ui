<?php

namespace Beartropy\Ui\Components;

class ToggleTheme extends BeartropyComponent
{
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
        // NEW
        public ?string $label = null,
        public string $labelPosition = 'right', // left | right
        public ?string $labelClass = null,
        public ?string $ariaLabel = null,
    ) {}

    /**
     * Retorna un objeto con todas las clases, flags y props necesarias para el blade.
     * Llamalo como $getViewData($__data) desde el blade.
     */
    public function getViewData(array $__data = [])
    {
        // Tamaños
        $sizes = [
            'xs' => 'w-2 h-2', 'sm' => 'w-3 h-3', 'md' => 'w-4 h-4',
            'lg' => 'w-5 h-5', 'xl' => 'w-6 h-6', '2xl' => 'w-8 h-8',
        ];
        $iconSize = $sizes[$this->size] ?? $sizes['md'];

        $buttonPaddings = [
            'xs'  => 'p-1', 'sm'  => 'p-1.5', 'md'  => 'p-2',
            'lg'  => 'p-3', 'xl'  => 'p-4', '2xl' => 'p-5',
        ];
        $buttonPadding = $buttonPaddings[$this->size] ?? $buttonPaddings['md'];

        $squareButtonSizes = [
            'xs'  => 'w-7 h-7', 'sm'  => 'w-8 h-8', 'md'  => 'w-10 h-10',
            'lg'  => 'w-12 h-12', 'xl'  => 'w-14 h-14', '2xl' => 'w-16 h-16',
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
        // Para accesibilidad: si no hay label visible y estamos en modo icon, usar ariaLabel
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
            'labelPosition'        => in_array($this->labelPosition, ['left','right']) ? $this->labelPosition : 'right',
            'labelClasses'         => $labelClasses . ' select-none',
            'ariaLabel'            => $ariaLabel,
        ];
    }

    public function render()
    {
        return view('beartropy-ui::toggle-theme');
    }
}
