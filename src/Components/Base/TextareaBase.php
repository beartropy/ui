<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Textarea logic.
 */
class TextareaBase extends BeartropyComponent
{
    /**
     * Create a new TextareaBase component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.textarea-base');
    }
}
