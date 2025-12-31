<?php

namespace Beartropy\Ui\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Beartropy\Ui\Beartropy
 * @see \Beartropy\Ui\BeartropyUiServiceProvider
 *
 * @method static mixed parseIcon(string $icon, string $class = 'w-5 h-5', string|null $set = null) Parse and render an icon.
 */
class Beartropy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'beartropy';
    }
}
