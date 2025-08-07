<?php

namespace Beartropy\Ui\Components;

class Toast extends BeartropyComponent
{

    public function __construct() {}

    public function render()
    {
        return view('beartropy-ui::toast');
    }
}
