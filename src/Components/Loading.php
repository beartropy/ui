<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

/**
 * Loading component.
 *
 * Wrapper around Livewire loading indicators.
 *
 * @property string|null $customView Custom view for the loading state.
 */
class Loading extends Component
{
    // No usar $options nunca
    public $customView;

    /**
     * Create a new Loading component instance.
     *
     * @param string|null $customView Custom view path.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Content (unused in standard loading).
     */
    public function __construct($customView = null)
    {
        $this->customView = $customView;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
            <livewire:loading :customView="$customView" />
        blade;
    }
}
