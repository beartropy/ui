<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Checkbox Component.
 *
 * Renders a checkbox input with support for indeterminate state, colors, and labels.
 */
class Checkbox extends BeartropyComponent
{
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
        public ?string $id = null,
        public ?string $name = null,
        public mixed $value = null,
        public bool $checked = false,
        public bool $disabled = false,
        public bool $indeterminate = false,
        public ?string $color = null,
        public mixed $error = false,
        public ?string $description = null,
        public ?string $label = null,
        public string $labelPosition = 'right',
    ) {
        $this->id = $id ?? 'beartropy-checkbox-' . uniqid();
    }

    public function render(): View
    {
        return view('beartropy-ui::checkbox');
    }
}
