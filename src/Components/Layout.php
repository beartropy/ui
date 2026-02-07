<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Layout component.
 *
 * Main layout wrapper for the application.
 *
 * ## Blade Props
 *
 * ### Slots
 * @slot default Main content.
 * @slot sidebar Sidebar content.
 * @slot header  Header content.
 * @slot footer  Footer content.
 */
class Layout extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::layout');
    }
}
