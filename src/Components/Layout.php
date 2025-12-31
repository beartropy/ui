<?php

namespace Beartropy\Ui\Components;


/**
 * Layout component.
 *
 * Main layout wrapper for the application.
 */
class Layout extends BeartropyComponent
{
    // No usar $options nunca


    /**
     * Create a new Layout component instance.
     */
    public function __construct() {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::layout');
    }
}
