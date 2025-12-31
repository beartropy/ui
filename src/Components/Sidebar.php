<?php

namespace Beartropy\Ui\Components;


class Sidebar extends BeartropyComponent
{


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
