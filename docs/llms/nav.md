# x-bt-nav — AI Reference

## Component Tag
```blade
<x-bt-nav :items="$items" />
<x-bt-nav color="blue" :items="$items" :withnavigate="true" />
<x-bt-nav :items="$items" highlight-mode="text" :hide-categories="true" />
```

## Architecture
- `Nav` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`)
- Renders `nav.blade.php` — single template with inline Alpine.js (~280 lines)
- Colors resolved from `presets/nav.php` — NOT via `getComponentPresets()` (reads config directly)
- Two highlight modes: `standard` (bg highlight) and `text` (text-only highlight)
- Active state: PHP server-side (`isItemActive()`) + Alpine client-side (SPA navigation)
- Sidebar collapse with floating hover submenus via `x-teleport`
- Permission filtering via `can`/`canAny`/`canMatch` with admin bypass

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|-----------------|
| items | `array\|string\|null` | `null` | `:items="$items"` |
| color | `string` | `'beartropy'` | `color="blue"` |
| highlightMode | `string` | `'standard'` | `highlight-mode="text"` |
| sidebarBind | `string` | `'sidebarCollapsed'` | `sidebar-bind="!sidebarOpen"` |
| withnavigate | `bool` | `false` | `:withnavigate="true"` |
| hideCategories | `bool` | `false` | `:hide-categories="true"` |
| singleOpenExpanded | `bool` | `false` | `:single-open-expanded="true"` |
| collapseButtonAsItem | `bool` | `true` | `:collapse-button-as-item="false"` |
| collapseButtonLabelCollapse | `?string` | `null` | Localized "Collapse" |
| collapseButtonLabelExpand | `?string` | `null` | Localized "Expand" |
| collapseButtonIconCollapse | `string` | `'arrows-pointing-in'` | icon name |
| collapseButtonIconExpand | `string` | `'arrows-pointing-out'` | icon name |
| rememberCollapse | `?bool` | `null` | null = auto |
| rememberCollapseKey | `string` | `'beartropy:sidebar:collapsed'` | localStorage key |
| hoverMenuShowHeader | `bool` | `true` | `:hover-menu-show-header="false"` |
| highlightParentClass | `?string` | _(preset)_ | override preset |
| highlightChildClass | `?string` | _(preset)_ | override preset |
| itemClass | `?string` | _(preset)_ | override preset |
| childItemClass | `?string` | _(preset)_ | override preset |
| categoryClass | `string` | _(preset)_ | override preset |
| iconClass | `string` | `''` | `icon-class="w-5 h-5"` |
| childBorderClass | `string` | _(preset)_ | override preset |
| hoverTextClass | `?string` | _(preset)_ | text mode hover |
| hoverTextChildClass | `?string` | _(preset)_ | text mode child hover |
| hoverMenuHeaderClass | `string` | _(default)_ | floating header CSS |
| hoverMenuHeaderTextClass | `string` | _(default)_ | floating header text CSS |

## Color Preset Shape
```php
// Each color in presets/nav.php has 11 keys (two modes: standard + text):
'blue' => [
    'highlightParentStandard' => 'bg-blue-200/60 dark:bg-blue-700/40 ...',
    'highlightParentText'     => 'text-blue-600 dark:text-blue-400 font-semibold',
    'highlightChildStandard'  => 'bg-blue-100/60 dark:bg-blue-800/40 ...',
    'highlightChildText'      => 'text-blue-500 dark:text-blue-400',
    'itemClassStandard'       => 'flex items-center gap-2 ... hover:bg-blue-200/40 ...',
    'itemClassText'           => 'flex items-center gap-2 ... transition',
    'childItemClassStandard'  => 'flex items-center gap-2 ... hover:bg-blue-100/40 ...',
    'childItemClassText'      => 'flex items-center gap-2 ... transition pl-2',
    'hoverText'               => 'hover:text-blue-500 dark:hover:text-blue-300',
    'hoverTextChild'          => 'hover:text-blue-500 dark:hover:text-blue-300',
    'childBorderClass'        => '',
],
```

Available colors: `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Item Shape
```php
// Link (minimal)
['label' => 'Dashboard', 'route' => '/dashboard']

// Link (full)
['label' => 'Dashboard', 'route' => '/dashboard', 'icon' => 'home',
 'badge' => '5', 'tooltip' => 'Go home', 'class' => 'custom-class',
 'label_class' => 'font-bold', 'id' => 'nav-dashboard']

// Named route
['label' => 'Users', 'routeName' => 'users.index', 'routeParams' => ['status' => 'active']]

// Active state override
['label' => 'Admin', 'route' => '/admin', 'match' => 'admin/*']
['label' => 'Users', 'routeNameMatch' => 'users.*']

// Parent with children
['label' => 'Settings', 'icon' => 'cog', 'children' => [
    ['label' => 'Profile', 'route' => '/settings/profile'],
]]

// External link
['label' => 'Docs', 'route' => 'https://docs.example.com', 'external' => true]

// Permission gated
['label' => 'Admin', 'route' => '/admin', 'can' => 'admin-access']
['label' => 'Reports', 'canAny' => ['view-reports', 'manage-reports']]
['label' => 'Users', 'canMatch' => 'users.*']

// Disabled / Divider
['label' => 'Soon', 'route' => '#', 'disabled' => true]
['divider' => true]
```

## Category Structure
```php
// Categorized items
[
    ['category' => 'Main', 'items' => [...]],
    ['category' => 'Admin', 'items' => [...]],
]

// Flat items (auto-wrapped into anonymous category)
[
    ['label' => 'Dashboard', 'route' => '/dashboard'],
    ['label' => 'Settings', 'route' => '/settings'],
]
```

## Template Structure
```
nav[x-data][aria-label]
├── div.scrollable
│   └── @foreach categories
│       ├── div.category-heading (if !hideCategories)
│       └── @foreach items
│           ├── div.divider (if item.divider)
│           └── div
│               ├── a[data-nav-id][data-has-children]
│               │   ├── {!! icon !!}
│               │   ├── span.label (x-show="!sidebarIsCollapsed")
│               │   ├── span.badge (slot / Alpine x-text / static)
│               │   └── svg.chevron (if children, rotate-180 when open)
│               ├── div.inline-submenu (x-show, x-collapse)
│               │   └── @foreach children → a[data-href]
│               └── template[x-teleport="body"]  (floating submenu)
│                   └── div[role="menu"] → a[role="menuitem"]
└── div.collapse-button (if collapseButtonAsItem)
    └── button[@click toggle]
```

## Active State Detection (PHP)
```php
// 5-strategy detection in isItemActive():
1. match      → request()->is(pattern)     // path glob
2. routeNameMatch → request()->routeIs(pattern) // route name glob
3. routeName  → exact name match + URL path fallback
4. route      → relative path comparison
5. children   → recursive: any active child → parent active
```

## Alpine.js State
```js
{
    open: {},                    // {navId: bool} submenu open/close
    sidebarIsCollapsed: false,   // collapse state
    singleOpenExpanded: false,   // accordion mode
    remember: true,              // localStorage persistence
    rememberKey: '...',
    hoverId: null,               // floating submenu tracking
    submenuPos: {top, left, minWidth},
    activePath: '...',           // current URL path+search

    // Methods:
    toggle(id), isOpen(id),
    openHover(id, el), closeHoverSoon(), keepHoverOpen(),
    isActiveHref(href, mode), isActiveEl(el, startsWith),
    isActiveParent(el), parentMatches(el),
    updateActiveState(), openActiveBranches(),
    loadRemembered(), saveRemembered(v),
}
```

## Permission Filtering
```php
// Supports 3 permission types:
'can'      → Laravel Gate check (OR if array)
'canAny'   → explicit OR gate check
'canMatch' → wildcard match against Spatie permission names

// Admin bypass:
config('beartropyui.admin_bypass_nav', true)
config('beartropyui.admin_roles', [])
```

## Config-Based Items
```php
// Loads config/beartropy/ui/navs/{name}.php
<x-bt-nav />           // loads 'default'
<x-bt-nav items="admin" /> // loads 'admin'
```

## Common Patterns

```blade
{{-- Basic sidebar --}}
<x-bt-nav blue :items="$items" :withnavigate="true" />

{{-- Categorized --}}
<x-bt-nav :items="[
    ['category' => 'Main', 'items' => [
        ['label' => 'Dashboard', 'icon' => 'home', 'route' => '/dashboard'],
    ]],
]" />

{{-- Text highlight mode --}}
<x-bt-nav highlight-mode="text" :items="$items" />

{{-- Accordion + hide categories --}}
<x-bt-nav :items="$items" :single-open-expanded="true" :hide-categories="true" />

{{-- External sidebar bind --}}
<div x-data="{ sidebarOpen: true }">
    <x-bt-nav :items="$items" sidebar-bind="!sidebarOpen" />
</div>
```

## Key Notes
- Items can use `route` (relative path) OR `routeName` (Laravel named route) — not both
- `children` key (not `items`) for nested navigation
- Permission filtering happens server-side in constructor
- Active state: server-side detection in PHP + client-side reconciliation via Alpine
- Alpine patches `history.pushState`/`replaceState` for SPA active state tracking
- Collapse state persisted in `localStorage` (configurable key)
- Floating hover submenus appear when sidebar is collapsed (via `x-teleport`)
- Localized strings: `collapse`, `expand`, `submenu_for`, `sidebar_navigation` in `beartropy-ui::ui`
