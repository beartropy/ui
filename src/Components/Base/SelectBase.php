<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for native Select logic.
 */
class SelectBase extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::base.select-base');
    }
}
