<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Header component.
 *
 * Renders the application header.
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
class Header extends Component
{
    public function render(): View
    {
        return view('beartropy-ui::header');
    }
}
