<?php

namespace Beartropy\Ui\Components;


/**
 * Sidebar component.
 *
 * Renders the application sidebar.
 */
class Sidebar extends BeartropyComponent
{
    /**
     * Create a new Sidebar component instance.
     */
    public function __construct() {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::sidebar');
    }
}
