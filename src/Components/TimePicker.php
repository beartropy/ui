<?php

namespace Beartropy\Ui\Components;

/**
 * TimePicker component.
 *
 * Renders a time picker input with scrollable hour/minute/second columns,
 * 12-hour or 24-hour format, min/max range, and interval support.
 *
 * @property string|null $id          Component id.
 * @property string|null $name        Input name.
 * @property string|null $label       Label text.
 * @property string|null $color       Color preset key.
 * @property mixed       $value       Initial value (HH:mm or HH:mm:ss).
 * @property string|null $min         Minimum allowed time (HH:mm).
 * @property string|null $max         Maximum allowed time (HH:mm).
 * @property int         $interval    Minute step (1, 5, 10, 15, 30, 60).
 * @property string      $format      Time format (H:i = 24h, h:i A = 12h).
 * @property bool        $seconds     Show seconds column.
 * @property bool        $disabled    Disabled state.
 * @property bool        $readonly    Readonly state.
 * @property string|null $placeholder Placeholder text.
 * @property bool        $clearable   Allow clearing.
 * @property mixed       $customError Custom error message.
 * @property string|null $help        Help text below input.
 * @property string|null $hint        Hint text below input.
 */
class TimePicker extends BeartropyComponent
{
    /**
     * Create a new TimePicker component instance.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Trigger content.
     *
     * ### Magic Attributes (Color)
     * @property bool $beartropy Beartropy color.
     * @property bool $red       Red color.
     * @property bool $orange    Orange color.
     * @property bool $amber     Amber color.
     * @property bool $yellow    Yellow color.
     * @property bool $lime      Lime color.
     * @property bool $green     Green color.
     * @property bool $emerald   Emerald color.
     * @property bool $teal      Teal color.
     * @property bool $cyan      Cyan color.
     * @property bool $sky       Sky color.
     * @property bool $blue      Blue color.
     * @property bool $indigo    Indigo color.
     * @property bool $violet    Violet color.
     * @property bool $purple    Purple color.
     * @property bool $fuchsia   Fuchsia color.
     * @property bool $pink      Pink color.
     * @property bool $rose      Rose color.
     * @property bool $slate     Slate color.
     * @property bool $gray      Gray color.
     * @property bool $zinc      Zinc color.
     * @property bool $neutral   Neutral color.
     * @property bool $stone     Stone color.
     * @property bool $primary   Primary color (alias for beartropy).
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $color = null,
        public mixed $value = null,
        public ?string $min = null,
        public ?string $max = null,
        public int $interval = 1,
        public string $format = 'H:i',
        public bool $seconds = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $placeholder = null,
        public bool $clearable = true,
        public mixed $customError = null,
        public ?string $help = null,
        public ?string $hint = null,
    ) {
        $this->id = $id ?? ('beartropy-timepicker-' . uniqid());
        $this->name = $name ?? $this->id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::time-picker');
    }
}
