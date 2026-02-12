# Menu

A data-driven navigation menu component that renders nested lists with section titles, icons, badges, and automatic active state detection. Colors are applied via presets — just pass a color name. Links use `wire:navigate` for Livewire SPA navigation.

## Basic Usage

```blade
<x-bt-menu :items="[
    ['url' => '/dashboard', 'label' => 'Dashboard'],
    ['url' => '/settings', 'label' => 'Settings'],
]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `array` | _(required)_ | Menu items array (see Item Shape below) |
| `color` | `string\|null` | `null` | Color preset name (resolved from presets) |
| `ul-class` | `string` | `'mt-4 space-y-2 ...'` | CSS classes for the `<ul>` wrapper |
| `li-class` | `string` | `'relative'` | CSS classes for `<li>` elements |
| `icon-class` | `string` | `'w-4 h-4 shrink-0'` | CSS classes for icon rendering |
| `mobile` | `bool` | `false` | Adds `p-2` padding for mobile layouts |

## Color Presets

Pass a color name as a magic attribute or through the `color` prop. Default is `orange`.

```blade
{{-- Magic attribute --}}
<x-bt-menu blue :items="$items" />
<x-bt-menu emerald :items="$items" />
<x-bt-menu beartropy :items="$items" />

{{-- Color prop --}}
<x-bt-menu color="purple" :items="$items" />
```

Available colors: `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Item Shape

Each item in the `items` array is an associative array. Items can be links, section titles, or nested groups:

```php
// Link
['url' => '/path', 'label' => 'Text']

// Link with icon
['url' => '/path', 'label' => 'Text', 'icon' => 'heroicon-o-home']

// Link with badge
['url' => '/path', 'label' => 'Text', 'badge' => ['text' => '5', 'class' => 'bg-red-100 text-red-600 ...']]

// Link with custom active route pattern
['url' => '/path', 'label' => 'Text', 'route' => 'path/*']

// Section title with nested items
['title' => 'Section Name', 'items' => [ ... ]]
```

## Sections with Titles

Group items under headings using `title` + `items`:

```blade
<x-bt-menu :items="[
    ['title' => 'General', 'items' => [
        ['url' => '/overview', 'label' => 'Overview'],
        ['url' => '/analytics', 'label' => 'Analytics'],
    ]],
    ['title' => 'Account', 'items' => [
        ['url' => '/settings', 'label' => 'Settings'],
        ['url' => '/billing', 'label' => 'Billing'],
    ]],
]" />
```

## Icons

Icons render through the shared Icon component, supporting Heroicons, Lucide, FontAwesome, and raw SVG:

```blade
<x-bt-menu :items="[
    ['url' => '#', 'label' => 'Home', 'icon' => 'heroicon-o-home'],
    ['url' => '#', 'label' => 'Search', 'icon' => 'heroicon-o-magnifying-glass'],
]" />
```

## Badges

Add notification counts or labels to items:

```blade
<x-bt-menu :items="[
    ['url' => '#', 'label' => 'Inbox', 'badge' => ['text' => '12', 'class' => 'text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full']],
]" />
```

## Active State

The component auto-detects the current page using `request()->is()`. By default it parses the path from the `url` key. Override with the `route` key for custom matching:

```php
// Matches any path under /admin
['url' => '/admin', 'label' => 'Admin', 'route' => 'admin*']
```

Active items get `aria-current="page"` and a screen-reader `(current)` label.

## Mobile Mode

Adds `p-2` root padding for use in mobile drawers:

```blade
<x-bt-menu :items="$items" :mobile="true" />
```

## Accessibility

- Semantic `<ul role="list">` with `<li>` items and `<h2>` section headings
- Active items include `aria-current="page"` and `<span class="sr-only">(current)</span>`
- Links use `wire:navigate` for client-side navigation

## Dark Mode

All preset colors include dark mode variants automatically.
