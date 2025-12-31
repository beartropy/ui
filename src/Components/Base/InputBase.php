<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Text Input logic.
 */
class InputBase extends BeartropyComponent
{
    public $size;
    public $color;
    public $label;
    public $placeholder;
    public $type;
    public $hasError;

    /**
     * Create a new InputBase component instance.
     *
     * @param string      $size        Input size.
     * @param string|null $color       Input color.
     * @param string|null $label       Label text.
     * @param string|null $placeholder Placeholder text.
     * @param string      $type        HTML input type.
     * @param bool        $hasError    Initial error state.
     */
    public function __construct($size = 'md', $color = null, $label = null, $placeholder = null, $type = 'text', $hasError = false)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->hasError = $hasError;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.input-base');
    }
}
