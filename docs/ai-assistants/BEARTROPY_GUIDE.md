# Beartropy UI - Universal AI Assistant Guide

> This guide helps AI assistants generate correct code using Beartropy UI components for Laravel/Livewire applications.

## Overview

**Beartropy UI** is a comprehensive UI component library for the TALL stack (Tailwind, Alpine, Laravel, Livewire).

- **40+ Components**: Forms, buttons, modals, tables, and more
- **Livewire 3 Integration**: Reactive components with `wire:model`
- **Tailwind CSS**: Fully styled with dark mode support
- **Heroicons**: Built-in icon support

## Component Prefix

All components use the `x-bt-` prefix (short for Beartropy):

```blade
<x-bt-input />
<x-bt-button />
<x-bt-select />
```

## Form Components

### Input
**Syntax:**
```blade
<x-bt-input
    wire:model="propertyName"
    label="Field Label"
    placeholder="Enter text..."
    type="text"
    iconStart="heroicon-name"
    iconEnd="heroicon-name"
    hint="Helper text"
    clearable
    copyButton
    :spinner="true"
/>
```

**Props:**
- `label` - Field label text
- `placeholder` - Placeholder text
- `type` - text, email, password, number, url, tel, search, date, etc.
- `iconStart` / `iconEnd` - Heroicon name (without 'o-' or 'heroicon-' prefix)
- `clearable` (bool, default: true) - Show clear button
- `copyButton` (bool) - Show copy to clipboard button
- `hint` / `help` - Help text below field
- `customError` - Override validation error
- `spinner` (bool, default: true) - Show loading spinner
- Size: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`
- Color: `primary`, `secondary`, `success`, `warning`, `danger`, `info`

**Examples:**
```blade
{{-- Basic --}}
<x-bt-input wire:model="name" label="Name" />

{{-- With icon --}}
<x-bt-input
    wire:model="email"
    type="email"
    label="Email"
    iconStart="envelope"
    placeholder="you@example.com"
/>

{{-- Password with toggle --}}
<x-bt-input
    wire:model="password"
    type="password"
    label="Password"
    iconStart="lock-closed"
/>

{{-- Search with live updates --}}
<x-bt-input
    wire:model.live.debounce.300ms="search"
    type="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

### Select
**Syntax:**
```blade
<x-bt-select
    wire:model="selectedValue"
    :options="$optionsArray"
    label="Select Label"
    placeholder="Choose..."
    searchable
    multiple
    clearable
    optionLabel="name"
    optionValue="id"
    optionDescription="description"
/>
```

**Props:**
- `options` (array|Collection) - Options to display
- `label` - Field label
- `placeholder` - Placeholder text
- `searchable` (bool) - Enable search
- `multiple` (bool) - Multiple selection
- `clearable` (bool) - Show clear button
- `optionLabel` (default: 'name') - Key for label
- `optionValue` (default: 'id') - Key for value
- `optionDescription` - Key for description
- `optionIcon` / `optionAvatar` - Keys for icon/avatar

**Examples:**
```blade
{{-- Simple array --}}
<x-bt-select
    wire:model="status"
    :options="['active' => 'Active', 'inactive' => 'Inactive']"
    label="Status"
/>

{{-- Object array --}}
<x-bt-select
    wire:model="userId"
    :options="$users"
    label="Select User"
    optionLabel="name"
    optionValue="id"
    searchable
/>

{{-- Multiple selection --}}
<x-bt-select
    wire:model="tags"
    :options="$allTags"
    label="Tags"
    multiple
    searchable
/>
```

### Textarea
**Syntax:**
```blade
<x-bt-textarea
    wire:model="message"
    label="Message"
    placeholder="Enter text..."
    rows="5"
    hint="Helper text"
/>
```

### Checkbox
**Syntax:**
```blade
<x-bt-checkbox
    wire:model="agreed"
    label="Label text"
    description="Optional description"
    value="checkbox-value"
/>
```

### Toggle
**Syntax:**
```blade
<x-bt-toggle
    wire:model="enabled"
    label="Enable Feature"
    description="Optional description"
/>
```

### Radio & RadioGroup
**Syntax:**
```blade
<x-bt-radio-group wire:model="choice" label="Choose One">
    <x-bt-radio value="option1" label="Option 1" />
    <x-bt-radio value="option2" label="Option 2" />
    <x-bt-radio value="option3" label="Option 3" />
</x-bt-radio-group>
```

### File Input
**Syntax:**
```blade
<x-bt-file-input
    wire:model="document"
    label="Upload File"
    accept=".pdf,.doc,.docx"
    hint="Max 10MB"
/>
```

### Date & Time
**Syntax:**
```blade
{{-- Date and time picker --}}
<x-bt-datetime
    wire:model="appointmentDate"
    label="Appointment"
    enableTime
/>

{{-- Time only --}}
<x-bt-time-picker
    wire:model="time"
    label="Select Time"
/>
```

## Button Components

### Button
**Syntax:**
```blade
<x-bt-button
    type="submit|button|reset"
    href="url"
    iconStart="heroicon-name"
    iconEnd="heroicon-name"
    :disabled="false"
    :spinner="true"
>
    Button Text
</x-bt-button>
```

**Variants:** `solid`, `outline`, `ghost`, `link`
**Colors:** `primary`, `secondary`, `success`, `warning`, `danger`, `info`
**Sizes:** `xs`, `sm`, `md`, `lg`, `xl`, `2xl`

**Examples:**
```blade
{{-- Primary button --}}
<x-bt-button primary>Save</x-bt-button>

{{-- With icon --}}
<x-bt-button iconStart="plus" success>Add New</x-bt-button>

{{-- Outline variant --}}
<x-bt-button outline secondary>Cancel</x-bt-button>

{{-- Ghost/danger --}}
<x-bt-button ghost danger iconStart="trash">Delete</x-bt-button>

{{-- Link button --}}
<x-bt-button link href="/help">Learn More</x-bt-button>

{{-- With Livewire action --}}
<x-bt-button wire:click="save" primary>Save Changes</x-bt-button>
```

### ButtonIcon
**Syntax:**
```blade
<x-bt-button-icon
    icon="heroicon-name"
    href="url"
    wire:click="action"
/>
```

### Fab (Floating Action Button)
**Syntax:**
```blade
<x-bt-fab icon="plus" wire:click="create" />
```

## Display Components

### Alert
**Syntax:**
```blade
<x-bt-alert success|warning|danger|info>
    Alert message here
</x-bt-alert>
```

### Badge
**Syntax:**
```blade
<x-bt-badge success|warning|danger|info|primary|secondary>
    Badge text
</x-bt-badge>
```

### Card
**Syntax:**
```blade
<x-bt-card>
    <h2>Card Title</h2>
    <p>Card content here</p>
</x-bt-card>
```

### Avatar
**Syntax:**
```blade
<x-bt-avatar
    src="image-url"
    alt="Name"
    size="xs|sm|md|lg|xl|2xl"
/>
```

### Tag
**Syntax:**
```blade
<x-bt-tag
    removable
    wire:click="remove"
>
    Tag Text
</x-bt-tag>
```

## Overlay Components

### Modal
**Syntax:**
```blade
<x-bt-modal wire:model="showModal">
    <x-slot:title>Modal Title</x-slot:title>

    <p>Modal content here</p>

    <x-slot:footer>
        <x-bt-button wire:click="save" primary>Save</x-bt-button>
        <x-bt-button wire:click="$set('showModal', false)" outline>Cancel</x-bt-button>
    </x-slot:footer>
</x-bt-modal>
```

### Dialog
**Syntax:**
```blade
<x-bt-dialog wire:model="showDialog">
    <x-slot:title>Confirm Action</x-slot:title>

    <p>Are you sure?</p>

    <x-slot:footer>
        <x-bt-button wire:click="confirm" danger>Confirm</x-bt-button>
        <x-bt-button wire:click="$set('showDialog', false)" outline>Cancel</x-bt-button>
    </x-slot:footer>
</x-bt-dialog>
```

### Dropdown
**Syntax:**
```blade
<x-bt-dropdown>
    <x-slot:trigger>
        <x-bt-button>Menu</x-bt-button>
    </x-slot:trigger>

    <x-slot:content>
        <a href="/profile">Profile</a>
        <a href="/settings">Settings</a>
        <button wire:click="logout">Logout</button>
    </x-slot:content>
</x-bt-dropdown>
```

## Layout Components

### Table
**Syntax:**
```blade
<x-bt-table>
    <x-slot:header>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </x-slot:header>

    <x-slot:body>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <x-bt-button-icon icon="pencil" href="/users/{{ $user->id }}/edit" />
                </td>
            </tr>
        @endforeach
    </x-slot:body>
</x-bt-table>
```

## Livewire Integration

### Wire Model
```blade
{{-- Two-way binding --}}
<x-bt-input wire:model="name" />

{{-- Live updates --}}
<x-bt-input wire:model.live="search" />

{{-- Debounced live updates --}}
<x-bt-input wire:model.live.debounce.300ms="search" />

{{-- Blur updates --}}
<x-bt-input wire:model.blur="email" />
```

### Validation
Errors display automatically from Livewire validation:

```php
// Livewire Component
#[Validate('required|email')]
public $email = '';

public function save()
{
    $this->validate(); // Errors show automatically
}
```

```blade
{{-- Errors appear automatically --}}
<x-bt-input wire:model="email" label="Email" />
```

### Loading States
Components show loading spinners automatically:

```blade
<x-bt-button wire:click="save" primary>
    Save {{-- Spinner shows automatically on wire:loading --}}
</x-bt-button>

<x-bt-input
    wire:model.live="search"
    label="Search" {{-- Spinner shows during search --}}
/>
```

## Complete Examples

### Login Form
```blade
<form wire:submit="login" class="space-y-4">
    <x-bt-input
        wire:model="email"
        type="email"
        label="Email"
        iconStart="envelope"
        placeholder="you@example.com"
    />

    <x-bt-input
        wire:model="password"
        type="password"
        label="Password"
        iconStart="lock-closed"
    />

    <x-bt-checkbox
        wire:model="remember"
        label="Remember me"
    />

    <x-bt-button type="submit" primary class="w-full">
        Sign In
    </x-bt-button>
</form>
```

### Contact Form with Validation
```php
// Livewire Component
class ContactForm extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $subject = '';

    #[Validate('required|min:10')]
    public $message = '';

    public function submit()
    {
        $this->validate();
        // Process form...
        session()->flash('success', 'Message sent!');
        $this->reset();
    }
}
```

```blade
<form wire:submit="submit" class="space-y-6">
    <x-bt-input
        wire:model="name"
        label="Name"
        iconStart="user"
    />

    <x-bt-input
        wire:model="email"
        type="email"
        label="Email"
        iconStart="envelope"
    />

    <x-bt-select
        wire:model="subject"
        :options="['general' => 'General', 'support' => 'Support', 'sales' => 'Sales']"
        label="Subject"
    />

    <x-bt-textarea
        wire:model="message"
        label="Message"
        rows="5"
    />

    <x-bt-button type="submit" primary>Send Message</x-bt-button>
</form>

@if (session('success'))
    <x-bt-alert success>{{ session('success') }}</x-bt-alert>
@endif
```

### Data Table with Search
```php
// Livewire Component
class UserTable extends Component
{
    public $search = '';
    public $statusFilter = '';

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->get();

        return view('livewire.user-table', compact('users'));
    }
}
```

```blade
<div class="space-y-4">
    <div class="flex gap-4">
        <x-bt-input
            wire:model.live.debounce.300ms="search"
            placeholder="Search users..."
            iconStart="magnifying-glass"
            clearable
            class="flex-1"
        />

        <x-bt-select
            wire:model.live="statusFilter"
            :options="['active' => 'Active', 'inactive' => 'Inactive']"
            placeholder="All Statuses"
            clearable
        />
    </div>

    <x-bt-table>
        <x-slot:header>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </x-slot:header>

        <x-slot:body>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <x-bt-badge :color="$user->status === 'active' ? 'success' : 'secondary'">
                            {{ ucfirst($user->status) }}
                        </x-bt-badge>
                    </td>
                    <td>
                        <x-bt-button-icon icon="pencil" href="/users/{{ $user->id }}/edit" sm ghost />
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
    </x-bt-table>
</div>
```

### Modal with Form
```php
// Livewire Component
class CreateUser extends Component
{
    public $showModal = false;

    #[Validate('required')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    public function save()
    {
        $this->validate();
        // Create user...
        $this->showModal = false;
        $this->reset(['name', 'email']);
    }
}
```

```blade
<div>
    <x-bt-button wire:click="$set('showModal', true)" primary>
        Create User
    </x-bt-button>

    <x-bt-modal wire:model="showModal">
        <x-slot:title>Create New User</x-slot:title>

        <div class="space-y-4">
            <x-bt-input wire:model="name" label="Name" />
            <x-bt-input wire:model="email" type="email" label="Email" />
        </div>

        <x-slot:footer>
            <x-bt-button wire:click="save" primary>Create</x-bt-button>
            <x-bt-button wire:click="$set('showModal', false)" outline>Cancel</x-bt-button>
        </x-slot:footer>
    </x-bt-modal>
</div>
```

## Best Practices

1. **Always use `wire:model`** for Livewire forms
2. **Validation errors show automatically** - no manual display needed
3. **Use debounce for search** - `wire:model.live.debounce.300ms`
4. **Icons use Heroicons** - Use icon names without prefix (e.g., `"envelope"` not `"o-envelope"`)
5. **Magic attributes** - Use size/color/variant as attributes: `<x-bt-button primary lg>`
6. **Loading states automatic** - `spinner` prop enabled by default
7. **Provide hints** - Use `hint` prop for user guidance
8. **Use semantic colors** - success, danger, warning for appropriate actions

## Common Patterns

### Live Search
```blade
<x-bt-input
    wire:model.live.debounce.300ms="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

### Dependent Selects
```php
public $selectedCountry = null;
public $states = [];

public function updatedSelectedCountry($value)
{
    $this->states = State::where('country_id', $value)->get();
}
```

```blade
<x-bt-select
    wire:model.live="selectedCountry"
    :options="$countries"
    label="Country"
/>

<x-bt-select
    wire:model="selectedState"
    :options="$states"
    label="State"
    :disabled="!$selectedCountry"
/>
```

### Confirmation Dialog
```blade
<x-bt-button
    wire:click="delete"
    wire:confirm="Are you sure?"
    danger
>
    Delete
</x-bt-button>
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

Configure Tailwind to include Beartropy paths:

```javascript
// tailwind.config.js
export default {
    content: [
        './resources/**/*.blade.php',
        './vendor/beartropy/ui/resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
}
```

---

**This guide provides all necessary information for AI assistants to generate correct Beartropy UI component code.** Use component names, props, and patterns exactly as shown above.
