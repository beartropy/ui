---
name: beartropy-setup
description: Help users install and configure Beartropy UI in their Laravel/Livewire projects
version: 1.0.0
author: Beartropy
tags: [beartropy, installation, setup, configuration, getting-started]
---

# Beartropy UI Setup Guide

You are an expert in helping users install and configure Beartropy UI in their Laravel/Livewire applications.

## Your Task

When users need help with installation or setup:

1. **Determine their current setup** - Ask about Laravel version, Livewire version, and what they've installed
2. **Guide through installation** - Provide step-by-step installation instructions
3. **Help with configuration** - Assist with config file customization
4. **Troubleshoot issues** - Help resolve common installation problems
5. **Verify installation** - Show how to test that everything works

## Installation Steps

### Requirements

Before installing Beartropy UI, ensure:
- PHP >= 8.0
- Laravel >= 10.x
- Livewire 3.x
- Tailwind CSS configured
- Alpine.js included (comes with Livewire 3)

### Step 1: Install via Composer

```bash
composer require beartropy/ui
```

### Step 2: Publish Assets (Optional)

Publish configuration and views if you want to customize:

```bash
# Publish config file
php artisan vendor:publish --tag=beartropy-ui-config

# Publish views (for customization)
php artisan vendor:publish --tag=beartropy-ui-views

# Publish all
php artisan vendor:publish --provider="Beartropy\Ui\BeartropyUiServiceProvider"
```

### Step 3: Include CSS and JS

Add Beartropy UI assets to your layout:

**In your app layout (e.g., `resources/views/layouts/app.blade.php`):**

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Beartropy UI Styles --}}
    <link rel="stylesheet" href="{{ asset('vendor/beartropy-ui/css/beartropy-ui.css') }}">

    @livewireStyles
</head>
<body>
    {{ $slot }}

    {{-- Beartropy UI Scripts --}}
    <script src="{{ asset('vendor/beartropy-ui/js/beartropy-ui.js') }}"></script>

    @livewireScripts
</body>
</html>
```

### Step 4: Configure Tailwind CSS

Update your `tailwind.config.js` to include Beartropy UI paths:

```javascript
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
        './vendor/beartropy/ui/resources/views/**/*.blade.php', // Add this line
    ],
    darkMode: 'class',
    theme: {
        extend: {},
    },
    plugins: [],
}
```

### Step 5: Verify Installation

Create a simple test view to verify installation:

**routes/web.php:**
```php
Route::get('/beartropy-test', function () {
    return view('beartropy-test');
});
```

**resources/views/beartropy-test.blade.php:**
```blade
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beartropy UI Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Beartropy UI Installation Test
        </h1>

       <x-bt-alert success>
            ✓ Beartropy UI is working correctly!
        </x-bt-alert>

       <x-bt-card>
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Test Components</h2>

            <div class="space-y-4">
               <x-bt-button primary>
                    Primary Button
                </x-bt-button>

               <x-bt-input
                    label="Test Input"
                    placeholder="Type something..."
                    iconStart="user"
                />

               <x-bt-select
                    :options="['option1' => 'Option 1', 'option2' => 'Option 2']"
                    label="Test Select"
                    placeholder="Choose an option"
                />

               <x-bt-checkbox
                    label="Test Checkbox"
                    description="This is a test checkbox"
                />
            </div>
        </x-bt-card>

       <x-bt-card>
            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Next Steps</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-400">
                <li>Explore all available components</li>
                <li>Use <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">/beartropy-form</code> skill to create forms</li>
                <li>Check <code class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">/beartropy-patterns</code> for complete examples</li>
            </ul>
        </x-bt-card>
    </div>
</body>
</html>
```

Visit `/beartropy-test` to verify components render correctly. You should see styled components with proper colors, icons, and interactions.

## Configuration

### Config File (config/beartropyui.php)

After publishing, you can customize:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Component Presets
    |--------------------------------------------------------------------------
    |
    | Define default sizes, colors, and variants for components.
    |
    */
    'presets' => [
        'button' => [
            'size' => 'md',
            'variant' => 'solid',
            'color' => 'primary',
        ],
        'input' => [
            'size' => 'md',
            'color' => 'primary',
        ],
        // ... other component defaults
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which icon set to use (heroicons, fontawesome, etc.)
    |
    */
    'icons' => [
        'set' => 'heroicons',
        'variant' => 'outline', // outline or solid
    ],

    /*
    |--------------------------------------------------------------------------
    | Dark Mode
    |--------------------------------------------------------------------------
    |
    | Enable or disable dark mode support
    |
    */
    'dark_mode' => true,
];
```

### Customizing Component Presets

You can add custom presets using the artisan command:

```bash
php artisan beartropy:add-preset
```

Or define them in the config file:

```php
'presets' => [
    'button' => [
        'colors' => [
            'brand' => [
                'solid' => 'bg-purple-600 hover:bg-purple-700 text-white',
                'outline' => 'border-purple-600 text-purple-600 hover:bg-purple-50',
            ],
        ],
    ],
],
```

Then use:
```blade
<x-beartropy-ui::button brand>Custom Color</x-bt-button>
```

## Common Issues & Solutions

### Issue: Components not rendering / blank page

**Solution:**
1. Ensure you've included Beartropy UI CSS and JS in your layout
2. Check that Tailwind content paths include Beartropy UI
3. Rebuild your assets: `npm run build` or `npm run dev`
4. Clear cache: `php artisan view:clear`

### Issue: Icons not showing

**Solution:**
1. Verify Heroicons package is installed: `composer require blade-ui-kit/blade-heroicons`
2. Check icon names are valid Heroicon names (see https://heroicons.com)
3. Ensure icon set is configured in config file

### Issue: Styles look broken / no dark mode

**Solution:**
1. Add `darkMode: 'class'` to your `tailwind.config.js`
2. Ensure Beartropy UI paths are in Tailwind content array
3. Rebuild Tailwind: `npm run build`

### Issue: Livewire validation not showing

**Solution:**
1. Ensure you're using Livewire 3.x
2. Check property names match between `wire:model` and Livewire component
3. Verify validation rules are defined in component

### Issue: Alpine.js features not working

**Solution:**
1. Livewire 3 includes Alpine.js automatically
2. If using Livewire 2, manually include Alpine: `<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>`

## Project Structure

After installation, your project should look like:

```
your-app/
├── app/
│   └── Livewire/
│       └── YourComponents.php
├── config/
│   └── beartropyui.php (after publishing)
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── livewire/
│       │   └── your-components.blade.php
│       └── vendor/
│           └── beartropy-ui/ (after publishing views)
├── tailwind.config.js
├── package.json
└── composer.json
```

## Quick Start Example

Once installed, you can immediately start using components in your Blade views or Livewire components.

**Simple standalone test (no Livewire required):**

Create a route and view to test components:

```php
// routes/web.php
Route::get('/demo', function () {
    return view('demo');
});
```

```blade
{{-- resources/views/demo.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beartropy UI Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="max-w-md mx-auto p-6 space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Component Demo</h1>

       <x-bt-card>
            <div class="space-y-4">
               <x-bt-button primary>
                    Click Me
                </x-bt-button>

               <x-bt-input
                    label="Your Name"
                    placeholder="John Doe"
                    iconStart="user"
                />

               <x-bt-checkbox
                    label="I agree to the terms"
                />
            </div>
        </x-bt-card>

       <x-bt-alert success>
            Components are working! 🎉
        </x-bt-alert>
    </div>
</body>
</html>
```

Visit `/demo` to see the components in action!

**For interactive forms with Livewire, use the `/beartropy-form` skill to get complete examples.**

## Next Steps

After installation:
1. Explore available components in the documentation
2. Try building a form using `beartropy-form` skill
3. Check out UI patterns with `beartropy-patterns` skill
4. Learn Livewire integration with `beartropy-livewire` skill

## Upgrading

To upgrade to the latest version:

```bash
composer update beartropy/ui
php artisan view:clear
npm run build
```

## Getting Help

- Documentation: https://beartropy.com/ui
- GitHub Issues: https://github.com/beartropy/ui/issues
- Use Claude Code skills: `/beartropy-form`, `/beartropy-component`, etc.

When helping users with setup, always ask about their current environment first, then provide tailored instructions.
