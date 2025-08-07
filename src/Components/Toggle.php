<?php

namespace Beartropy\Ui\Components;

class Toggle extends BeartropyComponent
{

    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public ?string $labelPosition = 'right',
        public ?string $color = null,
        public ?string $size = null,
        public ?string $customError = null,
        public ?bool $disabled = false,
        public ?string $hint = null,
        public ?string $help = null,
    ){}

    public function render()
    {
        return view('beartropy-ui::toggle');
    }
}
