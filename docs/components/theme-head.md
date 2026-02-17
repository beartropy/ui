# Theme Head

A blocking inline script that applies the saved dark/light theme before the page renders, preventing the flash of light mode (FOUC) when the user has dark mode enabled. Designed to work with `<x-bt-toggle-theme />` but can be used independently.

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

1. Reads `localStorage.theme` (set by `<x-bt-toggle-theme />` or `window.__setTheme()`)
2. Falls back to `prefers-color-scheme: dark` media query if no saved preference
3. Applies the `dark` class and `colorScheme` style to `<html>` immediately
4. Registers a `livewire:navigated` listener to re-apply after SPA navigation

Because the script is inline and synchronous, it runs before the browser paints any content — eliminating the light-to-dark flash.

## Examples

### Standard Layout

```blade
<!DOCTYPE html>
<html lang="en">
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
<head>
    <x-bt-theme-head />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

The component handles `livewire:navigated` events automatically, so dark mode persists across `wire:navigate` page transitions without any flash.

## Why Is This Needed?

The `<x-bt-toggle-theme />` component includes theme initialization in its JavaScript bundle (`beartropy-ui.js`). However, bundled JS is loaded asynchronously/deferred — by the time it runs, the page has already rendered with default (light) styles.

`<x-bt-theme-head />` solves this by applying the theme via a blocking inline script that runs before any CSS or content is painted.

## Tips

- Always place **before** your CSS (`@vite`, `@livewireStyles`, `<link>` tags)
- Safe to include on every page — it's idempotent
- Works with or without Livewire installed
- Works with or without `<x-bt-toggle-theme />` — useful if you set theme via settings pages or `window.__setTheme()`
