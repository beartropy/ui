<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * RadioGroup component.
 *
 * Wraps multiple Radio components to form a group.
 *
 * ## Blade Props
 *
 * ### View Properties (via @props)
 * @param string      $name    Input name.
 * @param array       $options Options array [['value' => '', 'label' => '']].
 * @param string      $color   Radio color.
 * @param string      $size    Radio size.
 * @param bool        $inline  Display inline.
 * @param bool        $disabled Disabled state.
 * @param string      $class   Additional classes.
 */
class RadioGroup extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::radio-group');
    }
}
