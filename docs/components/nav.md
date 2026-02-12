# Nav

A full-featured sidebar navigation component with categories, nested children, collapsible sidebar, hover submenus, permission gating, and automatic active state detection. Powered by Alpine.js for collapse/expand behavior and client-side active state reconciliation. Colors are applied via presets.

## Basic Usage

```blade
<x-bt-nav :items="[
    ['label' => 'Dashboard', 'icon' => 'home', 'route' => '/dashboard'],
    ['label' => 'Settings', 'icon' => 'cog-6-tooth', 'route' => '/settings'],
]" />
```

## Props (Constructor)

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `array\|string\|null` | `null` | Menu items array, config name string, or null for default config |
| `color` | `string` | `'beartropy'` | Color preset name |
| `highlight-mode` | `string` | `'standard'` | `'standard'` (background) or `'text'` (text-only) highlight |
| `sidebar-bind` | `string` | `'sidebarCollapsed'` | Alpine variable name for external collapse binding |
| `withnavigate` | `bool` | `false` | Add `wire:navigate` to links for Livewire SPA navigation |
| `hide-categories` | `bool` | `false` | Hide category headings |
| `single-open-expanded` | `bool` | `false` | Accordion mode: only one group open at a time |
| `collapse-button-as-item` | `bool` | `true` | Show the collapse/expand footer button |
| `collapse-button-label-collapse` | `string\|null` | `null` | Custom collapse label (defaults to localized "Collapse") |
| `collapse-button-label-expand` | `string\|null` | `null` | Custom expand label (defaults to localized "Expand") |
| `collapse-button-icon-collapse` | `string` | `'arrows-pointing-in'` | Icon for collapse button |
| `collapse-button-icon-expand` | `string` | `'arrows-pointing-out'` | Icon for expand button |
| `remember-collapse` | `bool\|null` | `null` | Persist collapse state in localStorage (null = auto) |
| `remember-collapse-key` | `string` | `'beartropy:sidebar:collapsed'` | localStorage key |
| `hover-menu-show-header` | `bool` | `true` | Show header in floating hover submenus |

### Style Override Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `highlight-parent-class` | `string\|null` | _(from preset)_ | Active parent item classes |
| `highlight-child-class` | `string\|null` | _(from preset)_ | Active child item classes |
| `item-class` | `string\|null` | _(from preset)_ | Base parent item classes |
| `child-item-class` | `string\|null` | _(from preset)_ | Base child item classes |
| `category-class` | `string` | _(from preset)_ | Category heading classes |
| `icon-class` | `string` | `''` | Icon rendering classes |
| `child-border-class` | `string` | _(from preset)_ | Child container border classes |

## Color Presets

```blade
<x-bt-nav color="blue" :items="$items" />
<x-bt-nav color="emerald" :items="$items" />
```

Available colors: `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Highlight Modes

**Standard** (default): Active items get a colored background.

```blade
<x-bt-nav :items="$items" />
```

**Text**: Active items get colored text with hover effects.

```blade
<x-bt-nav :items="$items" highlight-mode="text" />
```

## Item Shape

```php
// Simple link
['label' => 'Dashboard', 'route' => '/dashboard', 'icon' => 'home']

// Link with named route
['label' => 'Users', 'routeName' => 'users.index', 'icon' => 'users']

// Named route with parameters
['label' => 'Edit User', 'routeName' => 'users.edit', 'routeParams' => ['user' => 1]]

// Parent with children
['label' => 'Settings', 'icon' => 'cog', 'children' => [
    ['label' => 'Profile', 'route' => '/settings/profile'],
    ['label' => 'Billing', 'route' => '/settings/billing'],
]]

// With badge
['label' => 'Inbox', 'route' => '/inbox', 'icon' => 'envelope', 'badge' => '12']

// With tooltip
['label' => 'Help', 'route' => '/help', 'tooltip' => 'Get assistance']

// External link
['label' => 'Docs', 'route' => 'https://docs.example.com', 'external' => true]

// Disabled
['label' => 'Coming Soon', 'route' => '#', 'disabled' => true]

// Divider
['divider' => true]

// With permission gating
['label' => 'Admin', 'route' => '/admin', 'can' => 'admin-access']
['label' => 'Reports', 'route' => '/reports', 'canAny' => ['view-reports', 'manage-reports']]
['label' => 'Users', 'route' => '/users', 'canMatch' => 'users.*']
```

## Categories

Group items under headings:

```blade
<x-bt-nav :items="[
    ['category' => 'Main', 'items' => [
        ['label' => 'Dashboard', 'icon' => 'home', 'route' => '/dashboard'],
    ]],
    ['category' => 'Admin', 'items' => [
        ['label' => 'Users', 'icon' => 'users', 'route' => '/users'],
    ]],
]" />
```

Flat arrays (without category wrapping) are also supported.

## Active State Detection

Active state is detected automatically using multiple strategies:

1. **`match`** — Path patterns via `request()->is()` (e.g., `'users/*'`)
2. **`routeNameMatch`** — Route name patterns (e.g., `'users.*'`)
3. **`routeName`** — Exact route name match with path fallback
4. **`route`** — Relative path comparison
5. **Child activation** — Parents become active when any child is active

Client-side active state is maintained by Alpine.js for SPA navigation (Livewire, History API).

## Permissions

Items can be gated by permissions:

```php
// Single permission (Laravel Gate)
['label' => 'Admin', 'route' => '/admin', 'can' => 'admin-access']

// Any of multiple permissions (OR)
['label' => 'Reports', 'route' => '/reports', 'canAny' => ['view-reports', 'manage-reports']]

// Wildcard matching (Spatie permissions)
['label' => 'Users', 'route' => '/users', 'canMatch' => 'users.*']
```

Admin bypass is configurable via `config('beartropyui.admin_bypass_nav')` and `config('beartropyui.admin_roles')`.

## Sidebar Collapse

The nav supports collapsing to icon-only mode with floating hover submenus:

```blade
{{-- Bind to external Alpine variable --}}
<x-bt-nav :items="$items" sidebar-bind="sidebarCollapsed" />

{{-- Negated binding --}}
<x-bt-nav :items="$items" sidebar-bind="!sidebarOpen" />

{{-- Disable collapse button --}}
<x-bt-nav :items="$items" :collapse-button-as-item="false" />

{{-- Accordion mode --}}
<x-bt-nav :items="$items" :single-open-expanded="true" />
```

## Config-Based Items

Load items from config files:

```blade
{{-- Loads config/beartropy/ui/navs/default.php --}}
<x-bt-nav />

{{-- Loads config/beartropy/ui/navs/admin.php --}}
<x-bt-nav items="admin" />
```

## Accessibility

- Semantic `<nav>` element with `aria-label`
- `aria-current="page"` on active items (via Alpine)
- `aria-expanded` on collapsible parent items
- Floating submenus have `role="menu"` with `role="menuitem"` children
- Localized collapse/expand button labels

## Dark Mode

All preset colors include dark mode variants automatically.
