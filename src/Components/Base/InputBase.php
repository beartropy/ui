<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Text Input logic.
 */
class InputBase extends BeartropyComponent
{
    public function __construct(
        public $size = 'md',
        public $color = null,
        public $label = null,
        public $placeholder = null,
        public $type = 'text',
        public $hasError = false,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::base.input-base');
    }
}
