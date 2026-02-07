<?php

namespace Beartropy\Ui\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

/**
 * Artisan Command: Add Preset.
 *
 * Adds or modifies color presets for UI components.
 * Can modify published views or the vendor package directly (with force option).
 */
class AddPreset extends Command
{
    /*
    php artisan beartropy:add-preset radio red blue green yellow purple pink gray orange amber lime emerald teal cyan sky indigo violet rose fuchsia slate stone zinc neutral --force-vendor
    php artisan beartropy:add-preset input "#434343" --name="corporate"
    php artisan beartropy:add-preset button "#00ff00" "#ee4422" --force
    php artisan beartropy:add-preset input cyan --name="pepe"
    */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beartropy:add-preset
        {component : Component name (e.g. input)}
        {colors* : Tailwind color names or hex values}
        {--name= : Custom preset name (optional)}
        {--force : Overwrite if already exists}
        {--force-vendor : Modify the vendor file directly if not published to config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add color presets to a component based on an existing color, with hex and custom name support';

    /**
     * Execute the console command.
     *
     * @return int Command exit code.
     */
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
            if (!$this->confirm("Are you sure you want to modify vendor? Changes may be lost on package update.")) {
                $this->info("Operation cancelled.");
                return 0;
            }
            $presetPath = $packagePath;
            $this->warn("Warning! You are modifying the vendor file directly ({$packagePath}).");
        } else {
            if (File::exists($packagePath)) {
                $this->error("Preset for '{$component}' not found in 'config/presets/'.\n");
                $this->line("First publish the Beartropy UI presets with:");
                $this->line("  php artisan vendor:publish --tag=beartropy-ui-presets");
                $this->line("Or use --force-vendor to modify vendor directly (only recommended in dev).");
                return 1;
            } else {
                $this->error("Preset for '{$component}' not found in config/presets or vendor.");
                return 1;
            }
        }

        // Read and parse
        $content = File::get($presetPath);
        $presets = eval('?>' . $content);

        // Structure: default_color, colors
        if (!is_array($presets) || !isset($presets['colors']) || !is_array($presets['colors'])) {
            $this->error("File '{$presetPath}' does not have the expected structure (default_color, colors).");
            return 1;
        }

        $colorPresets = $presets['colors'];
        $defaultColor = $presets['default_color'] ?? array_key_first($colorPresets);

        // Base preset to clone
        $baseKey = $defaultColor;
        $basePreset = $colorPresets[$baseKey] ?? reset($colorPresets);

        if (!is_array($basePreset)) {
            $this->error("Base preset '{$baseKey}' is not an array.");
            return 1;
        }

        // Detect the base color to replace (e.g. bg-beartropy- or bg-primary-)
        preg_match('/bg-([a-z0-9]+)-/', implode(' ', $basePreset), $baseMatch);
        $baseColor = $baseMatch[1] ?? $baseKey;

        foreach ($colors as $i => $color) {
            // Decide key: --name if only one, or hex-{...} for hex, or color name
            $presetKey = ($customName && count($colors) == 1)
                ? $customName
                : (
                    Str::startsWith($color, '#')
                    ? ($customName && $i == 0 ? $customName : 'hex-' . ltrim($color, '#'))
                    : ($customName && $i == 0 ? $customName : $color)
                );

            if (isset($presets['colors'][$presetKey]) && !$force) {
                $this->warn("Preset '{$presetKey}' already exists. Use --force to overwrite.");
                continue;
            }

            // Clone and replace
            $newPreset = [];
            foreach ($basePreset as $k => $v) {
                if (Str::startsWith($color, '#')) {
                    $hex = ltrim($color, '#');
                    // Replace any color with the hex value
                    $v = preg_replace('/(bg|text|border|ring|placeholder|outline|shadow|accent|from|to|via)-[a-z0-9\-]+/', "$1-[#{$hex}]", $v);
                } else {
                    $v = str_replace("{$baseColor}-", "{$color}-", $v);
                    $v = str_replace("{$baseKey}-", "{$color}-", $v); // fallback
                }
                $newPreset[$k] = $v;
            }

            $presets['colors'][$presetKey] = $newPreset;
            $this->info("Preset '{$presetKey}' added.");
        }

        // Rewrite file with pretty print
        $arrayExport = $this->prettyPrintPresetArray($presets);
        $finalContent = "<?php\n\nreturn {$arrayExport};\n";

        File::put($presetPath, $finalContent);

        $this->info("Done! Presets added to {$presetPath}");
        return 0;
    }

    /**
     * Pretty print for arrays like:
     * [
     *   'default_color' => 'beartropy',
     *   'colors' => [
     *       'beartropy' => [ ... ],
     *       ...
     *   ]
     * ]
     *
     * @param array $array  Array to print.
     * @param int   $indent Indentation level.
     *
     * @return string
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
