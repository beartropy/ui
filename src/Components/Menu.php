<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;

/**
 * Data-driven navigation menu with recursive nesting, section titles, badges, and active state detection.
 *
 * Items are plain arrays with `url`+`label` for links, `title` for section headings,
 * and `items` for nested submenus (recursive). Icons render through the shared
 * Icon component (Heroicons, Lucide, FontAwesome, or raw SVG/HTML).
 *
 * Colors are resolved via presets — pass a color name (`orange`, `blue`, `beartropy`)
 * as a magic attribute or through the `$color` prop.
 *
 * Active state is detected via `request()->is()` using the `route` key or the URL path.
 * Links use `wire:navigate` for Livewire SPA navigation.
 *
 * ## Item Shape
 *
 * ```php
 * // Link
 * ['url' => '/dashboard', 'label' => 'Dashboard', 'icon' => 'home', 'badge' => ['text' => '3', 'class' => '...']]
 * // Section with nested items
 * ['title' => 'Settings', 'items' => [ ... ]]
 * ```
 *
 * ## Blade Props
 *
 * ### View-Only Properties
 * @property string $iconClass  CSS classes for the icon wrapper (default `'w-4 h-4 shrink-0'`).
 * @property int    $level      Recursion depth (internal, default 0).
 * @property bool   $mobile     Mobile styling flag (adds `p-2`, default false).
 */
class Menu extends BeartropyComponent
{
    /**
     * @param array       $items   Menu items array.
     * @param string|null $color   Color preset name (e.g. 'orange', 'blue', 'beartropy').
     * @param string      $ulClass CSS classes for the `<ul>` wrapper.
     * @param string      $liClass CSS classes for `<li>` elements.
     */
    public function __construct(
        public array $items,
        public ?string $color = null,
        public string $ulClass = 'mt-4 space-y-2 dark:border-slate-800 lg:space-y-4 lg:mt-4 lg:border-slate-200',
        public string $liClass = 'relative',
    ) {}

    /**
     * Render an icon name to HTML using the shared Icon component.
     *
     * Supports raw HTML (`<svg>`, `<img>`, `<i>`), Heroicon names,
     * FontAwesome classes, or any set handled by Icon.
     */
    public function renderIcon(string $icon, string $iconClass = 'w-4 h-4 shrink-0'): string
    {
        if (! $icon) {
            return '';
        }

        if (str_starts_with($icon, '<svg') || str_starts_with($icon, '<img') || str_starts_with($icon, '<i')) {
            return $icon;
        }

        $iconComponent = new Icon(name: $icon, class: $iconClass);

        return Blade::renderComponent($iconComponent);
    }

    public function render(): View
    {
        return view('beartropy-ui::menu');
    }
}
