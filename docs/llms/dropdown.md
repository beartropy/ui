# x-bt-dropdown — AI Reference

## Component Tag
```blade
<x-bt-dropdown>
    <x-slot:trigger>...</x-slot:trigger>
    <x-bt-dropdown.item href="/url">Label</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Architecture
- `Dropdown` → extends `BeartropyComponent`
- View: `dropdown.blade.php`
- Two rendering modes controlled by Blade prop `usePortal` (default `true`):
  - **Portal**: `x-teleport="body"` with `position:fixed`, Alpine.js inline positioning (`_reposition`, `_computeLeft`, `_computeTop`)
  - **Classic**: delegates to `DropdownBase` (`x-beartropy-ui::base.dropdown-base`) with relative positioning via `x-anchor`
- Partials: `partials/dropdown/item.blade.php`, `partials/dropdown/header.blade.php`, `partials/dropdown/separator.blade.php`
- Preset: `dropdown` (colors only, no sizes — width via `dropdownWidth` key or direct `width` prop)

## Props (Blade @props)

| Prop | Type | Default | Notes |
|------|------|---------|-------|
| `side` | `string` | `'bottom'` | `'bottom'` or `'top'` |
| `placement` | `string` | `'left'` | `'left'`, `'center'`, `'right'` |
| `usePortal` | `bool` | `true` | Portal vs classic mode |
| `autoFit` | `bool` | `true` | Only effective when `maxHeight != null` |
| `autoFlip` | `bool` | `true` | Flip top/bottom based on viewport space |
| `maxHeight` | `int\|null` | `null` | `null` → no overflow/scroll ever |
| `overflowMode` | `string` | `'auto'` | `'auto'`, `'scroll'`, `'visible'` |
| `flipAt` | `int` | `96` | Flip threshold in px |
| `minPanel` | `int` | `140` | Minimum panel height in px |
| `zIndex` | `string` | `'z-[99999999]'` | Tailwind z-index class |
| `width` | `string\|null` | `null` | Override width class; null → preset `dropdownWidth` or `'min-w-[12rem]'` |

### Constructor Props (Dropdown.php)

| Prop | PHP Type | Default |
|------|----------|---------|
| `placement` | `string` | `'bottom'` |
| `side` | `string` | `'left'` |
| `color` | `?string` | `null` |
| `size` | `?string` | `null` |
| `withnavigate` | `?bool` | `null` |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Menu body (items, headers, separators) |
| `trigger` | Clickable trigger element |

## Alpine State (Portal Mode)

```js
{
    open: false,
    autoFit, autoFlip, maxHeight, overflowMode,
    flipAt, minPanel, zIndex, widthClass,
    allowOverflow,  // true only when maxHeight != null
    sideLocal,      // 'top' or 'bottom', may flip at runtime
    hasOverflow,    // runtime: scrollHeight > clientHeight
    maxStyle,       // runtime: 'max-height:Npx;'
    coords,         // trigger bounding rect
    panelW,         // panel width
}
```

### Key Methods
- `_measure()` — reads trigger `getBoundingClientRect()`
- `_reposition()` — computes flip, maxHeight, overflow
- `_computeLeft()` — horizontal position from placement + edge clamping
- `_computeTop()` — vertical position based on `sideLocal`
- `_bindListeners()` — resize/scroll handlers + ResizeObserver for overflow

## Preset Structure (dropdown.php)

```php
'colors' => [
    'neutral' => [
        'dropdown_bg'               => 'bg-white dark:bg-gray-900',
        'dropdown_border'           => 'border border-neutral-300 dark:border-neutral-600',
        'dropdown_shadow'           => 'shadow-lg',
        'item_hover_bg'             => 'hover:bg-neutral-50 dark:hover:bg-neutral-900',
        'item_active_bg'            => 'active:bg-neutral-100 dark:active:bg-neutral-800',
        'item_text_color'           => 'text-neutral-700 dark:text-neutral-300',
        'item_disabled_text_color'  => 'text-neutral-400 dark:text-neutral-600',
    ],
    // ... beartropy, red, orange, amber, yellow, lime, green, emerald, teal,
    //     cyan, sky, blue, indigo, violet, purple, fuchsia, pink, rose,
    //     slate, gray, zinc, stone
]
```

## Partials

### `dropdown/item.blade.php`
- Uses `@aware(['color', 'withnavigate'])` to inherit parent values
- `as` prop: `'a'` (default) or `'button'`
- Link items get `wire:navigate` when `withnavigate` is true
- `closeOnClick` (default true) → dispatches `bt-dropdown-close` event
- Disabled: `aria-disabled=true` + `opacity-50 cursor-not-allowed` (link) or `disabled` attr (button)
- Reads preset via `config("beartropyui.presets.dropdown.colors.{$color}")`

### `dropdown/header.blade.php`
- `role="presentation"`, uppercase, small text
- `muted` prop (default true) controls text color

### `dropdown/separator.blade.php`
- `<hr>` with `border-gray-200 dark:border-gray-700`

## DropdownBase (Classic Mode)

When `usePortal=false`, the Blade template renders `x-beartropy-ui::base.dropdown-base` instead of the portal logic. DropdownBase is a full-featured base component with:
- Relative positioning via `x-anchor`
- Desktop/tablet + mobile modes (center/sheet/fullscreen)
- Its own teleport option, auto-flip, auto-fit
- `bind` prop to control which Alpine boolean controls visibility

## Events
- `bt-dropdown-close` — window event dispatched by items; triggers `open = false`
- Escape key: `@keydown.escape.window="open = false"`
- Click away: `@click.away="open = false"` (outer wrapper) + `@click.outside="open = false"` (portal panel)

## Common Patterns

```blade
{{-- Basic dropdown --}}
<x-bt-dropdown>
    <x-slot:trigger><x-bt-button>Actions</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.header>File</x-bt-dropdown.header>
    <x-bt-dropdown.item href="/new">New</x-bt-dropdown.item>
    <x-bt-dropdown.item href="/open">Open</x-bt-dropdown.item>
    <x-bt-dropdown.separator />
    <x-bt-dropdown.item as="button" @click="confirmDelete">Delete</x-bt-dropdown.item>
</x-bt-dropdown>

{{-- Right-aligned, top side --}}
<x-bt-dropdown placement="right" side="top">
    <x-slot:trigger><x-bt-button>Menu</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/test">Item</x-bt-dropdown.item>
</x-bt-dropdown>

{{-- Classic mode (no teleport) --}}
<x-bt-dropdown :usePortal="false">
    <x-slot:trigger><x-bt-button>Classic</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/test">Item</x-bt-dropdown.item>
</x-bt-dropdown>

{{-- Scrollable with max height --}}
<x-bt-dropdown :maxHeight="250" overflowMode="auto">
    <x-slot:trigger><x-bt-button>Long List</x-bt-button></x-slot:trigger>
    {{-- many items --}}
</x-bt-dropdown>

{{-- Colored --}}
<x-bt-dropdown color="blue">
    <x-slot:trigger><x-bt-button>Blue</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/test">Blue Item</x-bt-dropdown.item>
</x-bt-dropdown>

{{-- wire:navigate on all items --}}
<x-bt-dropdown :withnavigate="true">
    <x-slot:trigger><x-bt-button>Nav</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/page">Page</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Key Notes
- Portal mode escapes parent `overflow:hidden` and stacking contexts — preferred for dropdowns inside cards/modals
- `maxHeight=null` means no overflow/scroll ever; set a value to enable scrollable behavior
- Items use `@aware` to inherit `color` and `withnavigate` from the parent Dropdown
- The `open` Alpine boolean lives on the outer wrapper div, shared via scope with both trigger and portal panel
- Portal panel uses `position:fixed` with computed `top`/`left` via `_computeTop()`/`_computeLeft()`
