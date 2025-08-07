<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class InputTriggerBase extends BeartropyComponent
{
    public $size;
    public $color;
    public $label;
    public $placeholder;
    public $type;
    public $hasError;
    public $name;
    public $disabled;

    public function __construct($size = null, $color = null, $label = null, $placeholder = null, $type=null, $hasError=false, $name=null, $disabled=false)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->hasError = $hasError;
        $this->name = $name;
    }

    public function render()
    {
        return view('beartropy-ui::base.input-trigger-base');
    }

}
