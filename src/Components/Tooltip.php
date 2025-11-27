<?php

namespace Beartropy\Ui\Components;

class Tooltip extends BeartropyComponent
{
    public function __construct(
        public ?string $label = null,
        public ?int $delay = 0,
        public ?string $position = 'right',
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::tooltip');
    }
}
