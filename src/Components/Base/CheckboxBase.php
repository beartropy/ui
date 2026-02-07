<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Checkbox logic.
 */
class CheckboxBase extends BeartropyComponent
{
    public function __construct(
        public ?string $size = 'md',
        public ?string $color = 'beartropy',
        public ?string $label = null,
        public ?string $customError = null,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::base.checkbox-base');
    }
}
