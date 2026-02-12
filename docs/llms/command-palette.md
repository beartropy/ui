# x-bt-command-palette — AI Reference

## Component Tag
```blade
<x-bt-command-palette :items="$items" />
<x-bt-command-palette color="blue" :items="$items" />
<x-bt-command-palette src="palette.json" :allow-guests="true" />
<x-bt-command-palette :items="$items"><button>Open</button></x-bt-command-palette>
```

## Architecture
- `CommandPalette` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`)
- Renders `command-palette.blade.php` — single template with inline `<script>` Alpine component
- Modal overlay via `x-teleport="body"` with `x-cloak`
- Colors resolved via `$getComponentPresets('command-palette')` from `presets/command-palette.php`
- Items cached per-user via `Cache::remember()` keyed by auth ID + roles/permissions hash
- Permission filtering: `permission` (Laravel Gate), `roles` (Spatie hasAnyRole), admin bypass
- Client-side search: multi-term fuzzy filter across title, description, tags, action

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|-----------------|
| color | `string\|null` | `null` | `color="blue"` |
| items | `array\|null` | `null` | `:items="$items"` |
| source | `string\|null` | `null` | _(legacy, unused)_ |
| src | `string\|null` | `null` | `src="palette.json"` |
| allowGuests | `bool` | `false` | `:allow-guests="true"` |

## View Properties
| Property | Type | Description |
|----------|------|-------------|
| bt_cp_data | `array` | Filtered, permission-stripped items passed to Alpine |

## Color Preset Shape
```php
// Each color in presets/command-palette.php has 4 keys:
'blue' => [
    'modal_bg'    => 'bg-blue-50/80 dark:bg-blue-950/80',
    'text'        => 'text-blue-900 dark:text-blue-100',
    'hover_bg'    => 'hover:bg-blue-100/60 dark:hover:bg-blue-800/60',
    'selected_bg' => 'bg-blue-500/10 dark:bg-blue-400/20 ring-1 ring-blue-400/30',
],
```

Available colors: `beartropy`, `neutral`, `blue`, `violet`, `emerald`, `red`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `teal`, `cyan`, `sky`, `indigo`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`.

## Item Shape (PHP)
```php
[
    'title'       => 'Dashboard',       // Display title
    'description' => 'Go to dashboard', // Subtitle
    'action'      => '/dashboard',      // Action string
    'tags'        => ['nav', 'home'],   // Searchable/clickable tags
    'permission'  => 'view-dash',       // Spatie permission (string|array, OR logic)
    'roles'       => 'admin',           // Spatie role (string|array, OR logic)
    'target'      => '_blank',          // Link target
]
```

## Item Shape (Client — after stripPermissions)
```js
{
    title: 'Dashboard',
    description: 'Go to dashboard',
    action: '/dashboard',
    tags: ['nav', 'home'],
    target: '_blank'  // or null
}
```

## Action Types
```
route:dashboard       → Ziggy route() or fallback route map
url:/settings         → Navigate to URL
/dashboard            → Auto-detected relative URL
https://example.com   → Auto-detected absolute URL
dispatch:theme-toggle → Alpine $dispatch()
js:alert('hi')        → eval() execution
```

## Template Structure
```
div[x-data="btCommandPalette"][Cmd+K/Ctrl+K]
├── div[@click="open = true"]           (trigger)
│   ├── slot content (if provided)
│   └── x-beartropy-ui::input (default)
└── template[x-teleport="body"]
    └── div.overlay[x-show="open"][x-cloak][role="dialog"]
        └── div.panel[modal_bg][@click.outside]
            ├── div.search-header
            │   └── x-beartropy-ui::input[x-model="query"]
            └── ul[role="listbox"]
                ├── template[x-for="filtered"]
                │   └── li[role="option"][@click="execute(item)"]
                │       ├── span.title[x-text]
                │       ├── div.tags[x-for → span[@click.stop="query = tag"]]
                │       └── div.description[x-text]
                ├── li "No results." (when filtered.length === 0)
                └── li "Showing first 5 results" (when !query && length === 5)
```

## Alpine.js State
```js
{
    open: false,          // Modal visibility
    query: '',            // Search input value
    all: [],              // All items (from bt_cp_data)
    selectedIndex: 0,     // Keyboard-selected item index

    // Computed:
    get filtered()        // Multi-term search across title/desc/tags/action
                          // Returns all.slice(0,5) when no query

    // Methods:
    scrollIntoView()      // Scroll selected item into view
    handleKey(e)          // Arrow/Tab/Enter keyboard handler
    execute(item)         // Dispatch action by type prefix
}
```

## Permission Filtering (PHP)
```php
// In filterByPermissions():
// 1. Guest + !allowGuests → only items without permission/roles
// 2. Guest + allowGuests → all items
// 3. Admin bypass: config('beartropyui.admin_roles') + hasAnyRole → all items
// 4. Normal user: visible if (permission matches via can()) OR (role matches via hasAnyRole())
// 5. Items with neither permission nor roles → always visible
```

## Caching
```php
// Cache key format: "bt-cp:{userKey}:v{version}{srcKey}"
// userKey: "{userId}:{md5(roles|perms)}" or "guest"
// version: file mtime (src) or crc32(json_encode(items))
// srcKey: "|src:path" or "|inline"
// TTL: 1 day
```

## Common Patterns

```blade
{{-- Basic with inline items --}}
<x-bt-command-palette :items="[
    ['title' => 'Dashboard', 'action' => '/dashboard', 'tags' => ['nav']],
]" />

{{-- Blue color from JSON file --}}
<x-bt-command-palette color="blue" src="palette.json" />

{{-- Custom trigger button --}}
<x-bt-command-palette :items="$items">
    <button>Open Palette</button>
</x-bt-command-palette>

{{-- With permission-gated items --}}
<x-bt-command-palette :items="[
    ['title' => 'Public', 'action' => '/'],
    ['title' => 'Admin', 'action' => '/admin', 'permission' => 'admin-access'],
    ['title' => 'Editor', 'action' => '/edit', 'roles' => ['editor', 'admin']],
]" />

{{-- Allow all items for guests --}}
<x-bt-command-palette :items="$items" :allow-guests="true" />
```

## Key Notes
- Items use `action` (not `route` or `url`) as the primary action field
- Tags are clickable — clicking a tag sets it as the search query
- `permission` and `roles` are stripped before sending to client (security)
- `roles` key (plural) — matches `normalize()` output; supports string or array
- Admin bypass uses `config('beartropyui.admin_roles')` (no hyphen in config namespace)
- Default shows first 5 items when no search query; all matches when searching
- Selected item uses `selected_bg` from color preset (not hardcoded)
- Modal uses `x-cloak` to prevent flash-of-content on page load
