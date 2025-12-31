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
     *
     * ## Blade Props
     *
     * ### View Properties (via @props)
     * @param string|null $logo   Logo content/HTML.
     * @param string      $bg     Background classes (default: bg-light dark:bg-gray-900).
     * @param string      $border Border classes (default: border-gray-200 dark:border-gray-800).
     *
     * ### Slots
     * @slot default Navigation items.
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
