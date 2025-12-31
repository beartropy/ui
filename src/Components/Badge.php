<?php

namespace Beartropy\Ui\Components;

/**
 * Badge Component.
 *
 * Renders a small badge or tag, used to label items with status or category.
 */
class Badge extends BeartropyComponent
{

    /**
     * Create a new Badge component instance.
     *
     * @param string|null $color     Badge color theme.
     * @param string|null $size      Badge size (sm, md, lg).
     * @param string|null $variant   Style variant (solid, outline, etc.).
     * @param string|null $iconLeft  Icon to display on the left.
     * @param string|null $iconRight Icon to display on the right.
     */
    public function __construct(
        public $color = null,
        public $size = null,
        public $variant = null,
        public $iconLeft = null,
        public $iconRight = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::badge');
    }
}
