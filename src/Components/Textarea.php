<?php

namespace Beartropy\Ui\Components;


class Textarea extends BeartropyComponent
{

    public function __construct(
        public $label = null,
        public $placeholder = '',
        public $rows = 4,
        public $cols = null,
        public $name = null,
        public $id = null,
        public $color = null,
        public $disabled = false,
        public $readonly = false,
        public $required = false,
        public $help =  null,
        public $customError = null,
        public $autoResize = false,
        public $resize = null,
        public $showCounter = true,
        public $maxLength = null,
        public $showCopyButton = true,
    ) {}


    public function render()
    {
        return view('beartropy-ui::textarea');
    }
}
