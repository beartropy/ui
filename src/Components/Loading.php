<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class Loading extends Component
{
    // No usar $options nunca
    public $customView;

    public function __construct($customView = null)
    {
        $this->customView = $customView;
    }

    public function render()
    {
        return <<<'blade'
            <livewire:loading :customView="$customView" />
        blade;
    }

}
