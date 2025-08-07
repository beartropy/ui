<?php

namespace Beartropy\Ui\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AddPreset extends Command
{
    /*
    php artisan beartropy:add-preset radio red blue green yellow purple pink gray orange amber lime emerald teal cyan sky indigo violet rose fuchsia slate stone zinc neutral --force-vendor
    php artisan beartropy:add-preset input "#434343" --name="corporate"
    php artisan beartropy:add-preset button "#00ff00" "#ee4422" --force
    php artisan beartropy:add-preset input cyan --name="pepe"
    */

    protected $signature = 'beartropy:add-preset
        {component : Nombre del componente (ej: input)}
        {colors* : Nombres de colores Tailwind o hexadecimales}
        {--name= : Nombre del preset personalizado (opcional)}
        {--force : Sobrescribir si ya existe}
        {--force-vendor : Modificar directamente el archivo en vendor si no existe en config}';

    protected $description = 'Agrega presets de color a un componente a partir de un color existente, con soporte para hex y nombre custom';

    public function handle()
    {
        $component = $this->argument('component');
        $colors = $this->argument('colors');
        $customName = $this->option('name');
        $force = $this->option('force');
        $forceVendor = $this->option('force-vendor');

        $userPath = resource_path("views/vendor/beartropy/ui/presets/{$component}.php");
        $packagePath = base_path("vendor/beartropy/ui/resources/views/presets/{$component}.php");

        if (File::exists($userPath)) {
            $presetPath = $userPath;
        } elseif ($forceVendor && File::exists($packagePath)) {
            if (!$this->confirm("¿Seguro que querés modificar vendor? Esto puede perderse si actualizás el paquete.")) {
                $this->info("Operación cancelada.");
                return 0;
            }
            $presetPath = $packagePath;
            $this->warn("¡Atención! Vas a modificar directamente el archivo en vendor ({$packagePath}).");
        } else {
            if (File::exists($packagePath)) {
                $this->error("No existe el preset para '{$component}' en 'config/presets/'.\n");
                $this->line("Primero publicá los presets de Beartropy UI con:");
                $this->line("  php artisan vendor:publish --tag=beartropy-ui-presets");
                $this->line("O usá --force-vendor para modificar vendor directamente (solo recomendado en dev).");
                return 1;
            } else {
                $this->error("No se encontró el preset para '{$component}' ni en config/presets ni en vendor.");
                return 1;
            }
        }

        // Leer y parsear
        $content = File::get($presetPath);
        $presets = eval('?>' . $content);

        // Estructura: default_color, colors
        if (!is_array($presets) || !isset($presets['colors']) || !is_array($presets['colors'])) {
            $this->error("El archivo '{$presetPath}' no tiene la estructura esperada (default_color, colors).");
            return 1;
        }

        $colorPresets = $presets['colors'];
        $defaultColor = $presets['default_color'] ?? array_key_first($colorPresets);

        // El preset base para clonar
        $baseKey = $defaultColor;
        $basePreset = $colorPresets[$baseKey] ?? reset($colorPresets);

        if (!is_array($basePreset)) {
            $this->error("El preset base '{$baseKey}' no es un array.");
            return 1;
        }

        // Detectar el color base a reemplazar (ej: bg-beartropy- o bg-primary-)
        preg_match('/bg-([a-z0-9]+)-/', implode(' ', $basePreset), $baseMatch);
        $baseColor = $baseMatch[1] ?? $baseKey;

        foreach ($colors as $i => $color) {
            // Decide key: --name si hay uno solo, o hex-{...} para hex, o nombre de color
            $presetKey = ($customName && count($colors) == 1)
                ? $customName
                : (
                    Str::startsWith($color, '#')
                        ? ($customName && $i == 0 ? $customName : 'hex-' . ltrim($color, '#'))
                        : ($customName && $i == 0 ? $customName : $color)
                );

            if (isset($presets['colors'][$presetKey]) && !$force) {
                $this->warn("El preset '{$presetKey}' ya existe. Usá --force para sobrescribir.");
                continue;
            }

            // Clonar y reemplazar
            $newPreset = [];
            foreach ($basePreset as $k => $v) {
                if (Str::startsWith($color, '#')) {
                    $hex = ltrim($color, '#');
                    // Reemplaza cualquier color por el hex
                    $v = preg_replace('/(bg|text|border|ring|placeholder|outline|shadow|accent|from|to|via)-[a-z0-9\-]+/', "$1-[#{$hex}]", $v);
                } else {
                    $v = str_replace("{$baseColor}-", "{$color}-", $v);
                    $v = str_replace("{$baseKey}-", "{$color}-", $v); // fallback
                }
                $newPreset[$k] = $v;
            }

            $presets['colors'][$presetKey] = $newPreset;
            $this->info("Preset '{$presetKey}' agregado.");
        }

        // Re-escribir archivo con pretty print
        $arrayExport = $this->prettyPrintPresetArray($presets);
        $finalContent = "<?php\n\nreturn {$arrayExport};\n";

        File::put($presetPath, $finalContent);

        $this->info("¡Listo! Presets agregados a {$presetPath}");
        return 0;
    }

    /**
     * Pretty print para arrays tipo:
     * [
     *   'default_color' => 'beartropy',
     *   'colors' => [
     *       'beartropy' => [ ... ],
     *       ...
     *   ]
     * ]
     */
    protected function prettyPrintPresetArray(array $array, $indent = 0)
    {
        $pad = str_repeat('    ', $indent);
        $str = "[\n";
        foreach ($array as $key => $value) {
            $keyStr = is_numeric($key) ? $key : "'$key'";
            if (is_array($value)) {
                $str .= $pad . "    {$keyStr} => " . $this->prettyPrintPresetArray($value, $indent + 1);
            } else {
                $str .= $pad . "    {$keyStr} => '" . addslashes($value) . "',\n";
            }
        }
        $str .= $pad . "]" . ($indent == 0 ? '' : ",\n");
        return $str;
    }
}
