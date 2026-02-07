<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Checkbox Component.
 *
 * Renders a checkbox input with support for colors, labels, and validation errors.
 *
 * @property bool $xs Extra Small size.
 * @property bool $sm Small size.
 * @property bool $md Medium size (default).
 * @property bool $lg Large size.
 * @property bool $xl Extra Large size.
 * @property bool $primary   Primary color.
 * @property bool $beartropy Beartropy color (default).
 * @property bool $red       Red color.
 * @property bool $blue      Blue color.
 * @property bool $green     Green color.
 * @property bool $yellow    Yellow color.
 * @property bool $purple    Purple color.
 * @property bool $pink      Pink color.
 * @property bool $gray      Gray color.
 * @property bool $orange    Orange color.
 * @property bool $amber     Amber color.
 * @property bool $lime      Lime color.
 * @property bool $emerald   Emerald color.
 * @property bool $teal      Teal color.
 * @property bool $cyan      Cyan color.
 * @property bool $sky       Sky color.
 * @property bool $indigo    Indigo color.
 * @property bool $violet    Violet color.
 * @property bool $rose      Rose color.
 * @property bool $fuchsia   Fuchsia color.
 * @property bool $slate     Slate color.
 * @property bool $stone     Stone color.
 * @property bool $zinc      Zinc color.
 * @property bool $neutral   Neutral color.
 */
class Checkbox extends BeartropyComponent
{
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public mixed $value = null,
        public bool $checked = false,
        public bool $disabled = false,
        public ?string $color = null,
        public ?string $size = null,
        public mixed $customError = null,
        public ?string $label = null,
        public string $labelPosition = 'right',
        public ?string $help = null,
        public ?string $hint = null,
    ) {
        $this->id = $id ?? 'beartropy-checkbox-' . uniqid();
    }

    public function render(): View
    {
        return view('beartropy-ui::checkbox');
    }
}
