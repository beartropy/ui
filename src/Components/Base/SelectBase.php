<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for native Select logic.
 */
class SelectBase extends BeartropyComponent
{
    /**
     * Create a new SelectBase component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.select-base');
    }
}
