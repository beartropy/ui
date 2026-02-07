<?php

namespace Beartropy\Ui\Components;


/**
 * Textarea Component.
 *
 * Renders a textarea input with optional auto-resize, character counter, and copy button.
 */
class Textarea extends BeartropyComponent
{
    /**
     * Create a new Textarea component instance.
     *
     * @param string|null $label          Label text.
     * @param string      $placeholder    Placeholder text.
     * @param int         $rows           Number of rows.
     * @param int|null    $cols           Number of columns.
     * @param string|null $name           Input name.
     * @param string|null $id             Input ID.
     * @param string|null $color          Border color state.
     * @param bool        $disabled       Disabled state.
     * @param bool        $readonly       Readonly state.
     * @param bool        $required       Required flag.
     * @param string|null $help           Help text.
     * @param mixed       $customError    Custom validation error.
     * @param bool        $autoResize     Enable auto-growing height.
     * @param string|null $resize         CSS resize property (none, both, horizontal, vertical).
     * @param bool        $showCounter    Show character count.
     * @param int|null    $maxLength      Max character length.
     * @param bool        $showCopyButton Show copy to clipboard button.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Initial content/value.
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
        public ?string $label = null,
        public string $placeholder = '',
        public int $rows = 4,
        public ?int $cols = null,
        public ?string $name = null,
        public ?string $id = null,
        public ?string $color = null,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $help = null,
        public mixed $customError = null,
        public bool $autoResize = false,
        public ?string $resize = null,
        public bool $showCounter = true,
        public ?int $maxLength = null,
        public bool $showCopyButton = true,
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::textarea');
    }
}
