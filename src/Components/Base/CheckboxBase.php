<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class CheckboxBase extends BeartropyComponent
{
    public $size, $color, $label;
    public $customError;

    public function __construct($size = 'md', $color = 'beartropy', $label = null, $customError = null)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->customError = $customError;
    }

    public function render()
    {
        return view('beartropy-ui::base.checkbox-base');
    }

}
