<?php

namespace Beartropy\Ui\Components;

class Skeleton extends BeartropyComponent
{

    public function __construct(
        public string $init,
        public int $lines = 1,
        public string $rounded = 'lg',
        public string $tag = 'div',
        public ?string $skeletonClass = null,
        public ?string $shape = 'card', // card | rectangle | none
        public ?int $rows = null,
        public ?int $cols = null,

    ) {
        parent::__construct();
    }

    public function render()
    {
        return view('beartropy-ui::skeleton');
    }
}
