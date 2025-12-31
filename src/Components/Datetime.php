<?php

namespace Beartropy\Ui\Components;

/**
 * Datetime component.
 *
 * Renders a date/time picker using flatpickr.
 *
 * @property string|null $name          Input name.
 * @property string|null $label         Input label.
 * @property mixed       $value         Initial value.
 * @property string|null $min           Minimum date.
 * @property string|null $max           Maximum date.
 * @property bool        $disabled      Disabled state.
 * @property bool        $readonly      Readonly state.
 * @property string|null $placeholder   Placeholder text.
 * @property string|null $hint          Hint text.
 * @property string|null $customError   Custom error message.
 * @property string      $locale        Locale setting.
 * @property bool        $range         Enable range mode.
 * @property mixed       $initialValue  Initial value override.
 * @property string      $format        Date format (PHP format).
 * @property string|null $formatDisplay Display format (JS format).
 * @property bool        $showTime      Enable time picker.
 * @property string|null $color         Input color.
 */
class Datetime extends BeartropyComponent
{
    /**
     * Create a new Datetime component instance.
     *
     * @param string|null $name          Input name.
     * @param string|null $label         Input label.
     * @param mixed       $value         Initial value.
     * @param string|null $min           Minimum date.
     * @param string|null $max           Maximum date.
     * @param bool        $disabled      Disabled state.
     * @param bool        $readonly      Readonly state.
     * @param string|null $placeholder   Placeholder text.
     * @param string|null $hint          Hint text.
     * @param string|null $customError   Custom error message.
     * @param string      $locale        Locale setting.
     * @param bool        $range         Enable range mode.
     * @param mixed       $initialValue  Initial value override.
     * @param string      $format        Date format (PHP format).
     * @param string|null $formatDisplay Display format (JS format).
     * @param bool        $showTime      Enable time picker.
     * @param string|null $color         Input color.
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
        public $locale = 'es',
        public $range = false,
        public $initialValue = null,
        public $format = 'Y-m-d',
        public $formatDisplay = null,
        public $showTime = false,
        public $color = null,
    ) {
        $this->formatDisplay = $formatDisplay ?? ($showTime ? '{d}/{m}/{Y} {H}:{i}' : '{d}/{m}/{Y}');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::datetime');
    }
}
