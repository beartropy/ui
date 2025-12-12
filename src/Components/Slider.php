<?php

namespace Beartropy\Ui\Components;

class Slider extends BeartropyComponent
{
    public function __construct(
        public bool $show = false,
        public ?string $color = null,
        public string $side = 'right',
        public bool $backdrop = true,
        public bool $blur = true,
        public string $maxWidth = 'max-w-xl 2xl:max-w-4xl',
        public string $headerPadding = 'px-4 py-3 sm:px-6',
        public bool $static = false
    ){}

    public function render()
    {
        return view('beartropy-ui::slider');
    }

}
