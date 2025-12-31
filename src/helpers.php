<?php

use Illuminate\Support\Facades\Blade;

if (!function_exists('beartropy_preset')) {
    /**
     * Retrieve the configuration array for a specific Beartropy preset.
     *
     * Searches for the preset in the following order:
     * 1. Published resources in the project (`resources/views/vendor/beartropy/ui/presets`).
     * 2. Vendor package resources (`vendor/beartropy/ui/resources/views/presets`).
     *
     * @param string $name The name of the preset file (e.g., 'default').
     *
     * @return array<string, mixed> The preset configuration array.
     *
     * @throws \RuntimeException If the preset file cannot be found in either location.
     */
    function beartropy_preset(string $name)
    {
        // 1. Busca en published (proyecto)
        $published = base_path("resources/views/vendor/beartropy/ui/presets/{$name}.php");
        if (is_file($published)) {
            return require $published;
        }

        // 2. Busca en vendor (package)
        $package = __DIR__ . "/../resources/views/presets/{$name}.php";
        if (is_file($package)) {
            return require $package;
        }

        // 3. (Opcional) Error claro
        throw new \RuntimeException("Beartropy UI: Preset '{$name}' no se encuentra ni en resources/views/vendor/beartropy-ui/presets ni en el package.");
    }
}



if (!function_exists('beartropy_parse_icon')) {
    /**
     * Parse and render an icon for the UI.
     *
     * Handles:
     * - Emojis (unicode regex & smart detection).
     * - Raw SVG strings.
     * - Blade component icons via the `Icon` component.
     *
     * @param string|null $icon  The icon identifier, emoji, or SVG.
     * @param string      $class CSS classes for the icon.
     * @param string|null $set   Icon set preference.
     *
     * @return string|null The HTML/SVG string or null if empty.
     */
    function beartropy_parse_icon($icon, $class = 'w-5 h-5', $set = null)
    {
        if (empty($icon)) return null;

        // Regex de emojis: cubre la mayoría, incluyendo variantes (como ✈️)
        if (
            is_string($icon) &&
            preg_match('/^[\x{1F000}-\x{1FAFF}\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2600}-\x{27BF}\x{2700}-\x{27EF}][\x{FE0F}\x{200D}\x{1F3FB}-\x{1F3FF}]*/u', $icon)
        ) {
            return $icon;
        }
        // Además, si es string corto y no parece SVG ni HTML, asumimos emoji
        if (is_string($icon) && mb_strlen($icon) <= 3 && !str_starts_with(trim($icon), '<svg')) {
            return $icon;
        }
        // SVG crudo
        if (is_string($icon) && str_starts_with(trim($icon), '<svg')) {
            return $icon;
        }
        // Renderiza el componente Blade como SVG final
        return \Illuminate\Support\Facades\Blade::renderComponent(
            new \Beartropy\Ui\Components\Icon(
                name: $icon,
                class: $class,
                set: $set
            )
        );
    }
}
