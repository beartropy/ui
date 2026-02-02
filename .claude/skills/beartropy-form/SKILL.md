---
name: beartropy-form
description: Create forms using Beartropy UI components with proper validation and Livewire integration
version: 1.0.0
author: Beartropy
tags: [beartropy, ui, form, livewire, laravel, tall-stack]
---

# Beartropy Form Builder

You are an expert in building forms using Beartropy UI components for Laravel/Livewire applications.

## Your Task

When a user asks to create a form, you should:

1. **Ask clarifying questions** about the form requirements:
   - What fields are needed? (text inputs, selects, checkboxes, textareas, etc.)
   - Should it use Livewire for reactive validation?
   - What validation rules are needed?
   - Should it have a submit button with loading state?
   - Any special features? (file uploads, date pickers, search/lookup fields, etc.)

2. **Generate the form code** using Beartropy UI components with proper syntax

3. **Include proper Livewire integration** if requested

## Beartropy UI Components Available

### Input Component
```blade
<x-beartropy-ui::input
    wire:model="fieldName"
    label="Field Label"
    placeholder="Enter value..."
    type="text"
    iconStart="user"
    clearable
    hint="Helper text here"
/>
```

**Props:**
- `label` - Label text
- `placeholder` - Placeholder text
- `type` - Input type (text, email, password, number, etc.)
- `iconStart` / `iconEnd` - Heroicon name
- `clearable` - Show clear button (default: true)
- `copyButton` - Show copy button
- `hint` - Help text
- `help` - Alternative help text prop
- `customError` - Custom error message
- `spinner` - Show loading spinner on wire:loading (default: true)
- Size: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`
- Color: `primary`, `secondary`, `success`, `warning`, `danger`, `info`

**Slots:**
- `start` - Custom content at start
- `end` - Custom content at end

### Select Component
```blade
<x-beartropy-ui::select
    wire:model="selectedValue"
    :options="$options"
    label="Select an option"
    placeholder="Choose..."
    searchable
    clearable
/>
```

**Props:**
- `options` - Array or Collection of options
- `selected` - Initially selected value
- `label` - Label text
- `placeholder` - Placeholder text
- `searchable` - Enable search (default: false)
- `multiple` - Multiple selection
- `clearable` - Show clear button
- `remote` - Enable remote data fetching
- `remoteUrl` - Endpoint for remote data
- `optionLabel` - Key for option label (default: 'name')
- `optionValue` - Key for option value (default: 'id')
- `optionDescription` - Key for option description
- `optionIcon` - Key for option icon
- `optionAvatar` - Key for option avatar
- `emptyMessage` - Text when no options found

**Slots:**
- `beforeOptions` - Content at top of dropdown
- `afterOptions` - Content at bottom of dropdown

### Checkbox Component
```blade
<x-beartropy-ui::checkbox
    wire:model="agreed"
    label="I agree to terms"
    description="Optional description text"
/>
```

**Props:**
- `label` - Label text
- `description` - Helper description
- `checked` - Checked state
- `disabled` - Disabled state
- `indeterminate` - Indeterminate visual state
- `labelPosition` - 'left' or 'right' (default: 'right')

### Radio Component
```blade
<x-beartropy-ui::radio
    wire:model="choice"
    value="option1"
    label="Option 1"
/>
```

### Textarea Component
```blade
<x-beartropy-ui::textarea
    wire:model="message"
    label="Message"
    placeholder="Enter your message..."
    rows="5"
/>
```

**Props:**
- `rows` - Number of rows
- `maxlength` - Maximum character count
- All other props similar to Input component

### Button Component
```blade
<x-beartropy-ui::button
    type="submit"
    iconStart="check"
    spinner
>
    Submit Form
</x-bt-button>
```

**Props:**
- `type` - button, submit, reset
- `href` - URL for link-style button
- `disabled` - Disabled state
- `iconStart` / `iconEnd` - Heroicon name
- `label` - Button text
- `spinner` - Show spinner on wire:loading (default: true)
- Variants: `solid`, `outline`, `ghost`, `link`
- Colors: `primary`, `secondary`, `success`, `warning`, `danger`, `info`
- Sizes: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`

**Slots:**
- `start` - Custom prefix content
- `end` - Custom suffix content

### File Input Component
```blade
<x-beartropy-ui::file-input
    wire:model="photo"
    label="Upload Photo"
    accept="image/*"
/>
```

### Date/Time Components
```blade
<x-beartropy-ui::datetime
    wire:model="appointmentDate"
    label="Appointment Date"
    enableTime
/>

<x-beartropy-ui::time-picker
    wire:model="time"
    label="Select Time"
/>
```

## Form Validation with Livewire

### Error Display
Beartropy UI components automatically show validation errors from Livewire:

```blade
<x-beartropy-ui::input
    wire:model="email"
    label="Email"
    type="email"
/>
{{-- Error will show automatically from $errors->get('email') --}}
```

### Custom Errors
```blade
<x-beartropy-ui::input
    wire:model="username"
    label="Username"
    customError="This username is already taken"
/>
```

## Complete Form Example

### Livewire Component (PHP)
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

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

    public $agreeToTerms = false;

    public function submit()
    {
        $this->validate();

        // Process form...

        session()->flash('success', 'Form submitted successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

### Livewire View (Blade)
```blade
<div>
    <form wire:submit="submit" class="space-y-6 max-w-2xl mx-auto">
       <x-bt-input
            wire:model="name"
            label="Full Name"
            placeholder="John Doe"
            iconStart="user"
            hint="Enter your full name"
        />

       <x-bt-input
            wire:model="email"
            label="Email Address"
            type="email"
            placeholder="john@example.com"
            iconStart="envelope"
        />

       <x-bt-select
            wire:model="subject"
            :options="['general' => 'General Inquiry', 'support' => 'Support', 'sales' => 'Sales']"
            label="Subject"
            placeholder="Choose a subject"
            searchable
        />

       <x-bt-textarea
            wire:model="message"
            label="Message"
            placeholder="Tell us what's on your mind..."
            rows="5"
            hint="Minimum 10 characters"
        />

       <x-bt-checkbox
            wire:model="agreeToTerms"
            label="I agree to the terms and conditions"
        />

        <div class="flex gap-4">
           <x-bt-button
                type="submit"
                iconStart="paper-airplane"
                primary
            >
                Send Message
            </x-bt-button>

           <x-bt-button
                type="button"
                wire:click="$reset"
                outline
                secondary
            >
                Clear Form
            </x-bt-button>
        </div>
    </form>

    @if (session('success'))
       <x-bt-alert success>
            {{ session('success') }}
        </x-bt-alert>
    @endif
</div>
```

## Tips

1. **Always use `wire:model`** for reactive Livewire forms
2. **Validation errors show automatically** - no need to manually display them
3. **Use `spinner` prop** on buttons and inputs to show loading states
4. **Icons use Heroicons** - see https://heroicons.com for available icons
5. **Components support magic attributes** - use `primary`, `lg`, etc. directly as attributes
6. **Use `hint` or `help`** props to provide user guidance
7. **Combine with Alpine.js** for client-side interactions using `x-model`, `x-on:click`, etc.

## Common Patterns

### Login Form
```blade
<form wire:submit="login" class="space-y-4">
   <x-bt-input
        wire:model="email"
        label="Email"
        type="email"
        iconStart="envelope"
    />

   <x-bt-input
        wire:model="password"
        label="Password"
        type="password"
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

### Search Form
```blade
<x-beartropy-ui::input
    wire:model.live.debounce.300ms="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

### Multi-Step Form
Use Livewire properties to track steps and conditionally show form sections.

When creating forms, always follow these Beartropy UI patterns and ensure proper Livewire integration for reactive, validated forms.
