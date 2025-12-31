<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Range input logic.
 */
class RangeBase extends BeartropyComponent
{
    /**
     * Create a new RangeBase component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.range-base');
    }
}
