<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;

/**
 * Full-featured sidebar navigation with categories, nested children, permissions, and active state detection.
 *
 * Renders a `<nav>` element driven by Alpine.js for collapse/expand, hover submenus (via x-teleport),
 * and client-side active state reconciliation. Supports two highlight modes: 'standard' (background
 * highlight) and 'text' (text-only highlight with hover). Colors are resolved from `presets/nav.php`.
 *
 * Items support permission gating (`can`, `canAny`, `canMatch`) with optional admin bypass,
 * nested `children`, badges (slot, Alpine, or static), dividers, external links, and tooltips.
 *
 * @property array       $items                Resolved and permission-filtered navigation items.
 * @property string      $sidebarBind          Alpine variable name for sidebar collapse binding.
 * @property string      $highlightMode        Highlight mode: 'standard' (bg) or 'text' (text-only).
 * @property string|null $highlightParentClass CSS classes for active parent items.
 * @property string|null $highlightChildClass  CSS classes for active child items.
 * @property string|null $itemClass            Base CSS classes for parent items.
 * @property string|null $childItemClass       Base CSS classes for child items.
 * @property string      $categoryClass        CSS classes for category headings.
 * @property string      $iconClass            CSS classes for icon rendering.
 * @property string      $childBorderClass     CSS classes for child container border.
 * @property string|null $hoverTextClass       Hover classes for parent items (text mode).
 * @property string|null $hoverTextChildClass  Hover classes for child items (text mode).
 * @property bool        $withnavigate         Whether to add wire:navigate to links.
 */
class Nav extends BeartropyComponent
{

    public $items;
    public $sidebarBind;
    public $highlightMode;
    public $highlightParentClass;
    public $highlightChildClass;
    public $itemClass;
    public $childItemClass;
    public $categoryClass;
    public $iconClass;
    public $childBorderClass;
    public $hoverTextClass;
    public $hoverTextChildClass;
    public $withnavigate;
    public $hideCategories;
    public $singleOpenExpanded;
    public $collapseButtonAsItem;
    public $collapseButtonLabelCollapse;
    public $collapseButtonLabelExpand;
    public $collapseButtonIconCollapse;
    public $collapseButtonIconExpand;
    public $rememberCollapse;
    public $rememberCollapseKey;
    public $hoverMenuShowHeader;
    public $hoverMenuHeaderClass;
    public $hoverMenuHeaderTextClass;


    /**
     * Create a new Nav component instance.
     *
     * @param mixed       $items                Array of items, or string config name.
     * @param string      $sidebarBind          Sidebar bind variable.
     * @param string      $highlightMode        Highlight mode.
     * @param string|null $highlightParentClass Custom highlight parent.
     * @param string|null $highlightChildClass  Custom highlight child.
     * @param string|null $itemClass            Item class.
     * @param string|null $childItemClass       Child item class.
     * @param string      $categoryClass        Category class.
     * @param string      $iconClass            Icon class.
     * @param string      $childBorderClass     Child border class.
     * @param string|null $hoverTextClass       Hover text class.
     * @param string|null $hoverTextChildClass  Child hover text class.
     * @param string      $color                Color preset.
     * @param bool        $withnavigate         Enable wire:navigate.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot badge-{id} Custom badge content for a specific item ID.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     */
    public function __construct(
        $items = null,
        $sidebarBind = 'sidebarCollapsed',
        $highlightMode = 'standard',
        $highlightParentClass = null,
        $highlightChildClass = null,
        $itemClass = null,
        $childItemClass = null,
        $categoryClass = 'text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase px-3 mb-1 tracking-wide select-none',
        $iconClass = '',
        $childBorderClass = 'border-l border-gray-300 dark:border-gray-700',
        $hoverTextClass = null,
        $hoverTextChildClass = null,
        $color = 'beartropy',
        $withnavigate = false,
        $hideCategories = false,
        $singleOpenExpanded = false,
        $collapseButtonAsItem = true,
        $collapseButtonLabelCollapse = null,
        $collapseButtonLabelExpand = null,
        $collapseButtonIconCollapse = 'arrows-pointing-in',
        $collapseButtonIconExpand = 'arrows-pointing-out',
        $rememberCollapse = null,
        $rememberCollapseKey = 'beartropy:sidebar:collapsed',
        $hoverMenuShowHeader = true,
        $hoverMenuHeaderClass = 'sticky top-0 z-10 px-3 py-2 border-b border-gray-200/80 dark:border-gray-700/70 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm',
        $hoverMenuHeaderTextClass = 'font-bold text-sm text-gray-700 dark:text-gray-400',
    ) {
        $this->sidebarBind = $sidebarBind;
        $this->highlightMode = $highlightMode;
        $this->hideCategories = $hideCategories;
        $this->singleOpenExpanded = $singleOpenExpanded;
        $this->collapseButtonAsItem = $collapseButtonAsItem;
        $this->collapseButtonLabelCollapse = $collapseButtonLabelCollapse;
        $this->collapseButtonLabelExpand = $collapseButtonLabelExpand;
        $this->collapseButtonIconCollapse = $collapseButtonIconCollapse;
        $this->collapseButtonIconExpand = $collapseButtonIconExpand;
        $this->rememberCollapse = $rememberCollapse;
        $this->rememberCollapseKey = $rememberCollapseKey;
        $this->hoverMenuShowHeader = $hoverMenuShowHeader;
        $this->hoverMenuHeaderClass = $hoverMenuHeaderClass;
        $this->hoverMenuHeaderTextClass = $hoverMenuHeaderTextClass;

        $presets = config('beartropyui.presets.nav')['colors'];

        $preset = $presets[$color] ?? $presets['beartropy'];


        $this->highlightParentClass = $highlightParentClass ?? (
            $highlightMode === 'text'
            ? $preset['highlightParentText']
            : $preset['highlightParentStandard']
        );
        $this->highlightChildClass = $highlightChildClass ?? (
            $highlightMode === 'text'
            ? $preset['highlightChildText']
            : $preset['highlightChildStandard']
        );

        $this->itemClass = $itemClass ?? (
            $highlightMode === 'text'
            ? $preset['itemClassText']
            : $preset['itemClassStandard']
        );
        $this->childItemClass = $childItemClass ?? (
            $highlightMode === 'text'
            ? $preset['childItemClassText']
            : $preset['childItemClassStandard']
        );

        $this->hoverTextClass = $hoverTextClass ?? $preset['hoverText'];
        $this->hoverTextChildClass = $hoverTextChildClass ?? $preset['hoverTextChild'];

        $this->categoryClass = $categoryClass ?: ($preset['categoryClass'] ?? 'text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase px-3 mb-1 tracking-wide select-none');
        $this->iconClass = $iconClass ?: ($preset['iconClass'] ?? '');
        $this->childBorderClass = $childBorderClass ?: ($preset['childBorderClass'] ?? 'border-l border-gray-300 dark:border-gray-700');
        $resolved = $this->resolveItems($items);
        $this->items = $this->filterNavCategories($resolved);
        $this->withnavigate = $withnavigate;
    }




    /**
     * Resolve the navigation items source.
     *
     * @param mixed $items
     * @return array
     */
    protected function resolveItems($items): array
    {
        if (empty($items)) {
            return $this->loadConfigNav('default');
        }

        if (is_string($items)) {
            return $this->loadConfigNav($items);
        }

        if (is_array($items)) {
            return $items;
        }
        return [];
    }


    /**
     * Load navigation config file: config/beartropy/ui/navs/<nav>.php
     *
     * @param string $nav
     * @return array
     */
    protected function loadConfigNav(string $nav = 'default'): array
    {
        $path = config_path("beartropy/ui/navs/{$nav}.php");
        if (file_exists($path)) {
            return include $path;
        }

        return [];
    }

    /**
     * Determine if a navigation item is active based on current request.
     *
     * Checks path matches, route name matches, and recursive child activation.
     *
     * @param array $item
     * @return bool
     */
    public function isItemActive($item)
    {
        $request     = request();
        $route       = $request->route();
        $currentName = $route?->getName();
        // Normalize current path (without query)
        $currentPath = '/' . ltrim($request->path(), '/');

        // 1) match: PATH patterns
        if (!empty($item['match'])) {
            $patterns = is_array($item['match']) ? $item['match'] : [$item['match']];
            foreach ($patterns as $pattern) {
                if ($request->is(ltrim($pattern, '/'))) {
                    return true;
                }
            }
        }

        // 2) routeNameMatch: route name patterns (users.*)
        if (!empty($item['routeNameMatch'])) {
            $namePatterns = is_array($item['routeNameMatch']) ? $item['routeNameMatch'] : [$item['routeNameMatch']];
            foreach ($namePatterns as $pat) {
                if ($request->routeIs($pat)) {
                    return true;
                }
            }
        }

        // 3) routeName: exact route name match
        if (!empty($item['routeName'])) {
            // a) Direct name match (also supports wildcards)
            if ($currentName && ($currentName === $item['routeName'] || $request->routeIs($item['routeName']))) {
                return true;
            }

            // b) PATH fallback: generate relative URL and compare paths
            try {
                // false => relative (no domain); ignore querystrings
                $url = route($item['routeName'], $item['routeParams'] ?? [], false);
                $urlPath = '/' . ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
                if (rtrim($urlPath, '/') === rtrim($currentPath, '/')) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Non-existent route: ignore
            }
        }

        // 4) route: relative path/URL (no external http)
        if (!empty($item['route']) && (!isset($item['external']) || !$item['external'])) {
            if (is_string($item['route']) && !Str::startsWith($item['route'], ['http://', 'https://'])) {
                $itemPath = '/' . ltrim(parse_url($item['route'], PHP_URL_PATH) ?: $item['route'], '/');
                if (rtrim($itemPath, '/') === rtrim($currentPath, '/')) {
                    return true;
                }
                if ($request->is(ltrim($itemPath, '/'))) {
                    return true;
                }
            }
        }

        // 5) Any active child => parent active
        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if ($this->isItemActive($child)) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Render an icon for a navigation item.
     *
     * @param string $icon      Icon name or SVG string.
     * @param string $iconClass Extra classes.
     * @return string
     */
    public function renderIcon(string $icon, string $iconClass = ''): string
    {
        if (!$icon) {
            return '';
        }

        if (str_starts_with($icon, '<svg') || str_starts_with($icon, '<img') || str_starts_with($icon, '<i')) {
            return $icon;
        }

        $iconComponent = new \Beartropy\Ui\Components\Icon(name: $icon, class: $iconClass ?: 'w-4 h-4 shrink-0');
        return Blade::renderComponent($iconComponent);
    }

    /**
     * Filter navigation categories and their items based on permissions.
     *
     * @param array $categories
     * @param mixed $user
     * @return array
     */
    protected function filterNavCategories(array $categories, $user = null): array
    {
        // If passed a flat list of items (no categories),
        // wrap in an anonymous category to avoid breaking.
        $isCategories = !empty($categories) && array_key_exists('category', $categories[0] ?? []);

        if (!$isCategories) {
            return [[
                'category' => $categories['category'] ?? null,
                'items'    => $this->filterNavItems($categories, $user),
            ]];
        }

        $out = [];
        foreach ($categories as $cat) {
            $cat['items'] = $this->filterNavItems($cat['items'] ?? [], $user);
            if (!empty($cat['items'])) {
                $out[] = $cat;
            }
        }
        return $out;
    }


    /**
     * Filter a list of navigation items based on user permissions.
     *
     * @param array $items
     * @param mixed $user
     * @return array
     */
    public function filterNavItems($items, $user = null)
    {
        $user = $user ?: \Illuminate\Support\Facades\Auth::user();

        // --- helpers ---
        $isAdmin = function ($user) {
            if (!$user) return false;

            $roles = config('beartropyui.admin_roles', []);

            // Supports Spatie\HasRoles or a conventional boolean flag
            if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) {
                return true;
            }
            if (method_exists($user, 'hasRole')) {
                foreach ($roles as $r) {
                    if ($user->hasRole($r)) {
                        return true;
                    }
                }
            }
            if (property_exists($user, 'is_admin') && $user->is_admin) {
                return true;
            }

            return false;
        };

        $adminBypass = function () use ($user, $isAdmin) {
            return config('beartropyui.admin_bypass_nav', true) && $isAdmin($user);
        };

        // can(string|array): OR if array
        $canAccess = function ($can) use ($user, $adminBypass) {
            if ($adminBypass()) {
                return true;
            }
            if (!$can) {
                return true;
            }
            if (is_array($can)) {
                foreach ($can as $perm) {
                    if ($user && $user->can($perm)) {
                        return true;
                    }
                }
                return false;
            }
            return $user && $user->can($can);
        };

        // canAny(string|array): explicit OR
        $canAnyAccess = function ($canAny) use ($user, $adminBypass) {
            if ($adminBypass()) {
                return true;
            }
            if (!$canAny) {
                return true;
            }
            $list = is_array($canAny) ? $canAny : [$canAny];
            foreach ($list as $perm) {
                if ($user && $user->can($perm)) {
                    return true;
                }
            }
            return false;
        };

        // canMatch(string|array): wildcard against user permissions
        $canMatchAccess = function ($patterns) use ($user, $adminBypass) {
            if ($adminBypass()) {
                return true;
            }
            if (!$patterns) {
                return true;
            }
            if (!$user) {
                return false;
            }

            $patterns = is_array($patterns) ? $patterns : [$patterns];

            // Spatie: permission names
            /** @phpstan-ignore-next-line */
            $userPerms = method_exists($user, 'getAllPermissions')
                ? $user->getAllPermissions()->pluck('name')->all()
                : [];

            if (empty($userPerms)) {
                return false;
            }

            foreach ($patterns as $pat) {
                foreach ($userPerms as $permName) {
                    if (\Illuminate\Support\Str::is($pat, $permName)) {
                        return true;
                    }
                }
            }
            return false;
        };

        $filter = function ($items) use (&$filter, $canAccess, $canAnyAccess, $canMatchAccess) {
            $out = [];
            foreach ($items as $item) {
                if (isset($item['can'])      && !$canAccess($item['can'])) continue;
                if (isset($item['canAny'])   && !$canAnyAccess($item['canAny'])) continue;
                if (isset($item['canMatch']) && !$canMatchAccess($item['canMatch'])) continue;

                if (!empty($item['children'])) {
                    $item['children'] = $filter($item['children']);
                    if (empty($item['children'])) {
                        continue;
                    }
                }

                $out[] = $item;
            }
            return $out;
        };

        return $filter($items);
    }




    /**
     * Generate a unique ID for a navigation item.
     *
     * @param array $item
     * @return string
     */
    public function navId($item)
    {
        return md5(($item['routeName'] ?? '') . ($item['route'] ?? '') . ($item['label'] ?? '') . json_encode($item['children'] ?? []));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::nav');
    }
}
