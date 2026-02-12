# x-bt-button-icon — AI Reference

## Component Tag
```blade
<x-bt-button-icon icon="plus" />
```

## Architecture
- `ButtonIcon` → extends `BeartropyComponent`
- Renders through `button-icon.blade.php` (single template, no base delegation)
- Presets: `resources/views/presets/button-icon.php` (flat color → classes, no variants)
- Sizes: global `resources/views/presets/sizes.php` (`buttonIcon`, `buttonIconIcon`)
- Wrapped in `<div class="relative">` for spinner positioning

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| icon | `?string` | `null` (→ `'plus'` in Blade) | `icon="trash"` |
| label | `?string` | `null` (→ translated `'New'`) | `label="Delete"` |
| color | `?string` | `null` | `color="red"` |
| size | `?string` | `null` | `size="lg"` |
| spinner | `?bool` | `true` | `:spinner="false"` |
| rounded | `?string` | `'full'` | `rounded="lg"` |
| iconSet | `?string` | config default | `icon-set="heroicons"` |
| iconVariant | `?string` | config default | `icon-variant="solid"` |

## Magic Attributes (passed as bare attributes)

### Colors (mutually exclusive, default: `beartropy`)
`beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `gray`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

**No variants** — this component has a flat color structure (bg + bg_hover + text).

## Slots

| Slot | Description |
|------|-------------|
| default | Custom content replacing the icon |

## Accessibility
- `label` prop renders as `aria-label` attribute
- Default label: translated `'New'` (`beartropy-ui::ui.new`)
- Explicit `aria-label` attribute overrides the `label` prop

## Spinner / wire:target
- `spinner` defaults to `true`
- Auto-detects `wire:target` from `wire:click` if not explicitly set
- Shows `beartropy-spinner` SVG during loading, hides icon
- Spinner size scales with button size (uses `$sizePreset['buttonIconIcon']`)

## Preset Structure (button-icon.php)
```
colors → {color} → {bg, bg_hover, text}
```
23 colors, all produce: `bg-{color}-600`, `hover:bg-{color}-700`, `text-white`.

## Size Classes

| Size | Button (`buttonIcon`) | Icon (`buttonIconIcon`) |
|------|----------------------|------------------------|
| xs | `w-7 h-7` | `w-2 h-2` |
| sm | `w-8 h-8` | `w-3 h-3` |
| md | `w-10 h-10` | `w-5 h-5` |
| lg | `w-12 h-12` | `w-6 h-6` |
| xl | `w-14 h-14` | `w-7 h-7` |

## Rendered HTML Structure
```html
<div class="relative">
    <button class="flex items-center justify-center cursor-pointer rounded-full shadow-lg transition bg-beartropy-600 text-white hover:bg-beartropy-700 w-10 h-10" aria-label="New">
        <svg class="w-5 h-5">...</svg>
    </button>
</div>
```

With spinner + wire:click:
```html
<div class="relative">
    <button class="..." aria-label="New" wire:click="save" wire:target="save">
        <div wire:loading wire:target="save">
            <svg class="w-5 h-5 animate-spin">...</svg>
        </div>
        <div wire:loading.remove wire:target="save">
            <svg class="w-5 h-5">...</svg>
        </div>
    </button>
</div>
```

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-button-icon icon="plus" />

{{-- Color + icon --}}
<x-bt-button-icon red icon="trash" label="Delete" />

{{-- Size --}}
<x-bt-button-icon lg icon="star" />

{{-- Link --}}
<x-bt-button-icon href="/settings" icon="cog-6-tooth" />

{{-- Livewire action --}}
<x-bt-button-icon wire:click="delete" icon="trash" red label="Delete item" />

{{-- No spinner --}}
<x-bt-button-icon :spinner="false" wire:click="save" icon="check" />

{{-- Custom slot --}}
<x-bt-button-icon red>
    <span class="text-xs font-bold">99</span>
</x-bt-button-icon>

{{-- Dynamic color --}}
<x-bt-button-icon :color="$isActive ? 'green' : 'gray'" icon="power" />
```

## Key Notes
- No variants — flat color structure (unlike Button which has solid/outline/ghost/etc.)
- `label` is NOT rendered visually — only used as `aria-label`
- Slot content replaces the icon entirely
- `href` switches tag from `<button>` to `<a>`
- Spinner size scales with the button (not hardcoded)
