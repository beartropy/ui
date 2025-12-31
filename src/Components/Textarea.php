<?php

namespace Beartropy\Ui\Components;


/**
 * Textarea Component.
 *
 * Renders a textarea input with optional auto-resize, character counter, and copy button.
 */
class Textarea extends BeartropyComponent
{
    /**
     * Create a new Textarea component instance.
     *
     * @param string|null $label          Label text.
     * @param string      $placeholder    Placeholder text.
     * @param int         $rows           Number of rows.
     * @param int|null    $cols           Number of columns.
     * @param string|null $name           Input name.
     * @param string|null $id             Input ID.
     * @param string|null $color          Border color state.
     * @param bool        $disabled       Disabled state.
     * @param bool        $readonly       Readonly state.
     * @param bool        $required       Required flag.
     * @param string|null $help           Help text.
     * @param mixed       $customError    Custom validation error.
     * @param bool        $autoResize     Enable auto-growing height.
     * @param string|null $resize         CSS resize property (none, both, horizontal, vertical).
     * @param bool        $showCounter    Show character count.
     * @param int|null    $maxLength      Max character length.
     * @param bool        $showCopyButton Show copy to clipboard button.
     */
    public function __construct(
        public $label = null,
        public $placeholder = '',
        public $rows = 4,
        public $cols = null,
        public $name = null,
        public $id = null,
        public $color = null,
        public $disabled = false,
        public $readonly = false,
        public $required = false,
        public $help =  null,
        public $customError = null,
        public $autoResize = false,
        public $resize = null,
        public $showCounter = true,
        public $maxLength = null,
        public $showCopyButton = true,
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::textarea');
    }
}
