# x-bt-menu — AI Reference

## Component Tag
```blade
<x-bt-menu :items="$items" />
<x-bt-menu blue :items="$items" :mobile="true" />
<x-bt-menu color="emerald" :items="$items" ul-class="space-y-1" />
```

## Architecture
- `Menu` extends `BeartropyComponent` (uses `HasPresets` trait)
- Renders `menu.blade.php` — recursive template (calls itself for nested items)
- Colors resolved via `presets/menu.php` — default `orange`
- Icons render via `renderIcon()` method using the shared `Icon` component
- Links use `wire:navigate` for Livewire SPA navigation
- Active state detected via `request()->is()`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| items | `array` | _(required)_ | `:items="$items"` |
| color | `?string` | `null` | `color="blue"` or magic `blue` |
| ulClass | `string` | `'mt-4 space-y-2 ...'` | `ul-class="..."` |
| liClass | `string` | `'relative'` | `li-class="..."` |

## Props (view-only via @props)

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| iconClass | `string` | `'w-4 h-4 shrink-0'` | CSS for icon wrapper |
| level | `int` | `0` | Recursion depth (internal) |
| mobile | `bool` | `false` | Mobile padding mode |

## Color Preset Shape
```php
// Each color in presets/menu.php has 3 keys:
'blue' => [
    'title'  => 'font-medium text-blue-500 font-display dark:text-blue-400',
    'item'   => 'transition inline-flex items-center gap-x-2 text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400',
    'active' => 'text-blue-500 dark:text-blue-400 font-semibold',
],
```

Available colors: `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Item Shape
```php
// Link (minimal)
['url' => '/path', 'label' => 'Text']

// Link (full)
['url' => '/path', 'label' => 'Text', 'icon' => 'heroicon-o-home', 'route' => 'path/*',
 'badge' => ['text' => '5', 'class' => 'bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full']]

// Section with nested items
['title' => 'Section', 'items' => [ ... ]]
```

## Template Structure
```
ul[role="list"]
├── li
│   ├── h2 (if node.title)
│   ├── <x-bt-menu> (if node.items — recursive, level+1)
│   └── a[wire:navigate] (if node.url + node.label)
│       ├── {!! icon !!} (if node.icon)
│       ├── {{ label }}
│       ├── span.badge (if node.badge)
│       └── span.sr-only "(current)" (if active)
```

## Icon Rendering
- `renderIcon()` method on the `Menu` PHP class
- Supports raw HTML (`<svg>`, `<img>`, `<i>` tags) — passed through as-is
- Supports icon names (`heroicon-o-home`, `home`, etc.) — rendered via `Icon` component
- Uses `Blade::renderComponent()` for Blade icon components

## Active State Detection
```php
$pattern  = $node['route'] ?? ltrim(parse_url($node['url'], PHP_URL_PATH), '/');
$isActive = request()->is($pattern ?: '/');
```
- Override with `route` key for wildcard patterns (e.g., `'admin*'`)
- Active links get: preset `active` class, `aria-current="page"`, `<span class="sr-only">(current)</span>`

## Nesting
- Level 0: root `<ul>` with `$ulClass`
- Level 1+: nested `<ul>` with `ml-4 border-l border-slate-200 dark:border-slate-700 pl-2`
- Level 0 titles: `mt-2 mb-1`
- Level 1+ titles: `mt-1 mb-1 text-xs uppercase tracking-widest opacity-80`
- Color is passed down to nested instances via `:color="$color"`

## Common Patterns

```blade
{{-- Basic with color --}}
<x-bt-menu blue :items="[
    ['url' => '/home', 'label' => 'Home', 'icon' => 'heroicon-o-home'],
    ['url' => '/about', 'label' => 'About'],
]" />

{{-- Grouped sections --}}
<x-bt-menu emerald :items="[
    ['title' => 'General', 'items' => [
        ['url' => '/overview', 'label' => 'Overview'],
    ]],
    ['title' => 'Admin', 'items' => [
        ['url' => '/users', 'label' => 'Users'],
    ]],
]" />

{{-- Mobile sidebar --}}
<x-bt-menu beartropy :items="$items" :mobile="true" />
```

## Key Notes
- Constructor `items` is required (no default)
- Colors resolved via `HasPresets` — pass color as magic attribute or `color` prop
- Default color is `orange` (set in presets file)
- Recursive: nested `items` arrays create sub-`<ul>` at level+1, color propagates
- Icons use the shared `Icon` component (Heroicons, Lucide, FontAwesome, raw SVG)
- `wire:navigate` on all links for Livewire SPA behavior
- Mobile mode adds `p-2` to root `<ul>` only
- `(current)` sr-only text is localized via `beartropy-ui::ui.current`
