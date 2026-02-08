<?php

namespace Beartropy\Ui\Components;

/**
 * Datetime component.
 *
 * Renders a date/time picker with calendar UI and optional time wheel.
 *
 * @property string|null $id            Component id.
 * @property string|null $name          Input name.
 * @property string|null $label         Input label.
 * @property string|null $color         Color preset key.
 * @property mixed       $value         Initial value.
 * @property string|null $min           Minimum date (YYYY-MM-DD).
 * @property string|null $max           Maximum date (YYYY-MM-DD).
 * @property bool        $disabled      Disabled state.
 * @property bool        $readonly      Readonly state.
 * @property string|null $placeholder   Placeholder text.
 * @property string|null $hint          Hint text below input.
 * @property string|null $help          Help text below input.
 * @property mixed       $customError   Custom error message.
 * @property bool        $range         Enable range mode.
 * @property string      $format        Date format (PHP format).
 * @property string|null $formatDisplay Display format (JS format).
 * @property bool        $showTime      Enable time picker.
 * @property bool        $clearable     Allow clearing selection.
 */
class Datetime extends BeartropyComponent
{
    /**
     * Create a new Datetime component instance.
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
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $placeholder = null,
        public ?string $hint = null,
        public ?string $help = null,
        public mixed $customError = null,
        public bool $range = false,
        public string $format = 'Y-m-d',
        public ?string $formatDisplay = null,
        public bool $showTime = false,
        public bool $clearable = true,
    ) {
        $this->id = $id ?? ('beartropy-datetime-' . uniqid());
        $this->name = $name ?? $this->id;
        $this->formatDisplay = $formatDisplay ?? ($showTime ? '{d}/{m}/{Y} {H}:{i}' : '{d}/{m}/{Y}');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::datetime');
    }
}
