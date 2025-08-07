<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class Header extends Component
{
    // No usar $options nunca
   

    public function __construct(

    ) {

    }


    public function render()
    {
        return view('beartropy-ui::header');
    }
}
