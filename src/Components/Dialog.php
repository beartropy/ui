<?php

namespace Beartropy\Ui\Components;

class Dialog extends BeartropyComponent
{

    public function __construct(
        public ?string $size = 'md'
    ) {}

    public function render()
    {
        return view('beartropy-ui::dialog');
    }
}
