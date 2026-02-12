# Badge

A small badge or tag component for labeling items with status, category, or metadata.

## Basic Usage

```blade
<x-bt-badge>New</x-bt-badge>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Badge text (alternative to slot content) |
| `icon` | `string\|null` | `null` | Icon shorthand — renders as left icon |
| `icon-left` | `string\|null` | `null` | Icon name before the label |
| `icon-right` | `string\|null` | `null` | Icon name after the label |
| `color` | `string\|null` | `null` | Color name (or use magic attribute) |
| `size` | `string\|null` | `null` | Size name (or use magic attribute) |
| `variant` | `string\|null` | `null` | Variant name (or use magic attribute) |

## Variants

Set the visual style using a magic attribute:

```blade
<x-bt-badge solid>Solid (default)</x-bt-badge>
<x-bt-badge soft>Soft</x-bt-badge>
<x-bt-badge outline>Outline</x-bt-badge>
<x-bt-badge tint>Tint</x-bt-badge>
<x-bt-badge glass>Glass</x-bt-badge>
```

## Colors

Set the color using a magic attribute. Available on all variants:

```blade
<x-bt-badge beartropy>Beartropy (default)</x-bt-badge>
<x-bt-badge red>Red</x-bt-badge>
<x-bt-badge green>Green</x-bt-badge>
<x-bt-badge blue>Blue</x-bt-badge>
```

All 18 colors: `beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`.

You can also use the `color` prop:

```blade
<x-bt-badge :color="$dynamicColor">Dynamic</x-bt-badge>
```

## Sizes

```blade
<x-bt-badge xs>Extra Small</x-bt-badge>
<x-bt-badge sm>Small (default)</x-bt-badge>
<x-bt-badge md>Medium</x-bt-badge>
<x-bt-badge lg>Large</x-bt-badge>
<x-bt-badge xl>Extra Large</x-bt-badge>
```

## Icons

```blade
{{-- Icon shorthand (renders on the left) --}}
<x-bt-badge icon="check-circle" label="Approved" />

{{-- Explicit left icon --}}
<x-bt-badge icon-left="star">Featured</x-bt-badge>

{{-- Right icon --}}
<x-bt-badge icon-right="arrow-right">Next</x-bt-badge>

{{-- Both icons --}}
<x-bt-badge icon-left="star" icon-right="chevron-down">Featured</x-bt-badge>
```

The `icon` prop is a shorthand for `icon-left`. If both `icon` and `icon-left` are provided, `icon` takes precedence.

## Slots

```blade
{{-- Default slot: badge content --}}
<x-bt-badge>Badge text</x-bt-badge>

{{-- Label prop: alternative to slot --}}
<x-bt-badge label="Badge text" />

{{-- Start/End slots for custom prefix/suffix content --}}
<x-bt-badge>
    <x-slot:start>
        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
    </x-slot:start>
    Online
</x-bt-badge>

<x-bt-badge label="Errors">
    <x-slot:end>
        <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-500 text-white text-[10px] font-bold">3</span>
    </x-slot:end>
</x-bt-badge>
```

## Combining Variant + Color

Mix any variant with any color:

```blade
<x-bt-badge soft red>Soft Red</x-bt-badge>
<x-bt-badge outline blue>Outline Blue</x-bt-badge>
<x-bt-badge tint green>Tint Green</x-bt-badge>
<x-bt-badge glass purple>Glass Purple</x-bt-badge>
```

## Configuration

Default color and size can be set in `config/beartropyui.php`:

```php
'component_defaults' => [
    'badge' => [
        'color' => env('BEARTROPY_UI_BADGE_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_BADGE_SIZE', 'sm'),
    ],
],
```

Or via `.env`:

```env
BEARTROPY_UI_BADGE_COLOR=blue
BEARTROPY_UI_BADGE_SIZE=md
```

## Dark Mode

All variants and colors include dark mode styles automatically. No extra configuration needed.
