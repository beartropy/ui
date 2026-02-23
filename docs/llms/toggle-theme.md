# x-bt-toggle-theme — AI Reference

## Component Tag
```blade
<x-bt-toggle-theme />
<x-bt-toggle-theme mode="button" label="Dark Mode" size="lg" />
<x-bt-toggle-theme mode="square-button" icon-light="sun" icon-dark="moon" />
```

## Architecture
- `ToggleTheme` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`)
- Renders `toggle-theme.blade.php` — inline `<style>` for animation, Alpine via `x-data="btToggleTheme()"`
- JS module: `resources/js/modules/toggle-theme.js` (bundled into `beartropy-ui.js`)
  - `initTheme()` — runs at bundle load (before Alpine), applies saved theme, exposes `__setTheme`
  - `btToggleTheme()` — registered as `Alpine.data()`, handles toggle/rotation/event sync
- No preset file — colors are per-instance props with sensible defaults
- Persists to `localStorage.theme` (`'dark'` | `'light'`) **and** `bt_theme` cookie (for server-side rendering)
- Dispatches `theme-change` CustomEvent for cross-component sync
- Uses `MutationObserver` on `<html>` to catch `wire:navigate` morphing the class attribute

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|-----------------|
| size | `string` | `'md'` | `size="lg"` |
| mode | `string` | `'icon'` | `mode="button"` |
| class | `string` | `''` | `class="my-class"` |
| inheritColor | `bool` | `false` | `:inherit-color="true"` |
| iconColorLight | `?string` | `'text-orange-600'` | `icon-color-light="text-yellow-500"` |
| iconColorDark | `?string` | `'text-blue-400'` | `icon-color-dark="text-indigo-400"` |
| borderColorLight | `?string` | `'border-orange-300 dark:border-blue-600'` | `border-color-light="..."` |
| borderColorDark | `?string` | `'border-orange-400 dark:border-blue-500'` | `border-color-dark="..."` |
| iconLight | `?string` | `null` | `icon-light="sun"` |
| iconDark | `?string` | `null` | `icon-dark="moon"` |
| label | `?string` | `null` | `label="Dark Mode"` |
| labelPosition | `string` | `'right'` | `label-position="left"` |
| labelClass | `?string` | `null` | `label-class="text-xs"` |
| ariaLabel | `?string` | `null` | `aria-label="Switch theme"` |

## Slots
| Slot | Description |
|------|-------------|
| `icon-light` | Custom SVG/HTML for light mode icon |
| `icon-dark` | Custom SVG/HTML for dark mode icon |

## Size Map

| Size | Icon | Button Padding | Square Button |
|------|------|----------------|---------------|
| xs | w-2 h-2 | p-1 | w-7 h-7 |
| sm | w-3 h-3 | p-1.5 | w-8 h-8 |
| md | w-4 h-4 | p-2 | w-10 h-10 |
| lg | w-5 h-5 | p-3 | w-12 h-12 |
| xl | w-6 h-6 | p-4 | w-14 h-14 |
| 2xl | w-8 h-8 | p-5 | w-16 h-16 |

## Icon Resolution Order
1. Named slot (`icon-light` / `icon-dark`) — fully custom content
2. Heroicon name prop (`iconLight` / `iconDark`) — renders `<x-beartropy-ui::icon>`
3. Default inline SVG — sun (12-point star) / moon (crescent)

## Template Structure
```
<style> @keyframes theme-spin, .theme-rotate </style>
div[x-data="btToggleTheme()"]
└── button[type="button"][@click.stop="toggle()"][:aria-pressed][aria-label]
    ├── (icon-light slot | heroicon | sun SVG)[x-show="!dark"]
    └── (icon-dark slot | heroicon | moon SVG)[x-show="dark"]
```

## Alpine.js State (`btToggleTheme()`)
```js
{
    dark: boolean,       // Current theme state (from localStorage + system pref)
    rotating: boolean,   // Animation flag (true during 500ms rotation)

    init() {
        // Listen for external theme-change events to sync state
    },

    toggle() {
        // Flip dark, update <html> classList + colorScheme,
        // persist to localStorage + bt_theme cookie,
        // dispatch 'theme-change' event,
        // trigger rotation via $nextTick (ensures new icon renders first)
    }
}
```

## JS Module API (`resources/js/modules/toggle-theme.js`)
```js
// initTheme() — called at bundle load, before Alpine
// - Applies saved theme before CSS loads (FOUC prevention)
// - Sets bt_theme cookie (enables server-side rendering via @beartropyHtmlClass)
// - Exposes window.__setTheme('dark' | 'light') for programmatic use
// - MutationObserver on <html> catches wire:navigate class morphing

window.__setTheme('dark');  // Programmatic theme setter
// Dispatches: CustomEvent('theme-change', { detail: { theme: 'dark' } })
```

## Common Patterns

```blade
{{-- Basic icon toggle --}}
<x-bt-toggle-theme />

{{-- Button with label --}}
<x-bt-toggle-theme mode="button" label="Dark Mode" size="lg" />

{{-- Square button --}}
<x-bt-toggle-theme mode="square-button" size="md" />

{{-- Custom colors --}}
<x-bt-toggle-theme
    icon-color-light="text-yellow-500"
    icon-color-dark="text-indigo-400"
/>

{{-- Custom heroicons --}}
<x-bt-toggle-theme icon-light="sun" icon-dark="moon" />

{{-- Custom SVG slots --}}
<x-bt-toggle-theme>
    <x-slot:icon-light><span>☀️</span></x-slot:icon-light>
    <x-slot:icon-dark><span>🌙</span></x-slot:icon-dark>
</x-bt-toggle-theme>

{{-- In a nav bar --}}
<x-bt-toggle-theme mode="button" label="Theme" size="sm" class="ml-auto" />
```

## FOUC Prevention

`@BeartropyAssets` handles FOUC prevention automatically via four layers:

1. **Inline `<style>`** — `html.dark{color-scheme:dark}` for native form control theming (CSS-only, no JS dependency)
2. **Inline `<script>`** — applies `dark` class + `colorScheme` synchronously before body renders
3. **`data-navigate-once`** — prevents Livewire `wire:navigate` from removing/re-adding CSS and JS during head merging
4. **`MutationObserver`** — watches `<html>` class attribute; if `wire:navigate` morphs it and strips `dark`, the observer re-applies before the browser repaints

For zero-FOUC server-side rendering, use `@beartropyHtmlClass` on your `<html>` tag:

```blade
<html lang="en" class="@beartropyHtmlClass">
```

This reads the `bt_theme` cookie (set automatically by the toggle) and renders the `dark` class server-side.

If you load assets manually (e.g., via `@vite` without `@BeartropyAssets`), add `<x-bt-theme-head />` to your layout's `<head>` before stylesheets. See [theme-head](theme-head.md) for details.

## Key Notes
- Icon mode renders a `<button>` (not bare SVG) — proper semantics + keyboard accessible
- All 3 modes have `aria-label` and `:aria-pressed="dark"`
- Default aria-label: localized `__('beartropy-ui::ui.toggle_theme')` → "Toggle theme"
- Animation: 500ms CSS rotation via `.theme-rotate` class, synced with JS timeout
- Global `<script>` runs on every page load (even with multiple instances, safe to duplicate)
- `window.__setTheme()` allows external code (nav dropdowns, settings pages) to change theme
- `colorScheme` CSS property is set alongside `dark` class for native form element dark mode
- `bt_theme` cookie enables server-side theme rendering via `@beartropyHtmlClass`
- Slot check uses `isset($__data['iconLight'])` (camelCase) — slots are stored camelCase in `$__data`
