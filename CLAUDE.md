# Claude Code Context - Beartropy UI

> This file provides Claude Code with essential context about the Beartropy UI project.

## Project Overview

**Beartropy UI** is a comprehensive UI component library for the TALL stack (Tailwind, Alpine, Laravel, Livewire). It provides 40+ production-ready components for building beautiful, reactive web applications.

- **Package Name**: `beartropy/ui`
- **Type**: Laravel Package (Composer)
- **Stack**: TALL (Tailwind, Alpine, Laravel, Livewire)
- **PHP Version**: >= 8.0
- **Laravel Version**: >= 10.x
- **License**: MIT

## Project Structure

```
beartropy/ui/
├── src/                          # PHP source code
│   ├── Components/               # Component classes
│   │   ├── BeartropyComponent.php
│   │   ├── Button.php
│   │   ├── Input.php
│   │   ├── Select.php
│   │   └── ... (40+ components)
│   ├── Commands/                 # Artisan commands
│   ├── Support/                  # Helper classes
│   ├── Traits/                   # Reusable traits
│   ├── BeartropyUiServiceProvider.php
│   ├── helpers.php               # Global helper functions
│   └── routes.php
│
├── resources/
│   ├── views/                    # Blade templates
│   │   └── components/           # Component views
│   ├── js/                       # JavaScript
│   │   ├── index.js
│   │   └── beartropy-ui.js (built)
│   └── css/                      # Styles
│
├── config/                       # Configuration
│   └── beartropyui.php
│
├── tests/                        # Pest tests
│   ├── Feature/
│   └── Unit/
│
├── .claude/skills/               # Claude Code skills
├── docs/ai-assistants/           # AI assistant docs
│
├── build.mjs                     # esbuild config
├── tailwind.config.js            # Tailwind config
├── composer.json
└── package.json
```

## Component Naming Convention

### PHP Classes
- Location: `src/Components/`
- Namespace: `Beartropy\Ui\Components`
- Naming: PascalCase (e.g., `Button.php`, `FileInput.php`)
- Base class: Extend `BeartropyComponent` or `InputBase`

### Blade Views
- Location: `resources/views/components/`
- Naming: kebab-case (e.g., `button.blade.php`, `file-input.blade.php`)
- Usage: `<x-beartropy-ui::button>` or `<x-bt-button>` (alias)

### Component Aliases
All components support both prefixes:
- Full: `<x-beartropy-ui::input />`
- Short: `<x-bt-input />` ✅ **Preferred in code examples**

## Key Technologies

### Frontend
- **Tailwind CSS** - Utility-first styling with dark mode
- **Alpine.js** - Lightweight reactivity (bundled with Livewire)
- **Heroicons** - Icon set (via `blade-ui-kit/blade-heroicons`)
- **esbuild** - JavaScript bundling

### Backend
- **Laravel** - PHP framework
- **Livewire 3** - Reactive components
- **Blade** - Templating engine

### Build Tools
- **esbuild** - Fast JavaScript bundler
- **npm** - Package management
- **Tailwind JIT** - Just-in-time compilation

## Development Workflow

### Building Assets
```bash
npm run build      # Production build
npm run watch      # Development with watch mode
```

### Testing
```bash
vendor/bin/pest    # Run all tests
vendor/bin/pest --filter ComponentTest  # Run specific test
```

### Code Style
- **PSR-12** - PHP coding standard
- **Conventional Commits** - Commit message format
- **PHP 8.0+** - Modern PHP features (promoted properties, attributes, etc.)

## Important Files

### Service Provider
`src/BeartropyUiServiceProvider.php` - Registers components, publishes assets, loads views

### Base Component
`src/Components/BeartropyComponent.php` - Base class for all components with:
- Magic attribute handling (sizes, colors, variants)
- Preset management
- Common utilities

### Config File
`config/beartropyui.php` - Configuration for:
- Component presets (default sizes, colors)
- Icon settings
- Dark mode options

### Helpers
`src/helpers.php` - Global helper functions (if any)

## Component Architecture

### Standard Component Structure

**PHP Class Example:**
```php
<?php

namespace Beartropy\Ui\Components;

class ComponentName extends BeartropyComponent
{
    public function __construct(
        public ?string $label = null,
        public ?string $size = null,
        public ?string $color = null,
        // ... more props
    ) {
        // Initialization
    }

    public function render()
    {
        return view('beartropy-ui::component-name');
    }
}
```

**Blade View Example:**
```blade
@php
    [$colorPreset, $sizePreset] = $getComponentPresets('component-name');
    // Component logic
@endphp

<div {{ $attributes->merge(['class' => '...']) }}>
    {{-- Component markup --}}
    {{ $slot }}
</div>
```

## Magic Attributes System

Components support "magic attributes" for sizes, colors, and variants:

**Sizes**: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`
**Colors**: `primary`, `secondary`, `success`, `warning`, `danger`, `info`
**Variants**: `solid`, `outline`, `ghost`, `link`

**Usage:**
```blade
<x-bt-button primary lg>Large Primary Button</x-bt-button>
```

Handled by `BeartropyComponent::__get()` magic method.

## Preset System

Presets define default styling for components, configured in `config/beartropyui.php`:

```php
'presets' => [
    'button' => [
        'sizes' => [...],
        'colors' => [...],
        'variants' => [...],
    ],
],
```

Accessed via `$getComponentPresets()` helper in Blade views.

## Icon System

Uses **Heroicons** via Blade UI Kit:
- Icon names: Use plain names like `"envelope"`, `"user"`, `"magnifying-glass"`
- Component: `<x-beartropy-ui::icon name="envelope" />`
- In props: `iconStart="envelope"`

## Livewire Integration

Components are designed for seamless Livewire 3 integration:
- Support `wire:model`, `wire:click`, etc.
- Automatic validation error display
- Loading states with spinners
- Reactive updates

## Testing Strategy

Using **Pest PHP** with Livewire testing:
- Component rendering tests
- Livewire interaction tests
- Validation tests
- Accessibility tests

## Building New Components

### Checklist for New Components

1. **Create PHP Class** in `src/Components/`
   - Extend `BeartropyComponent` or appropriate base
   - Define public properties
   - Implement `render()` method
   - Add PHPDoc with `@property` for magic attributes

2. **Create Blade View** in `resources/views/components/`
   - Use kebab-case naming
   - Support slots where appropriate
   - Use preset system for styling
   - Handle wire:model for form components

3. **Add to Config** (if needed)
   - Define presets in `config/beartropyui.php`

4. **Write Tests** in `tests/Feature/`
   - Test rendering
   - Test Livewire integration
   - Test validation (for form components)

5. **Update Documentation**
   - Add to AI assistant guides
   - Include examples

## Common Patterns

### Form Components
- Extend `InputBase` or `InputTriggerBase`
- Support `wire:model`
- Auto-display validation errors
- Include loading spinners
- Support icons, hints, labels

### Display Components
- Extend `BeartropyComponent`
- Support color variants
- Support sizes
- Use slots for flexibility

### Overlay Components
- Use Alpine.js for interactions
- Support `wire:model` for visibility
- Include slots for title, content, footer
- Handle close/cancel actions

## Conventions

### Blade Components
- Always support `$attributes`
- Merge classes properly: `$attributes->merge(['class' => ...])`
- Use slots for flexible content
- Provide sensible defaults

### PHP Components
- Use promoted properties (PHP 8.0+)
- Type hint where possible
- Provide default values
- Document with PHPDoc

### JavaScript
- Keep minimal - leverage Alpine.js
- Bundle with esbuild
- Export as IIFE for browser

### CSS
- Use Tailwind utilities
- Support dark mode with `dark:` variants
- Avoid custom CSS when possible
- Use preset system for consistency

## Skills Available

Claude Code skills are in `.claude/skills/`:
- **beartropy-setup** - Installation & configuration
- **beartropy-form** - Form building
- **beartropy-component** - Component docs
- **beartropy-livewire** - Livewire patterns
- **beartropy-patterns** - UI patterns

Use them with `/skill-name` in Claude Code.

## Guidelines for AI Assistance

### When Helping with This Project

1. **Always use `x-bt-` prefix** in examples (not `x-beartropy-ui::`)
2. **Follow PSR-12** coding standard for PHP
3. **Use Pest** for tests (not PHPUnit syntax)
4. **Support Livewire 3** patterns
5. **Include dark mode** in all components
6. **Use Tailwind utilities** over custom CSS
7. **Provide complete examples** that are copy-paste ready
8. **Follow existing component patterns** for consistency

### Component Examples Should Include
- PHP class with proper type hints
- Blade view with presets
- Usage example with Livewire
- Props documentation
- Slots documentation

### Avoid
- Creating components without reading existing ones first
- Using outdated Livewire 2 syntax
- Skipping dark mode support
- Creating custom CSS instead of Tailwind
- Breaking existing component patterns

## Package Publishing

This is a **public package** on Packagist:
- Published as `beartropy/ui`
- Versioned using semantic versioning
- Tagged releases in git

## Resources

- **Documentation**: https://beartropy.com/ui
- **Repository**: https://github.com/beartropy/ui
- **Packagist**: https://packagist.org/packages/beartropy/ui
- **Issues**: https://github.com/beartropy/ui/issues

---

**When working on Beartropy UI, always prioritize component consistency, Livewire integration, and developer experience.**
