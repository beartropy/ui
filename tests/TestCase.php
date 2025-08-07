<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Beartropy\Ui\BeartropyUIServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Ajustá el path a donde esté tu config real
        $app['config']->set('beartropyui.presets', require __DIR__ . '/../config/presets.php');
    }
}
