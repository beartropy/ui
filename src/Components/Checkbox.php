<?php

namespace Beartropy\Ui\Components;

/**
 * Checkbox Component.
 *
 * Renders a checkbox input with support for indeterminate state, colors, and labels.
 */
class Checkbox extends BeartropyComponent
{

    public $id;
    public $name;
    public $value;
    public $checked;
    public $disabled;
    public $indeterminate;
    public $color;
    public $error;
    public $description;
    public $label;
    public $labelPosition;

    /**
     * Create a new Checkbox component instance.
     *
     * @param string|null $id            Unique identifier.
     * @param string|null $name          Input name.
     * @param mixed       $value         Input value.
     * @param bool        $checked       Checked state.
     * @param bool        $disabled      Disabled state.
     * @param bool        $indeterminate Indeterminate state (visual only).
     * @param string|null $color         Checkbox color.
     * @param mixed       $error         Error state/message.
     * @param string|null $description   Helper text/description.
     * @param string|null $label         Label text.
     * @param string      $labelPosition Valid values: 'left', 'right'.
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = null,
        $checked = false,
        $disabled = false,
        $indeterminate = false,
        $color = null,
        $error = false,
        $description = null,
        $label = null,
        $labelPosition = 'right'
    ) {
        $this->id = $id ?? 'beartropy-checkbox-' . uniqid();
        $this->name = $name;
        $this->value = $value;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->indeterminate = $indeterminate;
        $this->color = $color;
        $this->error = $error;
        $this->description = $description;
        $this->label = $label;
        $this->labelPosition = $labelPosition;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::checkbox');
    }
}
