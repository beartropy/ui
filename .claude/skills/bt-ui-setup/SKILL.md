---
name: bt-ui-setup
description: Help users install and configure Beartropy UI in their Laravel/Livewire projects
version: 2.0.0
author: Beartropy
tags: [beartropy, installation, setup, configuration, getting-started]
---

# Beartropy UI Setup Guide

You are an expert in helping users install and configure Beartropy UI in their Laravel/Livewire applications.

---

## Requirements

- PHP >= 8.1
- Laravel >= 11.x
- Livewire 3.x
- Tailwind CSS configured
- Alpine.js (comes with Livewire 3)

---

## Installation

### Step 1: Install via Composer

```bash
composer require beartropy/ui
```

### Step 2: Include Assets

Add the `@BeartropyAssets` Blade directive to your layout file:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @BeartropyAssets

    @livewireStyles
</head>
<body>
    {{ $slot }}

    @livewireScripts
</body>
</html>
```

The `@BeartropyAssets` directive automatically includes the required CSS and JS files with cache-busting version parameters.

### Step 3: Configure Tailwind CSS

Add the Beartropy UI preset and content paths to your `tailwind.config.js`:

```javascript
const beartropyPreset = require('./vendor/beartropy/ui/tailwind.config.js');

export default {
    presets: [beartropyPreset],
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
        './vendor/beartropy/ui/resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {},
    },
    plugins: [],
}
```

The preset adds the `beartropy` custom color palette. Including the vendor views path ensures Tailwind generates the utility classes used by Beartropy components.

### Step 4: Rebuild Assets

```bash
npm run build
```

Or for development:

```bash
npm run dev
```

### Step 5: Verify Installation

Create a quick test:

```blade
{{-- resources/views/beartropy-test.blade.php --}}
<div class="max-w-md mx-auto p-6 space-y-4">
    <x-bt-alert success>Beartropy UI is working!</x-bt-alert>
    <x-bt-button primary>Test Button</x-bt-button>
    <x-bt-input label="Test Input" placeholder="Type something..." iconStart="user" />
</div>
```

---

## Publish & Customize

### Publish Configuration

```bash
php artisan vendor:publish --tag=beartropy-ui-config
```

This creates `config/beartropyui.php` where you can set global defaults.

### Publish All Presets

```bash
php artisan vendor:publish --tag=beartropy-ui-presets
```

Presets define the Tailwind classes for each component's colors, sizes, and variants. Published presets go to `resources/views/vendor/beartropy/ui/presets/`.

### Publish Individual Preset

```bash
php artisan vendor:publish --tag=beartropyui-preset-button
php artisan vendor:publish --tag=beartropyui-preset-input
php artisan vendor:publish --tag=beartropyui-preset-select
```

Replace `button`, `input`, `select` with any component name.

### Publish Views

```bash
php artisan vendor:publish --tag=beartropy-ui-views
```

Published views go to `resources/views/vendor/beartropy-ui/` for full template customization.

---

## Configuration

### Component Defaults (`config/beartropyui.php`)

Set global defaults for colors, sizes, and behavior:

```php
<?php

return [
    'component_defaults' => [
        'button' => [
            'color' => env('BEARTROPY_UI_BUTTON_COLOR', 'beartropy'),
            'size' => env('BEARTROPY_UI_BUTTON_SIZE', 'md'),
            'variant' => env('BEARTROPY_UI_BUTTON_VARIANT', 'solid'),
        ],
        'input' => [
            'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
            'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
        ],
        'select' => [
            'color' => env('BEARTROPY_UI_SELECT_COLOR', 'beartropy'),
        ],
        'alert' => [
            'color' => env('BEARTROPY_UI_ALERT_COLOR', 'beartropy'),
            'variant' => env('BEARTROPY_UI_ALERT_VARIANT', 'soft'),
        ],
        // ... other components
    ],

    'icons' => [
        'variant' => 'outline', // 'outline' or 'solid'
    ],
];
```

### Adding Custom Color Presets

Use the artisan command to add a custom color:

```bash
php artisan beartropy:add-preset
```

Or manually edit a published preset file. Each color is an array of Tailwind class strings for different states (normal, hover, focus, error, disabled, etc.).

---

## AI Coding Skills

Install Beartropy skills for AI assistants:

```bash
# Install for Claude Code (default)
php artisan beartropy:skills

# Install for a specific agent
php artisan beartropy:skills --agent=cursor
php artisan beartropy:skills --agent=copilot
php artisan beartropy:skills --agent=windsurf
php artisan beartropy:skills --agent=codex

# Install for all agents
php artisan beartropy:skills --agent=all

# Update after upgrading Beartropy UI
php artisan beartropy:skills --force

# Remove all skills
php artisan beartropy:skills --remove
```

Available skills:
- `/bt-ui-setup` — Installation and configuration
- `/bt-ui-form` — Form building with validation
- `/bt-ui-component` — Complete component reference
- `/bt-ui-livewire` — Livewire integration patterns
- `/bt-ui-patterns` — Production-ready UI patterns

---

## Common Issues & Solutions

### Components not rendering / blank page

1. Ensure `@BeartropyAssets` is in your layout's `<head>`
2. Check that Tailwind content paths include `vendor/beartropy/ui/resources/views/**/*.blade.php`
3. Rebuild assets: `npm run build`
4. Clear cache: `php artisan view:clear`

### Icons not showing

1. Beartropy UI includes Blade Heroicons — no separate install needed
2. Check icon names at heroicons.com
3. Use `outline` (default) or `solid` variant via config

### Styles broken / no dark mode

1. Add `darkMode: 'class'` to your `tailwind.config.js`
2. Ensure the Beartropy preset is in your `presets` array
3. Rebuild: `npm run build`

### Validation errors not showing

1. Ensure Livewire 3.x is installed
2. Check `wire:model` property names match Livewire component properties
3. Verify validation rules are defined

### Livewire features not working

1. Ensure `@livewireStyles` and `@livewireScripts` are in your layout
2. For Livewire 3, Alpine.js is included automatically
3. Check that your component extends `Livewire\Component`

---

## Upgrading

```bash
composer update beartropy/ui
php artisan view:clear
npm run build

# Update AI skills to match new version
php artisan beartropy:skills --force
```

---

## Next Steps

1. Explore available components — use `/bt-ui-component` skill
2. Build a form — use `/bt-ui-form` skill
3. See full page patterns — use `/bt-ui-patterns` skill
4. Learn Livewire integration — use `/bt-ui-livewire` skill
