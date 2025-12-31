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
     */
    public function __construct(
        public string $placement = 'bottom',
        public string $side = 'left',
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $withnavigate = null
    ) {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::dropdown');
    }
}
