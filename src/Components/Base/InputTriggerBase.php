<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Input Trigger logic (Selects, Datepickers, etc).
 */
class InputTriggerBase extends BeartropyComponent
{
    public $size;
    public $color;
    public $label;
    public $placeholder;
    public $type;
    public $hasError;
    public $name;
    public $disabled;

    /**
     * Create a new InputTriggerBase component instance.
     *
     * @param string|null $size        Component size.
     * @param string|null $color       Component color.
     * @param string|null $label       Label.
     * @param string|null $placeholder Placeholder.
     * @param string|null $type        Type.
     * @param bool        $hasError    Error state.
     * @param string|null $name        Input name.
     * @param bool        $disabled    Disabled state.
     */
    public function __construct($size = null, $color = null, $label = null, $placeholder = null, $type = null, $hasError = false, $name = null, $disabled = false)
    {
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->hasError = $hasError;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.input-trigger-base');
    }
}
