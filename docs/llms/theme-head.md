# x-bt-theme-head — AI Reference

## Component Tag
```blade
<x-bt-theme-head />
```

## Architecture
- `ThemeHead` extends `BeartropyComponent`
- Renders: `theme-head.blade.php`
- No preset file — no visual output, only a `<script>` tag
- No Alpine.js dependency — runs as plain synchronous JavaScript
- Companion to `<x-bt-toggle-theme />` — prevents FOUC (Flash of Unstyled Content)

## Purpose

Outputs a **blocking inline `<script>`** that applies the saved dark/light theme to `<html>` before any CSS or body content renders. Without this component, pages flash light then switch to dark when the JS bundle loads.

**Must be placed in `<head>` before stylesheets.**

## Props (Constructor)

None. This component takes no props.

## Slots

None.

## Template Structure
```
<script>
  (IIFE)
  ├── Read localStorage.getItem('theme')
  ├── Check window.matchMedia('(prefers-color-scheme:dark)')
  ├── Toggle 'dark' class on <html>
  ├── Set document.documentElement.style.colorScheme
  ├── Set window.__btThemeNavigated = true (dedup flag)
  └── Register 'livewire:navigated' listener (re-applies theme after SPA navigation)
</script>
```

## How It Works

1. Runs **synchronously** in `<head>` — blocks rendering until the dark class is applied
2. Reads `localStorage.theme` (`'dark'` | `'light'`) or falls back to `prefers-color-scheme` media query
3. Toggles `dark` class and sets `colorScheme` CSS property on `<html>`
4. Sets `window.__btThemeNavigated = true` so the bundled `initTheme()` in `beartropy-ui.js` skips registering a duplicate `livewire:navigated` listener
5. Registers its own `livewire:navigated` listener to re-apply the theme after Livewire SPA navigation

## Relationship with `<x-bt-toggle-theme />`

| Component | Role |
|-----------|------|
| `<x-bt-theme-head />` | Prevents FOUC — apply theme before paint |
| `<x-bt-toggle-theme />` | UI toggle — lets user switch theme |

Both are independent. `theme-head` is needed even without a visible toggle (e.g., if theme is set via a settings page or `window.__setTheme()`).

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
```

## Key Notes
- **Must be in `<head>`** — placing it in `<body>` defeats the purpose (content already rendered)
- **Place before stylesheets** — the `dark` class must be present when CSS is parsed
- Zero visual output — only a `<script>` tag
- Safe to include on every page (idempotent, no side effects if theme is already applied)
- Works with or without Livewire — the `livewire:navigated` listener is always registered (no-op if Livewire isn't present)
- The bundled `initTheme()` in `beartropy-ui.js` still works as a fallback if this component is not used
