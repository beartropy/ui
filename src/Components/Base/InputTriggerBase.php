<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Input Trigger logic (Selects, Datepickers, etc).
 */
class InputTriggerBase extends BeartropyComponent
{
    public function __construct(
        public $size = null,
        public $color = null,
        public $label = null,
        public $placeholder = null,
        public $type = null,
        public $hasError = false,
        public $name = null,
        public $disabled = false,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::base.input-trigger-base');
    }
}
