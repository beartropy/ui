<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

/**
 * Header component.
 *
 * Renders the application header.
 */
class Header extends Component
{
    // No usar $options nunca


    /**
     * Create a new Header component instance.
     */
    public function __construct() {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::header');
    }
}
