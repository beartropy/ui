<?php

namespace Beartropy\Ui\Components;

class Datetime extends BeartropyComponent
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
        public $locale = 'es',
        public $range = false,
        public $initialValue = null,
        public $format = 'Y-m-d',
        public $formatDisplay = '{d}/{m}/{Y} {H}:{i}',
        public $showTime = false,
    ) {}

    public function render()
    {
        return view('beartropy-ui::datetime');
    }
}
