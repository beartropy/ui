<?php

namespace Beartropy\Ui;

use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BeartropyUiServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views/components', 'beartropy-ui');
        $this->loadViewsFrom(__DIR__.'/../resources/views/svg', 'beartropy-ui-svg');

        $this->registerCustomComponents();

        Blade::componentNamespace('Beartropy\\Ui\\Components', 'beartropy-ui');

        $this->publishes([
            __DIR__.'/../config/beartropyui.php' => config_path('beartropyui.php'),
        ], 'beartropy-ui-config');

        $this->publishes([
            __DIR__.'/../resources/views/presets' => resource_path('views/vendor/beartropy/ui/presets'),
        ], 'beartropy-ui-presets');

        $this->publishIndividualPresets();

        Blade::directive('BeartropyAssets', function () {
            return "<?php echo app('beartropy.assets')->render(); ?>";
        });
    }

    protected function publishIndividualPresets()
    {
        $sourcePresets = __DIR__ . '/../resources/views/presets';
        $publishPath = resource_path('views/vendor/beartropy/ui/presets');

        foreach (glob($sourcePresets.'/*.php') as $presetFile) {
            $name = basename($presetFile, '.php');
            $tag = 'beartropyui-preset-' . $name;

            $this->publishes([
                $presetFile => $publishPath.'/'.$name.'.php'
            ], $tag);
        }
    }

    protected function registerCustomComponents()
    {
        $prefix = config('beartropyui.prefix');
        $prefix = $prefix ? $prefix.'-' : '';

        // Paths relativos a src
        $paths = [
            'Components' => '',
            'Components/Base' => 'base.'
        ];

        foreach ($paths as $folder => $aliasPrefix) {
            $dir = __DIR__.'/'.$folder; // Asumiendo que estás en src
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

    public function register()
    {

        $this->app->singleton('beartropy.assets', function () {
            return new \Beartropy\Ui\Support\BeartropyAssets();
        });

        $this->app->singleton('beartropy', function ($app) {
            return new \Beartropy\Ui\Beartropy();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/beartropyui.php',
            'beartropyui'
        );

        $this->commands([
            \Beartropy\Ui\Commands\AddPreset::class,
        ]);

        if (class_exists(\Livewire\LivewireServiceProvider::class)) {
            $this->app->register(\Livewire\LivewireServiceProvider::class);
        }

    }



}
