<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Textarea logic.
 */
class TextareaBase extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::base.textarea-base');
    }
}
