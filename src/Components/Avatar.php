<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\BeartropyComponent;

class Avatar extends BeartropyComponent
{
    public $src, $alt, $size, $initials, $color, $customSize;

    public function __construct($src = null, $alt = '', $size = null, $color = null, $initials = null, $customSize = null)
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->size = $size;
        $this->color = $color;
        $this->initials = $initials;
        $this->customSize = $customSize;
    }

    public function render()
    {
        return view('beartropy-ui::avatar');
    }

}
