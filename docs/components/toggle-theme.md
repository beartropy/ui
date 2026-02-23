# Toggle Theme

A dark/light mode toggle that persists the user's preference to `localStorage` and a `bt_theme` cookie. Syncs across components via a custom `theme-change` event. Includes FOUC prevention that works across full page loads and `wire:navigate` SPA navigation. Three display modes: bare icon, rounded button, or square button.

## Basic Usage

```blade
<x-bt-toggle-theme />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `string` | `'md'` | Size: `xs`, `sm`, `md`, `lg`, `xl`, `2xl` |
| `mode` | `string` | `'icon'` | Display mode: `icon`, `button`, `square-button` |
| `class` | `string` | `''` | Additional wrapper classes |
| `inherit-color` | `bool` | `false` | Inherit parent text color instead of defaults |
| `icon-color-light` | `string\|null` | `'text-orange-600'` | Tailwind color for light mode icon |
| `icon-color-dark` | `string\|null` | `'text-blue-400'` | Tailwind color for dark mode icon |
| `border-color-light` | `string\|null` | `'border-orange-300 dark:border-blue-600'` | Border in light mode (button/square-button) |
| `border-color-dark` | `string\|null` | `'border-orange-400 dark:border-blue-500'` | Border in dark mode (button/square-button) |
| `icon-light` | `string\|null` | `null` | Heroicon name for light mode |
| `icon-dark` | `string\|null` | `null` | Heroicon name for dark mode |
| `label` | `string\|null` | `null` | Visible label text (button mode) |
| `label-position` | `string` | `'right'` | `left` or `right` |
| `label-class` | `string\|null` | `null` | Custom label CSS |
| `aria-label` | `string\|null` | `'Toggle theme'` | Custom aria-label |

## Display Modes

### Icon (default)

A bare icon toggle — just a sun/moon SVG.

```blade
<x-bt-toggle-theme />
```

### Button

A rounded pill button with border and optional label.

```blade
<x-bt-toggle-theme mode="button" label="Dark Mode" />
<x-bt-toggle-theme mode="button" label="Theme" label-position="left" />
```

### Square Button

A square button with fixed dimensions.

```blade
<x-bt-toggle-theme mode="square-button" />
```

## Sizes

```blade
<x-bt-toggle-theme size="xs" />  {{-- w-2 h-2 --}}
<x-bt-toggle-theme size="sm" />  {{-- w-3 h-3 --}}
<x-bt-toggle-theme size="md" />  {{-- w-4 h-4 (default) --}}
<x-bt-toggle-theme size="lg" />  {{-- w-5 h-5 --}}
<x-bt-toggle-theme size="xl" />  {{-- w-6 h-6 --}}
<x-bt-toggle-theme size="2xl" /> {{-- w-8 h-8 --}}
```

## Custom Colors

```blade
<x-bt-toggle-theme
    icon-color-light="text-yellow-500"
    icon-color-dark="text-indigo-400"
/>

<x-bt-toggle-theme
    mode="button"
    border-color-light="border-emerald-300 dark:border-emerald-600"
    border-color-dark="border-emerald-500 dark:border-emerald-400"
/>
```

## Custom Icons

Use Heroicon names:

```blade
<x-bt-toggle-theme icon-light="sun" icon-dark="moon" />
```

Or provide fully custom SVG/HTML via slots:

```blade
<x-bt-toggle-theme>
    <x-slot:icon-light><span>☀️</span></x-slot:icon-light>
    <x-slot:icon-dark><span>🌙</span></x-slot:icon-dark>
</x-bt-toggle-theme>
```

## FOUC Prevention

`@BeartropyAssets` handles FOUC prevention automatically via four layers:

1. **Inline CSS** — `html.dark{color-scheme:dark}` for native form controls (no JS needed)
2. **Inline script** — applies `dark` class synchronously before body renders
3. **`data-navigate-once`** — prevents `wire:navigate` from removing CSS/JS during navigation
4. **MutationObserver** — catches `wire:navigate` morphing `<html>` class and re-applies `dark` before repaint

### Server-Side Rendering (recommended)

For zero-FOUC on the very first paint, add `@beartropyHtmlClass` to your `<html>` tag:

```blade
<html lang="en" class="@beartropyHtmlClass">
```

This reads the `bt_theme` cookie (set automatically by the toggle) and renders the `dark` class server-side. The browser receives `<html class="dark">` in the initial HTML — no JS dependency.

### Manual Asset Loading

If you load assets via `@vite` without `@BeartropyAssets`, add `<x-bt-theme-head />` to your layout's `<head>` before stylesheets:

```blade
<head>
    <x-bt-theme-head />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

See [Theme Head](theme-head.md) for details.

## How It Works

1. **Inline CSS** sets `color-scheme` based on `.dark` class — native form controls respect dark mode immediately
2. **Inline script** reads `localStorage.theme`, applies `dark` class + `colorScheme` to `<html>`, sets `bt_theme` cookie
3. **MutationObserver** watches `<html>` class — if anything removes `dark` (e.g., Livewire morph), it's reapplied before the browser repaints
4. **Alpine component** manages toggle interaction, rotation animation, and persistence
5. **Custom event** `theme-change` is dispatched on toggle for cross-component sync
6. **Global `window.__setTheme(mode)`** function allows external code to set theme programmatically

## Keyboard Shortcuts

The toggle is a `<button>` element, so it's focusable and activatable via `Enter`/`Space`.

## Accessibility

- All modes render as `<button type="button">` with `aria-label` and `:aria-pressed`
- Default `aria-label`: "Toggle theme" (localized)
- When a visible label is provided, it's used as the `aria-label` automatically
