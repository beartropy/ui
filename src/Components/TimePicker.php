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
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Trigger content.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public mixed $value = null,
        public ?string $min = null,
        public ?string $max = null,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $placeholder = null,
        public ?string $hint = null,
        public ?string $customError = null,
        public string $format = 'H:i',
        public int $interval = 1,
        public bool $clearable = true,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::time-picker');
    }
}
