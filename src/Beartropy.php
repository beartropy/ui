<?php

namespace Beartropy\Ui;

use Illuminate\Support\Facades\Blade;

/**
 * Beartropy UI Core Class.
 *
 * Handles core functionality for the UI package, primarily icon parsing.
 */
class Beartropy
{
    /**
     * Parse and render an icon relative to the UI context.
     *
     * This method delegates to the global helper `beartropy_parse_icon`.
     * It handles various icon formats including Emojis, raw SVG strings, and Blade components.
     *
     * @param string|null $icon  The icon name, emoji, or SVG string.
     * @param string      $class CSS classes to apply to the icon.
     * @param string|null $set   The icon set to use (if applicable).
     *
     * @return string|null The rendered HTML/SVG string or null if no icon is provided.
     */
    public function parseIcon(?string $icon, string $class = 'w-5 h-5', ?string $set = null): ?string
    {
        return \beartropy_parse_icon($icon, $class, $set);
    }


}
