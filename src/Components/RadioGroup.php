<?php

namespace Beartropy\Ui\Components;


/**
 * RadioGroup component.
 *
 * Wraps multiple Radio components to form a group.
 */
class RadioGroup extends BeartropyComponent
{
    /**
     * Create a new RadioGroup component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::radio-group');
    }
}
