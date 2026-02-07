<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Range input logic.
 */
class RangeBase extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::base.range-base');
    }
}
