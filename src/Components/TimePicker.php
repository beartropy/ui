<?php

namespace Beartropy\Ui\Components;

/**
 * TimePicker component.
 *
 * Renders a time picker input.
 *
 * @property string|null $name        Input name.
 * @property string|null $label       Label text.
 * @property mixed       $value       Initial value.
 * @property string|null $min         Minimum time.
 * @property string|null $max         Maximum time.
 * @property bool        $disabled    Disabled state.
 * @property bool        $readonly    Readonly state.
 * @property string|null $placeholder Placeholder text.
 * @property string|null $hint        Hint text.
 * @property string|null $customError Custom error message.
 * @property string      $format      Time format (default H:i).
 * @property int         $interval    Minute interval.
 * @property bool        $clearable   Allow clearing.
 */
class TimePicker extends BeartropyComponent
{
    /**
     * Create a new TimePicker component instance.
     *
     * @param string|null $name        Input name.
     * @param string|null $label       Label text.
     * @param mixed       $value       Initial value.
     * @param string|null $min         Min time.
     * @param string|null $max         Max time.
     * @param bool        $disabled    Disabled.
     * @param bool        $readonly    Readonly.
     * @param string|null $placeholder Placeholder.
     * @param string|null $hint        Hint.
     * @param string|null $customError Custom error.
     * @param string      $format      Format.
     * @param int         $interval    Interval.
     * @param bool        $clearable   Clearable.
     */
    public function __construct(
        public $name = null,
        public $label = null,
        public $value = null,
        public $min = null,
        public $max = null,
        public $disabled = false,
        public $readonly = false,
        public $placeholder = null,
        public $hint = null,
        public $customError = null,
        public $format = 'H:i',
        public $interval = 1,
        public $clearable = true,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::time-picker');
    }
}
