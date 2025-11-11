<?php

namespace Beartropy\Ui\Components;

class Dropdown extends BeartropyComponent
{

    public function __construct(
        public string $placement = 'bottom',
        public string $side = 'left',
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $withnavigate = null
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::dropdown');
    }
}
