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
        public ?string $rounded = 'full'
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::button-icon');
    }
}
