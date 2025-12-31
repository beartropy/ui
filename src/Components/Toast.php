<?php

namespace Beartropy\Ui\Components;

/**
 * Toast component.
 *
 * Renders toast notifications.
 */
class Toast extends BeartropyComponent
{
    /**
     * Create a new Toast component instance.
     */
    /**
     * Create a new Toast component instance.
     *
     * ## Blade Props
     *
     * ### View Properties (via @props)
     * @param string $position     Toast position (top-right, top-left, etc.).
     * @param string $bottomOffset Bottom offset for mobile/snackbars.
     *
     * ### Events
     * @see beartropy-add-toast Dispatched to add a new toast.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::toast');
    }
}
