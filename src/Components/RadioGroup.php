<?php

namespace Beartropy\Ui\Components;


/**
 * RadioGroup component.
 *
 * Wraps multiple Radio components to form a group.
 */
class RadioGroup extends BeartropyComponent
{
    /**
     * Create a new RadioGroup component instance.
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
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::radio-group');
    }
}
