<?php

namespace Beartropy\Ui\Components;

class DebugBreakpoints extends BeartropyComponent
{

    public function __construct(
        public $expanded = false,
        public $env = 'local',
    ) {}

    public function render()

    {
        return view('beartropy-ui::debug-breakpoints');
    }
}
