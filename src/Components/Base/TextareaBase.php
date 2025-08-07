<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class TextareaBase extends BeartropyComponent
{
    public function __construct(){}

    public function render()
    {
        return view('beartropy-ui::base.textarea-base');
    }

}
