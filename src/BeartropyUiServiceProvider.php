<?php

namespace Beartropy\Ui;

use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Beartropy UI Service Provider.
 *
 * Bootstraps the UI package, registering components, views, assets, and commands.
 */
class BeartropyUiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * - Loads routes.
     * - Loads views (components and SVGs).
     * - Registers custom component aliases.
     * - Publishes configuration and presets.
     * - Defines the 'BeartropyAssets' Blade directive for Ziggy and asset rendering.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'beartropy-ui');

        $this->loadViewsFrom(__DIR__ . '/../resources/views/components', 'beartropy-ui');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/svg', 'beartropy-ui-svg');

        $this->registerCustomComponents();

        Blade::componentNamespace('Beartropy\\Ui\\Components', 'beartropy-ui');

        $this->publishes([
            __DIR__ . '/../config/beartropyui.php' => config_path('beartropyui.php'),
        ], 'beartropy-ui-config');

        $this->publishes([
            __DIR__ . '/../resources/views/presets' => resource_path('views/vendor/beartropy/ui/presets'),
        ], 'beartropy-ui-presets');

        $this->publishIndividualPresets();

        Blade::directive('BeartropyAssets', function () {
            $ziggyUrl = route('beartropy.assets.ziggy');
            return <<<BLADE
        <?php
            echo app('beartropy.assets')->render();
        ?>
        <script src="{$ziggyUrl}" defer data-navigate-once></script>
        BLADE;
        });
    }

    /**
     * Publish individual preset files.
     *
     * Iterates through available presets and registers publishable tags for each one.
     *
     * @return void
     */
    protected function publishIndividualPresets()
    {
        $sourcePresets = __DIR__ . '/../resources/views/presets';
        $publishPath = resource_path('views/vendor/beartropy/ui/presets');

        foreach (glob($sourcePresets . '/*.php') as $presetFile) {
            $name = basename($presetFile, '.php');
            $tag = 'beartropyui-preset-' . $name;

            $this->publishes([
                $presetFile => $publishPath . '/' . $name . '.php'
            ], $tag);
        }
    }

    /**
     * Register custom Blade components.
     *
     * Scans the Components directory and registers aliases based on configuration.
     * Handles specific naming conventions for Base components and Icons.
     *
     * @return void
     */
    protected function registerCustomComponents()
    {
        $prefix = config('beartropyui.prefix');
        $prefix = $prefix ? $prefix . '-' : '';

        // Paths relativos a src
        $paths = [
            'Components' => '',
            'Components/Base' => 'base.'
        ];

        foreach ($paths as $folder => $aliasPrefix) {
            $dir = __DIR__ . '/' . $folder; // Asumiendo que estás en src
            if (!is_dir($dir)) continue;

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            foreach ($files as $file) {
                if ($file->isDir() || $file->getExtension() !== 'php') continue;

                $class = 'Beartropy\\Ui\\' . str_replace('/', '\\', $folder) . '\\' . $file->getBasename('.php');
                if (!class_exists($class)) continue;

                $basename = $file->getBasename('.php');

                if ($basename === 'BeartropyComponent') {
                    continue;
                }

                // Icon es caso especial
                $alias = $basename === 'Icon'
                    ? ($prefix . ($prefix ? 'icon' : 'bt-icon'))
                    : ($prefix . $aliasPrefix . Str::kebab($basename));

                Blade::component($class, $alias);
            }
        }

        \Illuminate\Support\Facades\Blade::component('beartropy-ui::partials.dropdown.item', $prefix . 'dropdown.item');
        \Illuminate\Support\Facades\Blade::component('beartropy-ui::partials.dropdown.header', $prefix . 'dropdown.header');
        \Illuminate\Support\Facades\Blade::component('beartropy-ui::partials.dropdown.separator', $prefix . 'dropdown.separator');
    }

    /**
     * Register the application services.
     *
     * - Registers the 'beartropy.assets' singleton.
     * - Registers the 'beartropy' main class singleton.
     * - Merges configuration.
     * - Registers console commands.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('beartropy.assets', function () {
            return new \Beartropy\Ui\Support\BeartropyAssets();
        });

        $this->app->singleton('beartropy', function ($app) {
            return new \Beartropy\Ui\Beartropy();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/beartropyui.php',
            'beartropyui'
        );

        $this->commands([
            \Beartropy\Ui\Commands\AddPreset::class,
            \Beartropy\Ui\Commands\InstallSkills::class,
        ]);

        if (class_exists(\Livewire\LivewireServiceProvider::class)) {
            $this->app->register(\Livewire\LivewireServiceProvider::class);
        }
    }
}
