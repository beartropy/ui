<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * RadioGroup component.
 *
 * Wraps multiple Radio components to form a group with shared name, color, size, and validation.
 *
 * @property bool $primary   Primary color.
 * @property bool $beartropy Beartropy color (default).
 * @property bool $red       Red color.
 * @property bool $blue      Blue color.
 * @property bool $green     Green color.
 */
class RadioGroup extends BeartropyComponent
{
    public function __construct(
        public string $name = '',
        public array $options = [],
        public ?string $color = null,
        public ?string $size = null,
        public bool $inline = false,
        public bool $disabled = false,
        public bool $required = false,
        public mixed $value = null,
        public mixed $customError = null,
        public ?string $label = null,
        public ?string $help = null,
        public ?string $hint = null,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::radio-group');
    }
}
