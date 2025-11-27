<?php

namespace Beartropy\Ui\Components;

class ButtonIcon extends BeartropyComponent
{
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $spinner = true,
        public ?string $rounded = 'full',
        public ?string $iconSet = null,
        public ?string $iconVariant = null,
    ) {
        parent::__construct();
        $this->iconSet = $iconSet ?? config('beartropyui.icons.set', 'heroicons');
        $this->iconVariant = $iconVariant ?? config('beartropyui.icons.variant', 'outline');
    }

    public function render()
    {
        return view('beartropy-ui::button-icon');
    }
}
