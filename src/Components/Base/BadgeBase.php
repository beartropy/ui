<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

class BadgeBase extends BeartropyComponent
{

    public function __construct(
        public $color = null,
        public $size = null,
        public $variant = null,
        public $iconLeft = null,
        public $iconRight = null,
    ) {}

    public function render()
    {
        return view('beartropy-ui::base.badge-base');
    }

}
