<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Radio component.
 *
 * Renders a radio button input with label, colors, sizes, and validation errors.
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
class Radio extends BeartropyComponent
{
    public function __construct(
        public ?string $labelPosition = null,
        public ?string $size = null,
        public ?string $color = null,
        public ?string $label = null,
        public mixed $customError = null,
        public bool $disabled = false,
        public bool $grouped = false,
        public ?string $help = null,
        public ?string $hint = null,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::radio');
    }
}
