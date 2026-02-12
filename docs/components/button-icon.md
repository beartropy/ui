# Button Icon

An icon-only button for compact actions. Supports colors, sizes, rounded variants, spinner loading states, and link mode.

## Basic Usage

```blade
<x-bt-button-icon icon="plus" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `icon` | `string\|null` | `'plus'` | Icon name (Heroicons by default) |
| `label` | `string\|null` | `'New'` | Accessible label (rendered as `aria-label`) |
| `color` | `string\|null` | `null` | Color name (or use magic attribute) |
| `size` | `string\|null` | `null` | Size name (or use magic attribute) |
| `spinner` | `bool\|null` | `true` | Show spinner on Livewire loading |
| `rounded` | `string\|null` | `'full'` | Border radius suffix (`full`, `lg`, `md`, `none`, etc.) |
| `icon-set` | `string\|null` | config default | Icon set to use |
| `icon-variant` | `string\|null` | config default | Icon variant (e.g. `outline`, `solid`) |

## Colors

Set the color using a magic attribute:

```blade
<x-bt-button-icon beartropy icon="heart" />
<x-bt-button-icon red icon="trash" />
<x-bt-button-icon blue icon="information-circle" />
<x-bt-button-icon green icon="check" />
```

All 23 colors: `beartropy` (default), `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `gray`, `slate`, `stone`, `zinc`, `neutral`.

You can also use the `color` prop:

```blade
<x-bt-button-icon :color="$dynamicColor" icon="star" />
```

## Sizes

```blade
<x-bt-button-icon xs icon="star" />
<x-bt-button-icon sm icon="star" />
<x-bt-button-icon md icon="star" />
<x-bt-button-icon lg icon="star" />
<x-bt-button-icon xl icon="star" />
```

| Size | Button | Icon |
|------|--------|------|
| `xs` | `w-7 h-7` | `w-2 h-2` |
| `sm` | `w-8 h-8` | `w-3 h-3` |
| `md` | `w-10 h-10` | `w-5 h-5` |
| `lg` | `w-12 h-12` | `w-6 h-6` |
| `xl` | `w-14 h-14` | `w-7 h-7` |

## Rounded

```blade
<x-bt-button-icon rounded="full" icon="plus" />
<x-bt-button-icon rounded="lg" icon="plus" />
<x-bt-button-icon rounded="md" icon="plus" />
<x-bt-button-icon rounded="none" icon="plus" />
```

## Link Mode

Pass `href` to render as an `<a>` tag instead of `<button>`:

```blade
<x-bt-button-icon href="/settings" icon="cog-6-tooth" />
```

## Spinner (Livewire)

Spinner is enabled by default. When `wire:click` is present, a spinner replaces the icon during loading:

```blade
<x-bt-button-icon wire:click="save" icon="check" />
```

Disable the spinner:

```blade
<x-bt-button-icon :spinner="false" wire:click="save" icon="check" />
```

## Accessibility

The `label` prop renders as `aria-label` for screen readers. Default is "New":

```blade
<x-bt-button-icon icon="trash" label="Delete item" />
```

You can also pass `aria-label` directly:

```blade
<x-bt-button-icon icon="trash" aria-label="Remove" />
```

## Slots

Pass custom content instead of an icon:

```blade
<x-bt-button-icon>
    <span class="text-sm font-bold">A</span>
</x-bt-button-icon>
```

## Disabled

```blade
<x-bt-button-icon disabled icon="plus" />
```
