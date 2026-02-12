# x-bt-badge — AI Reference

## Component Tag
```blade
<x-bt-badge>Label</x-bt-badge>
```

## Architecture
- `Badge` → extends `BeartropyComponent`
- Renders through `badge.blade.php` (single template, no base delegation)
- Presets: `resources/views/presets/badge.php` (variant → color → classes)
- Sizes: global `resources/views/presets/sizes.php`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| color | `?string` | `null` | `color="red"` |
| size | `?string` | `null` | `size="lg"` |
| variant | `?string` | `null` | `variant="outline"` |
| label | `?string` | `null` | `label="Text"` |
| icon | `?string` | `null` | `icon="check"` |
| iconLeft | `?string` | `null` | `icon-left="star"` |
| iconRight | `?string` | `null` | `icon-right="arrow-right"` |

## Magic Attributes (passed as bare attributes)

### Variants (mutually exclusive, default: `solid`)
`solid`, `soft`, `outline`, `tint`, `glass`

### Colors (mutually exclusive, default: `beartropy`)
`beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`

### Sizes (mutually exclusive, default: `sm`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Badge content (can combine with `label` prop) |
| start | Content before left icon |
| end | Content after right icon |

## Icon Resolution
- `icon` is a shorthand for `iconLeft`
- Template resolves: `$icon ?? $iconLeft`
- If both are set, `icon` wins
- Left icon gets `mr-1`, right icon gets `ml-1`

## Preset Structure (badge.php)
```
colors → {variant} → {color} → {bg, text, border?}
```
5 variants × 18 colors = 90 color presets.

- `solid` / `soft` / `tint`: `bg` + `text` (no border)
- `outline`: `bg` (transparent) + `text` + `border`
- `glass`: `bg` (backdrop-blur) + `text` + `border`

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-badge>New</x-bt-badge>

{{-- Label prop --}}
<x-bt-badge label="Status" />

{{-- Variant + Color --}}
<x-bt-badge soft red>Soft Red</x-bt-badge>

{{-- Size --}}
<x-bt-badge sm green>Small Green</x-bt-badge>

{{-- Icon shorthand --}}
<x-bt-badge icon="check-circle" green label="Approved" />

{{-- Left and right icons --}}
<x-bt-badge icon-left="star" icon-right="chevron-down" label="Featured" />

{{-- Dynamic color --}}
<x-bt-badge :color="$status === 'active' ? 'green' : 'red'">{{ $status }}</x-bt-badge>

{{-- Start slot with status dot --}}
<x-bt-badge blue>
    <x-slot:start>
        <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1.5"></span>
    </x-slot:start>
    Online
</x-bt-badge>
```

## Config Defaults
```php
'badge' => [
    'color' => env('BEARTROPY_UI_BADGE_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_BADGE_SIZE', 'sm'),
],
```

## Key Notes
- No Alpine/JS component — purely server-rendered
- `label` and slot content are NOT mutually exclusive — both render (`{{ $label }}{{ $slot }}`)
- Single `<span>` output — no nested base component
- `$getComponentPresets('badge')` called with 2 args (config provides defaults)
- All 5 variants support all 18 colors with full dark mode
