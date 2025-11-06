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

    public $autoFit;
    public $autoFlip;
    public $maxHeight;
    public $overflow;

    public $triggerLabel;

    public function __construct($color=null, $placement = null, $side = null, $width = null, $presetFor = null, $autoFit = null, $autoFlip = null, $maxHeight = null, $overflow = null, $triggerLabel = null)
    {
        $this->color = $color;
        $this->placement = $placement;
        $this->side = $side;
        $this->width = $width;
        $this->presetFor = $presetFor;
        $this->autoFit = $autoFit;
        $this->autoFlip = $autoFlip;
        $this->maxHeight = $maxHeight;
        $this->overflow = $overflow;
        $this->triggerLabel = $triggerLabel;
    }

    public function render()
    {
        return view('beartropy-ui::base.dropdown-base');
    }

}
