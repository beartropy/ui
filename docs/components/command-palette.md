# Command Palette

A searchable command palette for navigation and actions. Opens via `Cmd+K` / `Ctrl+K` or a click trigger. Renders as a modal overlay with Alpine.js keyboard navigation, fuzzy multi-term search, and color presets. Items can be loaded from a PHP array or a JSON file in storage. Supports permission/role filtering via Spatie Permissions.

## Basic Usage

```blade
<x-bt-command-palette :items="[
    ['title' => 'Dashboard', 'description' => 'Go to dashboard', 'action' => '/dashboard', 'tags' => ['nav']],
    ['title' => 'Settings', 'description' => 'App settings', 'action' => '/settings', 'tags' => ['config']],
]" />
```

## Props (Constructor)

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `color` | `string\|null` | `null` | Color preset name (`beartropy`, `blue`, `emerald`, etc.) |
| `items` | `array\|null` | `null` | Items definition array |
| `src` | `string\|null` | `null` | Path to JSON file in `storage/app` |
| `allow-guests` | `bool` | `false` | Allow unauthenticated users to see all items |

## Item Shape

```php
[
    'title'       => 'Dashboard',           // Required: display title
    'description' => 'Go to main dashboard', // Optional: subtitle
    'action'      => '/dashboard',           // Action string (see Action Types)
    'tags'        => ['nav', 'home'],        // Optional: searchable tags (clickable)
    'permission'  => 'view-dashboard',       // Optional: Spatie permission gate
    'roles'       => 'admin',               // Optional: Spatie role(s) (string or array)
    'target'      => '_blank',              // Optional: '_blank' for new tab
]
```

## Action Types

| Prefix | Example | Behavior |
|--------|---------|----------|
| `route:` | `route:dashboard` | Resolve via Ziggy `route()` or fallback route map |
| `url:` | `url:/settings` | Navigate to URL |
| _(path)_ | `/dashboard` or `https://...` | Direct navigation (auto-detected) |
| `dispatch:` | `dispatch:theme-toggle` | Dispatch Alpine event |
| `js:` | `js:alert('hi')` | Execute JavaScript |

## Color Presets

```blade
<x-bt-command-palette color="blue" :items="$items" />
<x-bt-command-palette color="emerald" :items="$items" />
<x-bt-command-palette color="violet" :items="$items" />
```

Available colors: `beartropy`, `neutral`, `blue`, `violet`, `emerald`, `red`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `teal`, `cyan`, `sky`, `indigo`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`.

Each color controls: modal background, text color, hover highlight, and selected item highlight.

## JSON Source

Load items from a JSON file in `storage/app`:

```blade
<x-bt-command-palette src="command-palette.json" />
```

```json
[
    {"title": "Dashboard", "description": "Main dashboard", "action": "/dashboard", "tags": ["nav"]},
    {"title": "Users", "description": "User management", "action": "/users", "permission": "manage-users"}
]
```

## Custom Trigger

Replace the default search input with a custom trigger:

```blade
<x-bt-command-palette :items="$items">
    <button class="btn">Open Palette</button>
</x-bt-command-palette>
```

## Permission Filtering

Items support `permission` and `roles` keys for server-side filtering:

```php
$items = [
    ['title' => 'Public', 'action' => '/'],
    ['title' => 'Admin Panel', 'action' => '/admin', 'permission' => 'admin-access'],
    ['title' => 'Editor Tools', 'action' => '/editor', 'roles' => ['editor', 'admin']],
    ['title' => 'Reports', 'action' => '/reports', 'permission' => ['view-reports', 'manage-reports']],
];
```

**Filtering rules:**
- **Guest (no auth):** Only items without `permission`/`roles` (unless `allow-guests` is true)
- **Admin bypass:** Users with roles in `config('beartropyui.admin_roles')` see all items
- **Normal user:** Items visible if permission OR role matches

Permission and role keys are stripped before sending data to the client.

## Caching

Items are cached per-user (based on auth ID + roles/permissions hash) for one day. Cache key includes content version (file mtime for `src`, array hash for `items`).

## Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `Cmd+K` / `Ctrl+K` | Open palette |
| `Escape` | Close palette |
| `Arrow Down` / `Tab` | Next item |
| `Arrow Up` / `Shift+Tab` | Previous item |
| `Enter` | Execute selected item |

## Accessibility

- Modal overlay: `role="dialog"`, `aria-modal="true"`, `aria-label`
- Results list: `role="listbox"`, `aria-label`
- Each item: `role="option"`, `:aria-selected`
- Auto-focus on search input when opened
- `x-cloak` prevents flash of modal content
