# x-bt-button — AI Reference

## Component Tag
```blade
<x-bt-button>Label</x-bt-button>
```

## Architecture
- `Button` → extends `BeartropyComponent`
- Renders through `button.blade.php` → delegates to `base/button-base.blade.php`
- Presets: `resources/views/presets/button.php` (variant → color → classes)
- Sizes: global `resources/views/presets/sizes.php`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| type | `?string` | `null` | `type="submit"` |
| href | `?string` | `null` | `href="/url"` |
| disabled | `?bool` | `false` | `disabled` or `:disabled="$val"` |
| iconStart | `?string` | `null` | `icon-start="envelope"` |
| iconEnd | `?string` | `null` | `icon-end="arrow-right"` |
| label | `?string` | `null` | `label="Click"` |
| spinner | `?bool` | `true` | `:spinner="false"` |
| iconSet | `?string` | config | `icon-set="lucide"` |
| iconVariant | `?string` | config | `icon-variant="solid"` |

## Magic Attributes (passed as bare attributes)

### Variants (mutually exclusive, default: `solid`)
`solid`, `soft`, `outline`, `ghost`, `tint`, `glass`, `gradient`

### Colors (mutually exclusive, default: `beartropy`)
`beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Button content (overrides `label` prop) |
| start | Content before icon-start |
| end | Content after icon-end |

## HTML Tag Resolution
- `href` present → renders `<a>`
- No `href` → renders `<button type="{type}">`

## Livewire Loading
- Auto-detects `wire:click` → infers `wire:target`
- Shows spinner overlay during loading (centered, replaces content via `wire:loading.class="opacity-0"`)
- Auto-disables via `wire:loading.attr="disabled"`
- Disable with `:spinner="false"`

## Preset Structure (button.php)
```
colors → {variant} → {color} → {bg, text, border, hover, focus, active, disabled}
```
7 variants × 24 colors = 168 color presets.

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-button>Click</x-bt-button>

{{-- Variant + Color --}}
<x-bt-button soft blue>Soft Blue</x-bt-button>

{{-- Size --}}
<x-bt-button sm green>Small Green</x-bt-button>

{{-- Icons --}}
<x-bt-button icon-start="plus" green>Add Item</x-bt-button>

{{-- Link --}}
<x-bt-button href="/settings" ghost gray>Settings</x-bt-button>

{{-- Livewire --}}
<x-bt-button wire:click="save" green>Save</x-bt-button>

{{-- Dynamic color --}}
<x-bt-button :color="$status === 'active' ? 'green' : 'red'">{{ $status }}</x-bt-button>

{{-- Disabled --}}
<x-bt-button disabled>Disabled</x-bt-button>
```

## Config Defaults
```php
'button' => [
    'color' => env('BEARTROPY_UI_BUTTON_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_BUTTON_SIZE', 'md'),
],
```

## Key Notes
- `$tag` default is `null` (template resolves: `$tag ?? ($href ? 'a' : 'button')`)
- Spinner auto-infers target from `wire:click` value (extracts method name)
- All 7 variants support all 24 colors with full dark mode
- `label` prop is used only when the default slot is empty
