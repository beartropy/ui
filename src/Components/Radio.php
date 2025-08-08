<?php

namespace Beartropy\Ui\Components;

class Radio extends BeartropyComponent
{

    public function __construct(
        public $labelPosition = null,
        public $size = null,
        public $color = null,
        public $label = null,
        public $customError = null,
        public $grouped = false,
        public $groupedError = false,
    ){}


    public function render()
    {
        return view('beartropy-ui::radio');
    }
}
