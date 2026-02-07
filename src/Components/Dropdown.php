<?php

namespace Beartropy\Ui\Components;

/**
 * Dropdown Component.
 *
 * Renders a dropdown menu with various placement and styling options.
 */
class Dropdown extends BeartropyComponent
{

    /**
     * Create a new Dropdown component instance.
     *
     * @param string      $placement    Popper.js placement (e.g., 'bottom', 'bottom-start').
     * @param string      $side         Legacy alignment parameter.
     * @param string|null $color        Dropdown color theme.
     * @param string|null $size         Dropdown content width/size.
     * @param bool|null   $withnavigate Enable Wire Navigate on links (if supported).
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Body content.
     * @slot trigger Trigger button/content.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
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
        public string $placement = 'bottom',
        public string $side = 'left',
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $withnavigate = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::dropdown');
    }
}
