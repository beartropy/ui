# Theme Head

A blocking inline style and script that applies the saved dark/light theme before the page renders, preventing the flash of light mode (FOUC) when the user has dark mode enabled. Designed to work with `<x-bt-toggle-theme />` but can be used independently.

## When Do You Need This?

If you use `@BeartropyAssets`, the inline theme style and script are **already included automatically** — you don't need this component.

Use `<x-bt-theme-head />` only if you load Beartropy assets manually (e.g., via `@vite`) or need the script earlier in `<head>` than where `@BeartropyAssets` is placed.

## Basic Usage

Place in your layout's `<head>`, **before any stylesheets**:

```blade
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-bt-theme-head />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

## Props

None. This component takes no props or attributes.

## Slots

None.

## How It Works

1. **Inline `<style>`** sets `color-scheme: dark` on `html.dark` and `color-scheme: light` on `html:not(.dark)` — applies via CSS immediately
2. **Inline `<script>`** reads `localStorage.theme` (set by `<x-bt-toggle-theme />` or `window.__setTheme()`) — falls back to `prefers-color-scheme: dark` media query if no saved preference
3. Applies the `dark` class and `colorScheme` style to `<html>` immediately
4. Sets a `bt_theme` cookie (enables server-side rendering via `@beartropyHtmlClass`)
5. Registers a `MutationObserver` on `<html>` class — catches `wire:navigate` morphing and reapplies dark mode before the browser repaints
6. Both tags have `data-navigate-once` — Livewire preserves them across SPA navigations

Because the style is pure CSS and the script is inline and synchronous, they run before the browser paints any content — eliminating the light-to-dark flash.

## Server-Side Rendering

For zero-FOUC on the very first paint (before any JS runs), add `@beartropyHtmlClass` to your `<html>` tag:

```blade
<html lang="en" class="@beartropyHtmlClass">
```

This reads the `bt_theme` cookie and renders the `dark` class server-side. On the first visit (no cookie yet), the inline script handles it.

## Examples

### Standard Layout

```blade
<!DOCTYPE html>
<html lang="en" class="@beartropyHtmlClass">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-bt-theme-head />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-900">
    <nav>
        <x-bt-toggle-theme />
    </nav>
    {{ $slot }}
</body>
</html>
```

### With Livewire + wire:navigate

```blade
<html lang="en" class="@beartropyHtmlClass">
<head>
    <x-bt-theme-head />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

The MutationObserver handles `wire:navigate` page transitions automatically — dark mode persists without any flash.

## Why Is This Needed?

The `<x-bt-toggle-theme />` component includes theme initialization in its JavaScript bundle (`beartropy-ui.js`). However, bundled JS is loaded asynchronously/deferred — by the time it runs, the page has already rendered with default (light) styles.

`<x-bt-theme-head />` solves this with:
- A **CSS rule** (`color-scheme`) that applies instantly for native form controls
- A **blocking inline script** that applies the `dark` class before any content is painted
- A **MutationObserver** that guards against `wire:navigate` morphing `<html>` and stripping the `dark` class

## Tips

- Always place **before** your CSS (`@vite`, `@livewireStyles`, `<link>` tags)
- Safe to include on every page — idempotent, guarded by `window.__btThemeGuard`
- Works with or without Livewire installed
- Works with or without `<x-bt-toggle-theme />` — useful if you set theme via settings pages or `window.__setTheme()`
- Combine with `@beartropyHtmlClass` on `<html>` for the most robust FOUC prevention
