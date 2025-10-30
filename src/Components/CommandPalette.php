<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Throw_;

class CommandPalette extends BeartropyComponent
{
    /** Ítems ya filtrados (inyectados al Blade) */
    public array $bt_cp_data = [];

    public function __construct(
        public $color = null,
        public $items = null,          // array opcional (ya en memoria)
        protected $source = null,      // legacy (sin uso)
        protected $src = null,         // ruta JSON en storage/app (NO público)
        public bool $allowGuests = false // opcional: en landing/docs permitir todo
    ) {}

    public function render()
    {
        // Clave de cache por usuario/roles/permisos + versión del contenido
        [$userKey, $version] = $this->cacheKeyParts();
        $srcKey = $this->src ? ('|src:'.ltrim($this->src, '/')) : '|inline';
        $cacheKey = "bt-cp:{$userKey}:v{$version}{$srcKey}";

        $this->bt_cp_data = Cache::remember($cacheKey, now()->addDay(), function () {
            $items = $this->resolveItems();             // Lee array o JSON
            $items = $this->filterByPermissions($items);// Filtra por permisos
            return $this->stripPermissions($items);     // Remueve 'permission'
        });

        return view('beartropy-ui::command-palette');
    }

    /**
     * Construye partes de la cache key:
     *  - userKey: id + hash de roles/permisos (o "guest")
     *  - version: mtime del archivo (si src) o hash del array (si items)
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

        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->join(',') : '';
        $perms = method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->pluck('name')->join(',') : '';
        $userKey = $user->getAuthIdentifier() . ':' . md5($roles . '|' . $perms);

        return [$userKey, $version];
    }

    /** Lee ítems desde $items (array) o $src (JSON en storage/app). */
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

    /** Normaliza para evitar notices. */
    protected function normalize(array $items): array
    {
        return array_values(array_map(function ($i) {
            return [
                'title'       => $i['title'] ?? '',
                'description' => $i['description'] ?? '',
                'tags'        => array_values(array_filter($i['tags'] ?? [], fn($t) => is_string($t) && $t !== '')),
                'action'      => $i['action'] ?? '',
                'permission'  => $i['permission'] ?? null, // se eliminará luego
            ];
        }, $items));
    }

    /**
     * Filtrado por permisos (Spatie):
     * - Invitado: si $allowGuests => todo; si no, solo sin 'permission'
     * - Admin: todo
     * - String: user->can
     * - Array: canAny
     */
    protected function filterByPermissions(array $items): array
    {
        $user = Auth::user();

        if (!$user) {
            if ($this->allowGuests) return $items;
            return array_values(array_filter($items, fn($i) => empty($i['permission'])));
        }

        // 🔹 NUEVO: admin_roles desde config
        $adminRoles = config('beartropy-ui.admin_roles', []);
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($adminRoles)) {
            return $items;
        }

        $can = function ($perm) use ($user) {
            if (empty($perm)) return true;
            if (is_array($perm)) {
                foreach ($perm as $p) {
                    if ($p && $user->can($p)) return true;
                }
                return false;
            }
            return $user->can($perm);
        };

        return array_values(array_filter($items, fn($i) => $can($i['permission'] ?? null)));
    }

    /** Remueve 'permission' antes de enviar al cliente. */
    protected function stripPermissions(array $items): array
    {
        return array_map(fn($i) => Arr::except($i, ['permission']), $items);
    }
}
