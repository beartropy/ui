# x-bt-theme-head — AI Reference

## Component Tag
```blade
<x-bt-theme-head />
```

## Architecture
- `ThemeHead` extends `BeartropyComponent`
- Renders: `theme-head.blade.php`
- No preset file — no visual output, only a `<style>` + `<script>` tag
- No Alpine.js dependency — runs as plain synchronous JavaScript
- Companion to `<x-bt-toggle-theme />` — prevents FOUC (Flash of Unstyled Content)

## Purpose

Outputs a **blocking inline `<style>` + `<script>`** that applies the saved dark/light theme to `<html>` before any CSS or body content renders. Without this component, pages flash light then switch to dark when the JS bundle loads.

**Note:** If you use `@BeartropyAssets`, the inline theme style and script are already included automatically. You only need `<x-bt-theme-head />` if you load Beartropy assets manually (e.g., via `@vite`) or need the script earlier in `<head>`.

**Must be placed in `<head>` before stylesheets.**

## Props (Constructor)

None. This component takes no props.

## Slots

None.

## Template Structure
```
<style data-navigate-once>
  html.dark { color-scheme: dark }
  html:not(.dark) { color-scheme: light }
</style>
<script data-navigate-once>
  (IIFE)
  ├── Read localStorage.getItem('theme')
  ├── Check window.matchMedia('(prefers-color-scheme:dark)')
  ├── Toggle 'dark' class on <html>
  ├── Set document.documentElement.style.colorScheme
  ├── Set bt_theme cookie (for @beartropyHtmlClass server-side rendering)
  └── Register MutationObserver on <html> class attribute
      └── If wire:navigate morphs <html> and strips 'dark', reapply before repaint
</script>
```

## How It Works

1. **Inline `<style>`** sets `color-scheme: dark` on `html.dark` and `color-scheme: light` on `html:not(.dark)` — applies via CSS immediately, no JS needed
2. **Inline `<script>`** runs synchronously in `<head>` — reads `localStorage.theme` or falls back to `prefers-color-scheme` media query, toggles `dark` class and `colorScheme` on `<html>`
3. Sets a `bt_theme` cookie (enables server-side rendering via `@beartropyHtmlClass`)
4. Registers a `MutationObserver` (guarded by `window.__btThemeGuard`) on `<html>` class attribute — catches Livewire `wire:navigate` morphing the class and reapplies the correct theme before the browser repaints
5. Both `<style>` and `<script>` have `data-navigate-once` — Livewire preserves them across SPA navigations without removal/re-addition

## Relationship with `<x-bt-toggle-theme />`

| Component | Role |
|-----------|------|
| `<x-bt-theme-head />` | Prevents FOUC — apply theme before paint |
| `<x-bt-toggle-theme />` | UI toggle — lets user switch theme |

Both are independent. `theme-head` is needed even without a visible toggle (e.g., if theme is set via a settings page or `window.__setTheme()`).

## Blade Directives

### `@beartropyHtmlClass`

For zero-FOUC server-side rendering, use this directive on your `<html>` tag:

```blade
<html lang="en" class="@beartropyHtmlClass">
```

Reads the `bt_theme` cookie (set by the inline script and toggle component) and outputs `dark` if the user prefers dark mode. The browser receives `<html class="dark">` in the initial HTML — no JS dependency for the first paint.

## Common Patterns

```blade
{{-- Standard layout setup --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-bt-theme-head />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- With Livewire + wire:navigate --}}
<head>
    <x-bt-theme-head />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Zero-FOUC server-side rendering --}}
<html lang="en" class="@beartropyHtmlClass">
<head>
    <x-bt-theme-head />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

## Key Notes
- **Must be in `<head>`** — placing it in `<body>` defeats the purpose (content already rendered)
- **Place before stylesheets** — the `dark` class must be present when CSS is parsed
- Zero visual output — only a `<style>` + `<script>` tag
- Safe to include on every page — idempotent, guarded by `window.__btThemeGuard`
- Works with or without Livewire — the MutationObserver is passive if nothing changes the class
- The bundled `initTheme()` in `beartropy-ui.js` still works as a fallback if this component is not used
- `data-navigate-once` prevents Livewire from removing the tags during `wire:navigate` head merging
