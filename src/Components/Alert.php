<?php

namespace Beartropy\Ui\Components;

class Alert extends BeartropyComponent
{
    public function __construct(
        public $noIcon = false,
        public $icon = null,
        public $title = null,
        public $dismissible = false,
        public $class = '',
        public $color = null
    ){}

    public function render()
    {
        return view('beartropy-ui::alert');
    }

}
