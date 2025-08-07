<?php

namespace Beartropy\Ui;

use Illuminate\Support\Facades\Blade;

class Beartropy
{
    public function parseIcon($icon, $class = 'w-5 h-5', $set = null)
    {
        return \beartropy_parse_icon($icon, $class, $set);
    }


}
