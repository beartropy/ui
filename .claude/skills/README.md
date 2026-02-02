# Beartropy UI - Claude Code Skills

This directory contains public Claude Code skills to help developers use Beartropy UI components in their Laravel/Livewire applications.

## Available Skills

### 🚀 `/beartropy-setup`
**Installation and configuration guide**

Get help installing Beartropy UI, configuring Tailwind CSS, publishing assets, and troubleshooting common setup issues.

**Use when:**
- Installing Beartropy UI for the first time
- Having trouble with configuration
- Components not rendering correctly
- Need to verify installation

**Example:** "Help me install Beartropy UI in my Laravel project"

---

### 📝 `/beartropy-form`
**Form building with Beartropy UI components**

Create forms using Beartropy UI components with proper Livewire integration, validation, and loading states.

**Use when:**
- Building a new form
- Need form validation examples
- Want to integrate with Livewire
- Creating contact forms, login forms, registration, etc.

**Example:** "Create a contact form with name, email, and message fields"

---

### 🧩 `/beartropy-component`
**Detailed component documentation and examples**

Get comprehensive information about any specific Beartropy UI component including all props, slots, examples, and best practices.

**Use when:**
- Learning about a specific component
- Need to see all available props
- Want advanced usage examples
- Looking for component-specific patterns

**Example:** "Show me how to use the Select component with remote data"

---

### ⚡ `/beartropy-livewire`
**Livewire integration patterns**

Learn best practices for integrating Beartropy UI with Laravel Livewire including wire:model, validation, loading states, and reactive patterns.

**Use when:**
- Need to understand Livewire integration
- Building reactive/live search
- Implementing real-time validation
- Working with modals and dynamic content
- Managing form state with Livewire

**Example:** "Show me how to create a live search with Beartropy components"

---

### 🎨 `/beartropy-patterns`
**Common UI patterns and complete examples**

Get production-ready examples of common UI patterns like login pages, data tables, settings pages, and more.

**Use when:**
- Building a complete page/feature
- Need a login or registration page
- Creating a data table with filters
- Building a settings page with tabs
- Want complete Livewire + Blade examples

**Example:** "Help me create a user management table with search and filters"

---

## How to Use These Skills

### In Claude Code CLI

Simply invoke a skill by typing `/` followed by the skill name:

```
/beartropy-form
```

Then describe what you need:

```
Create a user registration form with name, email, password, and terms checkbox
```

### Combining Skills

You can use multiple skills in sequence:

1. Start with `/beartropy-setup` to install
2. Use `/beartropy-form` to create your first form
3. Reference `/beartropy-component` for specific component details
4. Use `/beartropy-livewire` for advanced reactive features
5. Check `/beartropy-patterns` for complete page examples

## Component Overview

Beartropy UI provides 40+ components for the TALL stack:

**Form Components:** Input, Textarea, Select, Checkbox, Radio, Toggle, FileInput, FileDropzone, Datetime, TimePicker, Slider, Lookup

**Buttons:** Button, ButtonIcon, Fab

**Display:** Alert, Badge, Avatar, Card, Tag, Skeleton, Toast

**Overlays:** Modal, Dialog, Dropdown, Tooltip, CommandPalette

**Layout:** Layout, Nav, Sidebar, Header, Menu, Table

**Special:** ChatInput, Icon, Loading, ToggleTheme

## Quick Examples

### Simple Form
```blade
<form wire:submit="save">
   <x-bt-input
        wire:model="email"
        label="Email"
        type="email"
        iconStart="envelope"
    />

   <x-bt-button type="submit" primary>
        Submit
    </x-bt-button>
</form>
```

### Search with Live Updates
```blade
<x-beartropy-ui::input
    wire:model.live.debounce.300ms="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

### Select with Options
```blade
<x-beartropy-ui::select
    wire:model="status"
    :options="['active' => 'Active', 'pending' => 'Pending']"
    label="Status"
    searchable
/>
```

## Requirements

- PHP >= 8.0
- Laravel >= 10.x
- Livewire 3.x
- Tailwind CSS
- Alpine.js (included with Livewire 3)

## Installation

```bash
composer require beartropy/ui
```

For detailed installation instructions, use the `/beartropy-setup` skill.

## Resources

- **Documentation:** https://beartropy.com/ui
- **GitHub:** https://github.com/beartropy/ui
- **Heroicons:** https://heroicons.com (for icon names)

## Contributing

These skills are part of the Beartropy UI package. To suggest improvements:

1. Open an issue at https://github.com/beartropy/ui/issues
2. Include the skill name and suggested changes
3. Provide examples of how it would help users

## License

These skills are provided under the MIT License as part of Beartropy UI.

---

**Made with ❤️ for the TALL stack community**

Use these skills to build beautiful, reactive UIs faster with Beartropy UI and Laravel Livewire!
