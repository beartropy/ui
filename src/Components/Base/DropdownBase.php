<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class DropdownBase extends BeartropyComponent
{
    public $placement;
    public $side;
    public $width;
    public $color;
    public $presetFor;

    public function __construct($color=null, $placement = null, $side = null, $width = null, $presetFor = null)
    {
        $this->color = $color;
        $this->placement = $placement;
        $this->side = $side;
        $this->width = $width;
        $this->presetFor = $presetFor;
    }

    public function render()
    {
        return view('beartropy-ui::base.dropdown-base');
    }

}
