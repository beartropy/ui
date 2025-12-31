<?php

namespace Beartropy\Ui\Components;

/**
 * Radio component.
 *
 * Renders a radio button input, optionally with a label and error handling.
 *
 * @property string|null $labelPosition Label position (left, right).
 * @property string|null $size          Size preset.
 * @property string|null $color         Color preset.
 * @property string|null $label         Label text.
 * @property string|null $customError   Custom error message.
 * @property bool        $grouped       Whether it belongs to a group.
 * @property bool        $groupedError  Whether to show error for the group.
 */
class Radio extends BeartropyComponent
{
    /**
     * Create a new Radio component instance.
     *
     * @param string|null $labelPosition Label position.
     * @param string|null $size          Size preset.
     * @param string|null $color         Color preset.
     * @param string|null $label         Label text.
     * @param string|null $customError   Custom error message.
     * @param bool        $grouped       Is grouped.
     * @param bool        $groupedError  Show group error.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Label content override.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     */
    public function __construct(
        public $labelPosition = null,
        public $size = null,
        public $color = null,
        public $label = null,
        public $customError = null,
        public $grouped = false,
        public $groupedError = false,
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::radio');
    }
}
