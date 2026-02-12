# Floating Action Button (FAB)

A fixed-position circular button for primary actions, typically placed at the bottom-right corner of the screen.

## Basic Usage

```blade
<x-bt-fab />
```

Renders a circular button with a **+** icon at the default position (bottom-right, 1rem from edges).

## Icon

```blade
<x-bt-fab icon="pencil" />
<x-bt-fab icon="arrow-up" />
```

Any Heroicon name is accepted. Defaults to `plus`.

## Label (Accessibility)

The `label` prop sets the `aria-label` for screen readers. Defaults to the localized "New" text.

```blade
<x-bt-fab label="Create post" />
<x-bt-fab icon="pencil" label="Edit document" />
```

## As a Link

When `href` is provided, the FAB renders as an `<a>` tag instead of `<button>`.

```blade
<x-bt-fab href="/posts/create" icon="plus" label="New post" />
```

## Colors

Supports all color presets via named prop or magic attribute:

```blade
<x-bt-fab color="red" />
<x-bt-fab blue />
<x-bt-fab green icon="check" />
```

Available colors: beartropy (default), red, blue, green, yellow, purple, pink, gray, orange, amber, lime, emerald, teal, cyan, sky, indigo, violet, rose, fuchsia, slate, stone, zinc, neutral.

## Sizes

```blade
<x-bt-fab size="sm" />
<x-bt-fab size="lg" />
```

Available sizes: xs, sm, md (default), lg, xl.

## Positioning

Control the FAB's fixed position with `right`, `bottom`, and `zIndex`:

```blade
<x-bt-fab right="2rem" bottom="2rem" :zIndex="100" />
```

Defaults: `right="1rem"`, `bottom="1rem"`, `zIndex=50`.

## Mobile Only

Hide the FAB on medium screens and above:

```blade
<x-bt-fab :onlyMobile="true" />
```

## Custom Content (Slot)

Replace the icon entirely with custom slot content:

```blade
<x-bt-fab>
    <span class="text-lg font-bold">+</span>
</x-bt-fab>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| icon | `?string` | `'plus'` | Heroicon name |
| label | `?string` | `'New'` (localized) | Accessible aria-label |
| onlyMobile | `?string` | `false` | Hide on md+ screens |
| zIndex | `?string` | `50` | CSS z-index |
| right | `?string` | `'1rem'` | CSS right offset |
| bottom | `?string` | `'1rem'` | CSS bottom offset |
| color | `?string` | `'beartropy'` | Color preset |
| size | `?string` | `'md'` | Size preset |

## Accessibility

- `aria-label` is always rendered (from `label` prop or default localized text)
- `type="button"` is set when rendering as a `<button>` (not a link)
- Focus-visible ring for keyboard navigation
