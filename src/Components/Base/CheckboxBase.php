<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Checkbox logic.
 */
class CheckboxBase extends BeartropyComponent
{
    public $size, $color, $label;
    public $customError;

    /**
     * Create a new CheckboxBase component instance.
     *
     * @param string      $size        Checkbox size.
     * @param string      $color       Checkbox color.
     * @param string|null $label       Label text.
     * @param mixed       $customError Custom error message.
     */
    public function __construct($size = 'md', $color = 'beartropy', $label = null, $customError = null)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->customError = $customError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.checkbox-base');
    }
}
