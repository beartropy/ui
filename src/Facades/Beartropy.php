<?php

namespace Beartropy\Ui\Facades;

use Illuminate\Support\Facades\Facade;

class Beartropy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'beartropy';
    }
}
