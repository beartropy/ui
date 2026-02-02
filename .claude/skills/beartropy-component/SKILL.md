---
name: beartropy-component
description: Get detailed information and examples for specific Beartropy UI components
version: 1.0.0
author: Beartropy
tags: [beartropy, ui, components, documentation, examples]
---

# Beartropy Component Helper

You are an expert in Beartropy UI components and can provide detailed information, examples, and best practices for any component.

## Your Task

When a user asks about a specific Beartropy UI component:

1. **Identify the component** they're asking about
2. **Provide complete documentation** including:
   - Component description and use cases
   - All available props with types and defaults
   - Available slots
   - Magic attributes (size, color, variant)
   - Livewire integration examples
   - Alpine.js integration examples if applicable
3. **Show practical examples** ranging from basic to advanced usage
4. **Include common patterns** and best practices
5. **Mention related components** that work well together

## Available Components

### Form Components
- **Input** - Text, email, password, number inputs with icons and validation
- **Textarea** - Multi-line text input
- **Select** - Single/multiple select with search, remote data, and object mapping
- **Checkbox** - Checkbox with labels and descriptions
- **Radio** - Radio button inputs
- **RadioGroup** - Group of radio buttons
- **Toggle** - Toggle switch component
- **FileInput** - File upload with preview
- **FileDropzone** - Drag-and-drop file upload
- **Datetime** - Date and time picker
- **TimePicker** - Time selection
- **Slider** - Range slider input
- **Lookup** - Advanced searchable select with remote data

### Button Components
- **Button** - Primary action button with variants
- **ButtonIcon** - Icon-only button
- **Fab** - Floating action button

### Display Components
- **Alert** - Alert messages with different severities
- **Badge** - Small status badges
- **Avatar** - User avatar with fallback
- **Card** - Content card container
- **Tag** - Removable tag/chip
- **Skeleton** - Loading skeleton placeholder
- **Toast** - Toast notifications

### Overlay Components
- **Modal** - Modal dialog
- **Dialog** - Dialog with actions
- **Dropdown** - Dropdown menu
- **Tooltip** - Hover tooltips
- **CommandPalette** - Command palette/search

### Layout Components
- **Layout** - Page layout wrapper
- **Nav** - Navigation component
- **Sidebar** - Sidebar navigation
- **Header** - Page header
- **Menu** - Menu component
- **Table** - Data table

### Special Components
- **ChatInput** - Chat message input with file upload
- **Icon** - Icon component (uses Heroicons)
- **Loading** - Loading spinner
- **ToggleTheme** - Dark/light mode toggle

## Component Deep Dives

### Input Component

**Description:** Versatile input component supporting text, email, password, number and other input types with icons, clear button, password toggle, and copy functionality.

**Usage:**
```blade
<x-beartropy-ui::input
    wire:model="email"
    label="Email Address"
    type="email"
    placeholder="Enter your email"
    iconStart="envelope"
    hint="We'll never share your email"
/>
```

**All Props:**
- `label` (string) - Field label
- `placeholder` (string) - Placeholder text
- `type` (string) - Input type: text, email, password, number, url, tel, search, date, etc.
- `value` (mixed) - Initial value
- `iconStart` (string) - Heroicon name for start icon
- `iconEnd` (string) - Heroicon name for end icon
- `clearable` (bool, default: true) - Show clear button when field has value
- `copyButton` (bool, default: false) - Show copy-to-clipboard button
- `hint` (string) - Help text below input
- `help` (string) - Alternative to hint
- `customError` (string) - Custom error message (overrides validation errors)
- `spinner` (bool, default: true) - Show loading spinner on wire:loading
- `size` (string) - xs, sm, md, lg, xl, 2xl
- `color` (string) - primary, secondary, success, warning, danger, info

**Magic Attributes:**
```blade
<x-beartropy-ui::input wire:model="name" lg primary /> {{-- Large size, primary color --}}
```

**Slots:**
- `start` - Custom content at start (overrides iconStart)
- `end` - Custom content at end (overrides iconEnd)

**Examples:**

Basic text input:
```blade
<x-beartropy-ui::input
    wire:model="username"
    label="Username"
    placeholder="Enter username"
/>
```

Email with icon:
```blade
<x-beartropy-ui::input
    wire:model="email"
    type="email"
    label="Email"
    iconStart="envelope"
    placeholder="you@example.com"
/>
```

Password with toggle:
```blade
<x-beartropy-ui::input
    wire:model="password"
    type="password"
    label="Password"
    iconStart="lock-closed"
/>
```

Search with live updates:
```blade
<x-beartropy-ui::input
    wire:model.live.debounce.300ms="search"
    type="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

Number input:
```blade
<x-beartropy-ui::input
    wire:model="quantity"
    type="number"
    label="Quantity"
    min="1"
    max="100"
/>
```

With custom error:
```blade
<x-beartropy-ui::input
    wire:model="username"
    label="Username"
    customError="{{ $usernameError }}"
/>
```

With copy button:
```blade
<x-beartropy-ui::input
    value="{{ $apiKey }}"
    label="API Key"
    copyButton
    readonly
/>
```

---

### Select Component

**Description:** Powerful select component with search, multi-select, remote data fetching, and object mapping.

**Basic Usage:**
```blade
<x-beartropy-ui::select
    wire:model="countryId"
    :options="$countries"
    label="Country"
    searchable
/>
```

**All Props:**
- `options` (array|Collection) - Options to display
- `selected` (mixed) - Initially selected value(s)
- `label` (string) - Field label
- `placeholder` (string) - Placeholder text
- `searchable` (bool) - Enable search input
- `multiple` (bool) - Allow multiple selections
- `clearable` (bool) - Show clear button
- `remote` (bool) - Enable remote data fetching
- `remoteUrl` (string) - API endpoint for remote data
- `optionLabel` (string, default: 'name') - Object key for option label
- `optionValue` (string, default: 'id') - Object key for option value
- `optionDescription` (string) - Object key for option description
- `optionIcon` (string) - Object key for option icon
- `optionAvatar` (string) - Object key for option avatar
- `emptyMessage` (string) - Message when no options found
- `perPage` (int) - Results per page for pagination
- `hint` (string) - Help text
- `spinner` (bool, default: true) - Show loading spinner

**Slots:**
- `beforeOptions` - Content at top of dropdown
- `afterOptions` - Content at bottom of dropdown

**Examples:**

Simple array:
```blade
<x-beartropy-ui::select
    wire:model="status"
    :options="['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending']"
    label="Status"
/>
```

Object array with custom mapping:
```blade
@php
$users = [
    ['id' => 1, 'full_name' => 'John Doe', 'email' => 'john@example.com'],
    ['id' => 2, 'full_name' => 'Jane Smith', 'email' => 'jane@example.com'],
];
@endphp

<x-beartropy-ui::select
    wire:model="assignedTo"
    :options="$users"
    label="Assign To"
    optionLabel="full_name"
    optionValue="id"
    optionDescription="email"
    searchable
/>
```

Multiple selection:
```blade
<x-beartropy-ui::select
    wire:model="selectedTags"
    :options="$tags"
    label="Tags"
    multiple
    searchable
/>
```

Remote data fetching:
```blade
<x-beartropy-ui::select
    wire:model="userId"
    label="Select User"
    remote
    remoteUrl="/api/users/search"
    optionLabel="name"
    optionValue="id"
    searchable
/>
```

---

### Button Component

**Description:** Versatile button with multiple variants, sizes, colors, and loading states.

**Basic Usage:**
```blade
<x-beartropy-ui::button type="submit" primary>
    Save Changes
</x-bt-button>
```

**All Props:**
- `type` (string) - button, submit, reset
- `href` (string) - URL for link-style button
- `disabled` (bool) - Disabled state
- `iconStart` (string) - Heroicon name for start icon
- `iconEnd` (string) - Heroicon name for end icon
- `label` (string) - Button text
- `spinner` (bool, default: true) - Show spinner on wire:loading

**Variants:** `solid` (default), `outline`, `ghost`, `link`
**Colors:** `primary`, `secondary`, `success`, `warning`, `danger`, `info`
**Sizes:** `xs`, `sm`, `md`, `lg`, `xl`, `2xl`

**Examples:**

Primary button:
```blade
<x-beartropy-ui::button primary>
    Create Account
</x-bt-button>
```

With icon:
```blade
<x-beartropy-ui::button iconStart="plus" success>
    Add New
</x-bt-button>
```

Outline variant:
```blade
<x-beartropy-ui::button outline secondary>
    Cancel
</x-bt-button>
```

Ghost button:
```blade
<x-beartropy-ui::button ghost danger iconStart="trash">
    Delete
</x-bt-button>
```

Link style:
```blade
<x-beartropy-ui::button link href="/help">
    Learn More
</x-bt-button>
```

Large with loading:
```blade
<x-beartropy-ui::button
    type="submit"
    wire:click="save"
    primary
    lg
    iconStart="check"
>
    Save Changes
</x-bt-button>
```

Disabled:
```blade
<x-beartropy-ui::button disabled>
    Unavailable
</x-bt-button>
```

---

### Modal Component

**Description:** Modal dialog for overlaying content.

**Usage:**
```blade
<x-beartropy-ui::modal wire:model="showModal">
    <x-slot:title>
        Confirm Action
    </x-slot:title>

    <p>Are you sure you want to proceed?</p>

    <x-slot:footer>
       <x-bt-button wire:click="confirm" primary>
            Confirm
        </x-bt-button>
       <x-bt-button wire:click="$set('showModal', false)" outline>
            Cancel
        </x-bt-button>
    </x-slot:footer>
</x-bt-modal>
```

---

### CommandPalette Component

**Description:** Quick command palette/search interface (like Cmd+K).

**Usage:**
```blade
<x-beartropy-ui::command-palette
    wire:model="showPalette"
    :items="$searchResults"
    placeholder="Search commands..."
/>
```

---

## Integration Tips

### With Livewire
- Use `wire:model` for two-way binding
- Use `wire:model.live` for real-time updates
- Use `wire:model.debounce.300ms` for debounced updates
- Components automatically show validation errors from `$errors`

### With Alpine.js
- Components use Alpine.js internally
- You can add Alpine directives: `x-on:click`, `x-show`, etc.
- Access component state through Alpine's `$wire` magic

### Validation
- Validation errors from Livewire display automatically
- Use `customError` prop to override or show custom errors
- Combine with Laravel validation rules in Livewire components

## Best Practices

1. **Use semantic component choices** - Select the right component for the job
2. **Leverage magic attributes** for cleaner code
3. **Provide helpful `hint` text** to guide users
4. **Use appropriate sizes** - Don't make everything large
5. **Be consistent with colors** - Use a color scheme throughout your app
6. **Show loading states** - The `spinner` prop is enabled by default
7. **Combine components** - Use slots to compose complex UIs

When helping users with components, always provide working examples and explain the props clearly.
