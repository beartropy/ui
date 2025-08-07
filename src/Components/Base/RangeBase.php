<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class RangeBase extends BeartropyComponent
{
    public function __construct(){}

    public function render()
    {
        return view('beartropy-ui::base.range-base');
    }
}
