<?php

namespace Beartropy\Ui\Components;

class Card extends BeartropyComponent
{

    public function __construct(
        public ?string $title = null,
        public ?string $footer = null,
        public ?string $color = null,
        public ?string $size = null,
    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::card');
    }
}
