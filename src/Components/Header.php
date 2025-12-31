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
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Main center content.
     * @slot actions Right-side action buttons.
     *
     * ### View Properties
     * @property string|null $logo   Logo URL.
     * @property string      $title  Header title.
     * @property bool        $fixed  Fixed positioning.
     * @property bool        $mini   Mini sidebar mode adjustment.
     * @property int         $zIndex Z-index value.
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
