<?php

namespace Beartropy\Ui\Components;

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
        $this->id = $id ?? 'beartropy-checkbox-'.uniqid();
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


    public function render()
    {
        return view('beartropy-ui::checkbox');
    }
}
