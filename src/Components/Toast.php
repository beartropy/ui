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
