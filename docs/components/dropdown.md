# Dropdown

A click-triggered menu component with two rendering modes: **portal** (default) and **classic**.

## Basic Usage

```blade
<x-bt-dropdown>
    <x-slot:trigger>
        <x-bt-button>Options</x-bt-button>
    </x-slot:trigger>

    <x-bt-dropdown.item href="/edit">Edit</x-bt-dropdown.item>
    <x-bt-dropdown.separator />
    <x-bt-dropdown.item href="/delete" as="button">Delete</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `side` | `string` | `bottom` | Vertical side: `bottom` or `top` |
| `placement` | `string` | `left` | Horizontal alignment: `left`, `center`, `right` |
| `usePortal` | `bool` | `true` | `true` = fixed-position panel teleported to `<body>`; `false` = classic relative positioning |
| `autoFit` | `bool` | `true` | Adapt panel height to viewport (only when `maxHeight` is set) |
| `autoFlip` | `bool` | `true` | Flip top/bottom when space is insufficient |
| `maxHeight` | `int\|null` | `null` | Maximum panel height in px (`null` = no overflow/scroll) |
| `overflowMode` | `string` | `auto` | Overflow behavior when `maxHeight` is set: `auto`, `scroll`, `visible` |
| `flipAt` | `int` | `96` | Threshold in px to trigger flip |
| `minPanel` | `int` | `140` | Minimum panel height in px |
| `zIndex` | `string` | `z-[99999999]` | Tailwind z-index class for portal panel |
| `width` | `string\|null` | `null` | Width class (e.g. `w-64`); if null, uses preset default |
| `color` | `string\|null` | `null` | Color preset name |
| `withnavigate` | `bool\|null` | `null` | Add `wire:navigate` to dropdown items |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Menu body content (items, headers, separators) |
| `trigger` | Clickable element that toggles the dropdown |

## Rendering Modes

### Portal (default)

Teleports the panel to `<body>` with `position: fixed`, escaping parent `overflow: hidden` and stacking contexts. Handles auto-flip, auto-fit, and horizontal edge clamping via Alpine.js.

### Classic

Set `:usePortal="false"` to use `DropdownBase` with relative positioning anchored to the trigger:

```blade
<x-bt-dropdown :usePortal="false">
    <x-slot:trigger><x-bt-button>Menu</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/test">Item</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Sub-Components

### `x-bt-dropdown.item`

A menu item rendered as a link (`<a>`) or button.

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `as` | `string` | `a` | Element tag: `a` or `button` |
| `icon` | `string\|null` | `null` | Heroicon name for a leading icon |
| `disabled` | `bool` | `false` | Disable the item |
| `closeOnClick` | `bool` | `true` | Dispatch close event on click |
| `color` | `string\|null` | `null` | Override color preset |

```blade
{{-- Link item --}}
<x-bt-dropdown.item href="/settings" icon="heroicon-o-cog-6-tooth">Settings</x-bt-dropdown.item>

{{-- Button item --}}
<x-bt-dropdown.item as="button" @click="doSomething">Action</x-bt-dropdown.item>

{{-- Disabled item --}}
<x-bt-dropdown.item href="/locked" :disabled="true">Locked</x-bt-dropdown.item>

{{-- Keep dropdown open on click --}}
<x-bt-dropdown.item href="/toggle" :closeOnClick="false">Toggle</x-bt-dropdown.item>
```

### `x-bt-dropdown.header`

A non-interactive section title.

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `muted` | `bool` | `true` | Use muted text color |

```blade
<x-bt-dropdown.header>Actions</x-bt-dropdown.header>
```

### `x-bt-dropdown.separator`

A horizontal rule divider.

```blade
<x-bt-dropdown.separator />
```

## Colors

Color presets control the panel background, border, shadow, and item hover/active states. Pass any preset name from the dropdown config:

```blade
<x-bt-dropdown color="blue">
    <x-slot:trigger><x-bt-button>Blue Menu</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/test">Item</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Scrollable Dropdown

Use `maxHeight` to enable scrolling for long menus:

```blade
<x-bt-dropdown :maxHeight="300" overflowMode="auto">
    <x-slot:trigger><x-bt-button>Long List</x-bt-button></x-slot:trigger>
    @foreach($items as $item)
        <x-bt-dropdown.item :href="$item->url">{{ $item->name }}</x-bt-dropdown.item>
    @endforeach
</x-bt-dropdown>
```

## wire:navigate

Pass `:withnavigate="true"` to add `wire:navigate` to all link items:

```blade
<x-bt-dropdown :withnavigate="true">
    <x-slot:trigger><x-bt-button>Nav</x-bt-button></x-slot:trigger>
    <x-bt-dropdown.item href="/page1">Page 1</x-bt-dropdown.item>
    <x-bt-dropdown.item href="/page2">Page 2</x-bt-dropdown.item>
</x-bt-dropdown>
```

## Close Behavior

The dropdown closes via:
- Escape key (`@keydown.escape.window`)
- Clicking outside (`@click.away` / `@click.outside`)
- `bt-dropdown-close` window event (dispatched by items with `closeOnClick`)

## Dark Mode

Dark mode styles are included automatically via `dark:` classes on background, border, and item states.
