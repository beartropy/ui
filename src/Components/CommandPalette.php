<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Throw_;

/**
 * CommandPalette component.
 *
 * A searchable command palette for navigation and actions.
 * Supports loading items from an array or a JSON file.
 * Includes permission filtering via Spatie Permissions if available.
 *
 * @property string|null $color       Component color.
 * @property array|null  $items       Items definition array.
 * @property string|null $source      Legacy source path (unused).
 * @property string|null $src         JSON source path in storage/app.
 * @property bool        $allowGuests Allow guests to view items.
 */
class CommandPalette extends BeartropyComponent
{
    /** @var array Filtered items injected into the view. */
    public array $bt_cp_data = [];

    /**
     * Create a new CommandPalette component instance.
     *
     * @param string|null $color       Component color.
     * @param array|null  $items       Items definition array.
     * @param string|null $source      Legacy source path.
     * @param string|null $src         JSON source path.
     * @param bool        $allowGuests Allow guests to view items.
     */
    public function __construct(
        public $color = null,
        public $items = null,
        protected $source = null,
        protected $src = null,
        public bool $allowGuests = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * Calculates cache key based on user permissions and content version.
     * Caches the resulting filtered items for performance.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        // Clave de cache por usuario/roles/permisos + versión del contenido
        [$userKey, $version] = $this->cacheKeyParts();
        $srcKey = $this->src ? ('|src:' . ltrim($this->src, '/')) : '|inline';
        $cacheKey = "bt-cp:{$userKey}:v{$version}{$srcKey}";

        $this->bt_cp_data = Cache::remember($cacheKey, now()->addDay(), function () {
            $items = $this->resolveItems();             // Lee array o JSON
            $items = $this->filterByPermissions($items); // Filtra por permisos
            return $this->stripPermissions($items);     // Remueve 'permission'
        });

        return view('beartropy-ui::command-palette');
    }

    /**
     * Build cache key parts.
     *
     * - userKey: id + hash of roles/permissions (or "guest")
     * - version: file modification time (if src) or array hash (if items)
     *
     * @return array [userKey, version]
     */
    protected function cacheKeyParts(): array
    {
        // Versión por mtime del archivo o hash del array
        $version = '1';
        if ($this->src) {
            $path = ltrim($this->src, '/');
            $mtime = Storage::disk('local')->exists($path)
                ? Storage::disk('local')->lastModified($path)
                : time();
            $version = (string) $mtime;
        } elseif (is_array($this->items)) {
            $version = (string) crc32(json_encode($this->items));
        }

        // Usuario + roles/permisos
        $user = Auth::user();
        if (!$user) return ['guest', $version];

        /** @phpstan-ignore-next-line */
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->join(',') : '';
        /** @phpstan-ignore-next-line */
        $perms = method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name')->join(',') : '';
        $userKey = $user->getAuthIdentifier() . ':' . md5($roles . '|' . $perms);

        return [$userKey, $version];
    }

    /**
     * Resolve items from array or JSON file.
     *
     * @return array
     * @throws \Exception If JSON parsing fails.
     */
    protected function resolveItems(): array
    {
        if (is_array($this->items) && !empty($this->items)) {
            return $this->normalize($this->items);
        }

        if ($this->src) {
            $path = ltrim($this->src, '/');
            return $this->readJsonFromStorage($path);
        }

        return [];
    }

    /**
     * Read JSON file from storage.
     *
     * @param string $path File path relative to storage root.
     *
     * @return array
     * @throws \Exception If JSON parsing fails.
     */
    protected function readJsonFromStorage(string $path): array
    {
        if (!Storage::disk('local')->exists($path)) {
            return [];
        }

        $raw = Storage::disk('local')->get($path);
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("[Beartropy] Error parsing JSON in CommandPalette src '{$path}': " . json_last_error_msg());
        }

        return $this->normalize(is_array($data) ? $data : []);
    }

    /**
     * Normalize items to ensure all keys exist.
     *
     * @param array $items Raw items.
     *
     * @return array Normalized items.
     */
    protected function normalize(array $items): array
    {
        return array_values(array_map(function ($i) {
            return [
                'title'       => $i['title'] ?? '',
                'description' => $i['description'] ?? '',
                'tags'        => array_values(array_filter($i['tags'] ?? [], fn($t) => is_string($t) && $t !== '')),
                'action'      => $i['action'] ?? '',
                'permission'  => $i['permission'] ?? null,
                'roles'       => $i['roles'] ?? null,
                'target'      => $i['target'] ?? null,
            ];
        }, $items));
    }

    /**
     * Filter items by permissions (Spatie).
     *
     * - Guest: if $allowGuests => all; otherwise only items without 'permission'/'role'.
     * - Admin: all (if config admin_roles matches).
     * - User: checks 'permission' (can) and 'role' (hasAnyRole).
     *
     * @param array $items Normalized items.
     *
     * @return array Filtered items.
     */
    protected function filterByPermissions(array $items): array
    {
        $user = Auth::user();

        // Invitados
        if (!$user) {
            if ($this->allowGuests) return $items;

            // Solo ítems sin restricciones (ni permission ni role)
            return array_values(array_filter($items, function ($i) {
                return empty($i['permission']) && empty($i['role']);
            }));
        }

        // Bypass por admin_roles (Spatie hasAnyRole)
        $adminRoles = config('beartropy-ui.admin_roles', []);
        /** @phpstan-ignore-next-line */
        if (!empty($adminRoles) && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($adminRoles)) {
            return $items;
        }

        // Helper: match de permisos (string|array) con OR interno
        $matchesPermission = function ($perm) use ($user) {
            if (empty($perm)) return false; // no condiciona si no está
            if (is_array($perm)) {
                foreach ($perm as $p) {
                    if ($p && $user->can($p)) return true;
                }
                return false;
            }
            return $user->can($perm);
        };

        // Helper: match de roles (string|array) con OR interno (requiere Spatie)
        $matchesRole = function ($role) use ($user) {
            if (empty($role)) return false; // no condiciona si no está
            if (!method_exists($user, 'hasAnyRole')) return false; // sin Spatie, ignora roles
            if (is_array($role)) {
                /** @phpstan-ignore-next-line */
                return $user->hasAnyRole($role);
            }
            /** @phpstan-ignore-next-line */
            return $user->hasAnyRole([$role]);
        };

        // Regla final:
        // - Si no hay ni permission ni role => visible
        // - Si hay al menos uno => visible si (permission OK) OR (role OK)
        return array_values(array_filter($items, function ($i) use ($matchesPermission, $matchesRole) {
            $perm = $i['permission'] ?? null;
            $role = $i['role'] ?? null;

            if (empty($perm) && empty($role)) return true;

            return $matchesPermission($perm) || $matchesRole($role);
        }));
    }


    /**
     * Remove 'permission' key before sending to client.
     *
     * @param array $items Filtered items.
     *
     * @return array Items safe for public exposure.
     */
    protected function stripPermissions(array $items): array
    {
        return array_map(fn($i) => Arr::except($i, ['permission']), $items);
    }
}
