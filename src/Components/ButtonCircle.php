<?php

namespace Beartropy\Ui\Components;

class ButtonCircle extends BeartropyComponent
{

    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $spinner = true
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::button-circle');
    }
}
