<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Sidebar component.
 *
 * Renders the application sidebar.
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
class Sidebar extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::sidebar');
    }
}
