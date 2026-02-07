<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Dropdown logic.
 */
class DropdownBase extends BeartropyComponent
{
    public function __construct(
        public ?string $color = null,
        public ?string $placement = null,
        public ?string $side = null,
        public ?string $width = null,
        public ?string $presetFor = null,
        public ?bool $autoFit = null,
        public ?bool $autoFlip = null,
        public ?string $maxHeight = null,
        public ?string $overflow = null,
        public ?string $triggerLabel = null,
        public ?bool $fitAnchor = true,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::base.dropdown-base');
    }
}
