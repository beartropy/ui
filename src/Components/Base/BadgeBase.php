<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Badge component logic.
 */
class BadgeBase extends BeartropyComponent
{
    public function __construct(
        public ?string $color = null,
        public ?string $size = null,
        public ?string $variant = null,
        public ?string $iconLeft = null,
        public ?string $iconRight = null,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::base.badge-base');
    }
}
