<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Badge component logic.
 */
class BadgeBase extends BeartropyComponent
{
    /**
     * Create a new BadgeBase component instance.
     *
     * @param string|null $color     Badge color.
     * @param string|null $size      Badge size.
     * @param string|null $variant   Badge variant.
     * @param string|null $iconLeft  Left icon name.
     * @param string|null $iconRight Right icon name.
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
        return view('beartropy-ui::base.badge-base');
    }
}
