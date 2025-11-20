<?php

namespace Beartropy\Ui\Components;

class TimePicker extends BeartropyComponent
{
    public function __construct(
        public $name = null,
        public $label = null,
        public $value = null,
        public $min = null,
        public $max = null,
        public $disabled = false,
        public $readonly = false,
        public $placeholder = null,
        public $hint = null,
        public $customError = null,
        public $format = 'H:i',
        public $interval = 1,
        public $clearable = true,
    ) {}

    public function render()
    {
        return view('beartropy-ui::time-picker');
    }
}
