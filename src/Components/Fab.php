<?php

namespace Beartropy\Ui\Components;

class Fab extends BeartropyComponent
{

    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $onlyMobile = null,
        public ?string $zIndex = null,
        public ?string $right = null,
        public ?string $bottom = null,
        public ?string $color = null,
        public ?string $size = null,
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::fab');
    }
}
