# x-bt-fab — AI Reference

## Component Tag
```blade
<x-bt-fab />
<x-bt-fab icon="pencil" label="Edit" href="/edit" color="blue" size="lg" />
```

## Architecture
- `Fab` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`)
- Renders `fab.blade.php` — no Alpine, no JS, pure server-rendered
- Preset file: `presets/fab.php` (22 colors: bg, bg_hover, text)
- Size keys in `presets/sizes.php`: `fabButton` (container), `fabIcon` (icon)
- Renders as `<button type="button">` by default; `<a>` when `href` present
- Fixed-position wrapper: `<div class="fixed" style="right:…; bottom:…; z-index:…">`

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|--------------------|
| icon | `?string` | `'plus'` | `icon="star"` |
| label | `?string` | `__('beartropy-ui::ui.new')` → `'New'` | `label="Add"` |
| onlyMobile | `?string` | `false` | `:onlyMobile="true"` |
| zIndex | `?string` | `50` | `:zIndex="100"` |
| right | `?string` | `'1rem'` | `right="2rem"` |
| bottom | `?string` | `'1rem'` | `bottom="3rem"` |
| color | `?string` | `null` (→ beartropy) | `color="red"` |
| size | `?string` | `null` (→ default) | `size="lg"` |

## Slots
| Slot | Description |
|------|-------------|
| default | Custom button content (replaces icon) |

## Color Preset Structure (`presets/fab.php`)
```php
'blue' => [
    'bg' => 'bg-blue-600',
    'bg_hover' => 'hover:bg-blue-700',
    'text' => 'text-white',
],
```

## Size Preset Keys (`presets/sizes.php`)
| Size | `fabButton` | `fabIcon` |
|------|-------------|-----------|
| xs | w-10 h-10 | w-4 h-4 |
| sm | w-12 h-12 | w-6 h-6 |
| md | w-14 h-14 | w-8 h-8 |
| lg | w-16 h-16 | w-10 h-10 |
| xl | w-18 h-18 | w-12 h-12 |

## Template Structure
```
div.fixed[style="right; bottom; z-index"]
└── button|a.rounded-full.shadow-lg[aria-label]
    └── (slot content | <x-beartropy-ui::icon>)
```

## Common Patterns

```blade
{{-- Default FAB --}}
<x-bt-fab />

{{-- Custom icon + label --}}
<x-bt-fab icon="pencil" label="Edit" />

{{-- As a link --}}
<x-bt-fab href="/posts/create" label="New post" />

{{-- Color + size --}}
<x-bt-fab green size="lg" icon="check" label="Confirm" />

{{-- Mobile only --}}
<x-bt-fab :onlyMobile="true" />

{{-- Custom position --}}
<x-bt-fab right="2rem" bottom="5rem" :zIndex="100" />

{{-- Custom slot content --}}
<x-bt-fab label="Add">
    <span class="text-xl font-bold">+</span>
</x-bt-fab>
```

## Key Notes
- `$label` is used ONLY for `aria-label`, NOT rendered visually
- Default label is localized: `__('beartropy-ui::ui.new')` → "New"
- `$attributes->merge()` handles class merging + all pass-through attributes
- `type="button"` only added when rendering as `<button>` (not `<a>`)
- Focus ring uses `focus-visible:ring-2` for keyboard-only visibility
- `onlyMobile` adds `md:hidden` to the wrapper `<div>`, not the button
- Positioning uses inline `style` (not Tailwind classes) for arbitrary values
