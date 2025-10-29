<?php

namespace Beartropy\Ui\Components;

class CommandPalette extends BeartropyComponent
{

    public function __construct(
        public $color = null,
        public $items = null,
        public $source = null,
        public $cache = null,
        public $cacheKey = null
    ) {}

    public function render()
    {
        return view('beartropy-ui::command-palette');
    }
}
