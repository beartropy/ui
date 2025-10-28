<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;

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
    ) {
        $this->sidebarBind = $sidebarBind;
        $this->highlightMode = $highlightMode;

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

        $this->categoryClass = $preset['categoryClass'] ?? $categoryClass;
        $this->iconClass = $preset['iconClass'] ?? $iconClass;
        $this->childBorderClass = $preset['childBorderClass'] ?? $childBorderClass;
        $this->items = $this->resolveItems($items);
    }




    /**
     * Decide de dónde sacar el array de navegación
     */
    protected function resolveItems($items)
    {
        // Si es null/empty, busca default
        if (empty($items)) {
            return $this->loadConfigNav('default');
        }

        // Si es string, busca navs/<string>.php
        if (is_string($items)) {
            return $this->loadConfigNav($items);
        }

        // Si es array, úsalo directo
        if (is_array($items)) {
            return $items;
        }

        // Fallback
        return [];
    }


    /**
     * Carga el archivo de navegación: config/beartropy/ui/navs/<nav>.php
     */
    protected function loadConfigNav($nav = 'default')
    {
        $path = config_path("beartropy/ui/navs/{$nav}.php");
        if (file_exists($path)) {
            return include $path;
        }

        return [];
    }

    public function isItemActive($item)
    {
        $request     = request();
        $route       = $request->route();
        $currentName = $route?->getName();
        // Normalizamos el path actual (sin query)
        $currentPath = '/'.ltrim($request->path(), '/');

        // 1) match: patrones de PATH (como antes)
        if (!empty($item['match'])) {
            $patterns = is_array($item['match']) ? $item['match'] : [$item['match']];
            foreach ($patterns as $pattern) {
                if ($request->is(ltrim($pattern, '/'))) {
                    return true;
                }
            }
        }

        // 2) routeNameMatch: patrones de NOMBRE de ruta (users.*)
        if (!empty($item['routeNameMatch'])) {
            $namePatterns = is_array($item['routeNameMatch']) ? $item['routeNameMatch'] : [$item['routeNameMatch']];
            foreach ($namePatterns as $pat) {
                if ($request->routeIs($pat)) {
                    return true;
                }
            }
        }

        // 3) routeName: nombre de ruta exacto
        if (!empty($item['routeName'])) {
            // a) Coincidencia directa por nombre (y soporta wildcards si las usan accidentalmente)
            if ($currentName && ($currentName === $item['routeName'] || $request->routeIs($item['routeName']))) {
                return true;
            }

            // b) Fallback por PATH: generamos la URL relativa y comparamos paths
            try {
                // false => genera relativa (sin dominio); ignoramos querystrings
                $url = route($item['routeName'], $item['routeParams'] ?? [], false);
                $urlPath = '/'.ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
                if (rtrim($urlPath, '/') === rtrim($currentPath, '/')) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ruta inexistente: ignoramos
            }
        }

        // 4) route: path/URL relativo (sin http externo) como antes
        if (!empty($item['route']) && (!isset($item['external']) || !$item['external'])) {
            if (is_string($item['route']) && !Str::startsWith($item['route'], ['http://', 'https://'])) {
                $itemPath = '/'.ltrim(parse_url($item['route'], PHP_URL_PATH) ?: $item['route'], '/');
                if (rtrim($itemPath, '/') === rtrim($currentPath, '/')) {
                    return true;
                }
                if ($request->is(ltrim($itemPath, '/'))) {
                    return true;
                }
            }
        }

        // 5) Algún hijo activo → padre activo
        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if ($this->isItemActive($child)) return true;
            }
        }

        return false;
    }


    public function renderIcon($icon, $iconClass = '')
    {
        if (!$icon) return '';
        if (str_starts_with($icon, '<svg') || str_starts_with($icon, '<img') || str_starts_with($icon, '<i')) {
            return $icon;
        } else{
            $iconComponent = new \Beartropy\Ui\Components\Icon(name: $icon, class: 'w-4 h-4 shrink-0');
            return Blade::renderComponent($iconComponent);
        }
        return '';
    }

    public static function filterNavItems($items, $user = null)
    {
        $user = $user ?: auth()->user();

        // can(string|array): OR si es array (comportamiento original)
        $canAccess = function ($can) use ($user) {
            if (!$can) return true;
            if (is_array($can)) {
                foreach ($can as $perm) {
                    if ($user && $user->can($perm)) return true;
                }
                return false;
            }
            return $user && $user->can($can);
        };

        // canAny(string|array): OR explícito
        $canAnyAccess = function ($canAny) use ($user) {
            if (!$canAny) return true;
            $list = is_array($canAny) ? $canAny : [$canAny];
            foreach ($list as $perm) {
                if ($user && $user->can($perm)) return true;
            }
            return false;
        };

        // canMatch(string|array): hace match por wildcard contra los permisos del usuario
        $canMatchAccess = function ($patterns) use ($user) {
            if (!$patterns) return true;
            if (!$user) return false;

            $patterns = is_array($patterns) ? $patterns : [$patterns];

            // Spatie: obtiene todos los permisos del usuario (names)
            // getAllPermissions() existe en HasRoles. Si no existiera, caemos a vacío.
            $userPerms = method_exists($user, 'getAllPermissions')
                ? $user->getAllPermissions()->pluck('name')->all()
                : [];

            if (empty($userPerms)) return false;

            foreach ($patterns as $pat) {
                // Si cualquiera de los permisos del user matchea el patrón, habilita.
                foreach ($userPerms as $permName) {
                    if (Str::is($pat, $permName)) {
                        return true;
                    }
                }
            }
            return false;
        };

        $filter = function ($items) use (&$filter, $canAccess, $canAnyAccess, $canMatchAccess) {
            $out = [];
            foreach ($items as $item) {
                // 1) can (igual que antes)
                if (isset($item['can']) && !$canAccess($item['can'])) continue;

                // 2) canAny (nuevo)
                if (isset($item['canAny']) && !$canAnyAccess($item['canAny'])) continue;

                // 3) canMatch (nuevo: wildcards)
                if (isset($item['canMatch']) && !$canMatchAccess($item['canMatch'])) continue;

                // 4) hijos (recursivo)
                if (!empty($item['children'])) {
                    $item['children'] = $filter($item['children']);
                    if (empty($item['children'])) continue;
                }

                $out[] = $item;
            }
            return $out;
        };

        return $filter($items);
    }



    public function navId($item) {
        return md5(($item['route'] ?? '') . ($item['label'] ?? '') . json_encode($item['children'] ?? []));
    }

    public function render()
    {
        return view('beartropy-ui::nav');
    }
}
