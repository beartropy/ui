<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Toast component.
 *
 * Renders toast notifications.
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
class Toast extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::toast');
    }
}
