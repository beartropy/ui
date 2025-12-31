<?php

namespace Beartropy\Ui\Components;

/**
 * Modal Component.
 *
 * Renders a modal dialog.
 */
class Modal extends BeartropyComponent
{
    // No usar $options nunca


    public function __construct() {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::modal');
    }
}
