<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class InputBase extends BeartropyComponent
{
    public $size;
    public $color;
    public $label;
    public $placeholder;
    public $type;
    public $hasError;
    public $outline;

    public function __construct($size = 'md', $color = null, $label = null, $placeholder = null, $type = 'text', $hasError=false, $outline=false)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->hasError = $hasError;
        $this->outline = $outline;
    }

    public function render()
    {
        return view('beartropy-ui::base.input-base');
    }

}
