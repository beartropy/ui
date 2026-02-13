---
name: beartropy-form
description: Create forms using Beartropy UI components with proper validation and Livewire integration
version: 2.0.0
author: Beartropy
tags: [beartropy, ui, form, livewire, laravel, tall-stack]
---

# Beartropy Form Builder

You are an expert in building forms using Beartropy UI components for Laravel/Livewire applications.

All components use the `<x-bt-*>` tag prefix (short alias for `<x-beartropy-ui::*>`).

---

## Choosing the Right Input

| User says... | Use this | Why |
|---|---|---|
| "text field", "email", "password", "number input" | `<x-bt-input>` | Standard text input with icons, clear button, copy button, spinner |
| "multi-line text", "description field" | `<x-bt-textarea>` | Auto-resize textarea with character count |
| "dropdown", "multi-select", "remote data" | `<x-bt-select>` | Full-featured: search, multi-select, remote data, object mapping |
| "autocomplete", "type-ahead" | `<x-bt-lookup>` | Text input with filtered dropdown, diacritic-insensitive search |
| "checkbox", "agree to terms" | `<x-bt-checkbox>` | Checkbox with label, description, indeterminate state |
| "radio buttons", "choose one option" | `<x-bt-radio>` | Radio button (wrap with `<x-bt-radio-group>` for labels) |
| "on/off switch", "toggle setting" | `<x-bt-toggle>` | Toggle switch with label and description |
| "upload a file", "profile photo" | `<x-bt-file-input>` | Compact button-style file picker, looks like a text input |
| "drag and drop files", "batch upload" | `<x-bt-file-dropzone>` | Dedicated drop zone with previews, progress bars, validation |
| "date picker", "date+time" | `<x-bt-datetime>` | Flatpickr-based date/datetime picker |
| "time only" | `<x-bt-time-picker>` | Time picker with interval support |
| "tag input", "chips", "keywords" | `<x-bt-tag>` | Add/remove tags with separators, paste support |
| "range slider" | `<x-bt-slider>` | Range slider with min/max/step |
| "chat message input" | `<x-bt-chat-input>` | Auto-resizing textarea with file attachment and submit |

---

## Form Input Components

### Input
```blade
<x-bt-input
    wire:model="name"
    label="Full Name"
    placeholder="John Doe"
    iconStart="user"
    clearable
    hint="Enter your legal name"
/>
```

**Key Props:** `label`, `placeholder`, `type` (text/email/password/number), `iconStart`/`iconEnd`, `clearable` (default: true), `copyButton`, `hint`/`help`, `customError`, `spinner` (default: true)
**Sizes:** `xs`, `sm`, `md` (default), `lg`, `xl`
**Slots:** `start`, `end`

### Textarea
```blade
<x-bt-textarea
    wire:model="bio"
    label="Bio"
    placeholder="Tell us about yourself..."
    rows="4"
    :maxlength="500"
/>
```

**Key Props:** `rows`, `maxlength` (shows character counter), `resize` (default: true for auto-resize), plus all Input props

### Select

```blade
{{-- Simple options --}}
<x-bt-select
    wire:model="country"
    :options="['us' => 'United States', 'ca' => 'Canada', 'mx' => 'Mexico']"
    label="Country"
    searchable
/>

{{-- Object options --}}
<x-bt-select
    wire:model="userId"
    :options="$users"
    optionLabel="name"
    optionValue="id"
    optionAvatar="avatar_url"
    label="Select User"
    searchable
    multiple
/>

{{-- Remote data --}}
<x-bt-select
    wire:model="city"
    remoteUrl="/api/cities"
    optionLabel="name"
    optionValue="id"
    label="City"
    searchable
/>
```

**Key Props:** `options`, `searchable`, `multiple`, `clearable`, `remoteUrl`, `optionLabel` (default: 'name'), `optionValue` (default: 'id'), `optionDescription`, `optionIcon`, `optionAvatar`, `emptyMessage`
**Slots:** `beforeOptions`, `afterOptions`

### Lookup (Autocomplete)

```blade
<x-bt-lookup
    wire:model="country"
    label="Country"
    placeholder="Search..."
    iconStart="magnifying-glass"
    :options="$countries"
    optionLabel="name"
    optionValue="code"
/>
```

**Key difference from Select:** Lookup is a text input with a filtered dropdown. The user types to filter, and can also enter free text. Select is a structured dropdown for pre-defined options.

**Key Props:** `options`, `optionLabel` (default: 'name'), `optionValue` (default: 'id'), `clearable` (default: true), `iconStart`, `iconEnd`, plus standard input props

### Checkbox

```blade
<x-bt-checkbox
    wire:model="agreed"
    label="I agree to the terms and conditions"
    description="By checking this box, you accept our privacy policy"
/>
```

**Key Props:** `label`, `description`, `checked`, `disabled`, `indeterminate`, `labelPosition` ('left'/'right', default: 'right')

### Radio

```blade
<div class="space-y-2">
    <x-bt-radio wire:model="plan" value="basic" label="Basic Plan" />
    <x-bt-radio wire:model="plan" value="pro" label="Pro Plan" />
    <x-bt-radio wire:model="plan" value="enterprise" label="Enterprise" />
</div>
```

### Toggle

```blade
<x-bt-toggle
    wire:model.live="notifications"
    label="Email Notifications"
    description="Receive email updates about your account"
/>
```

**Key Props:** `label`, `description`, `disabled`

### Tag (Chip Input)

```blade
<x-bt-tag
    wire:model="skills"
    label="Skills"
    placeholder="Type and press Enter..."
    :maxTags="10"
    :unique="true"
/>
```

**Key Props:** `value` (array), `separator` (default: ','), `unique` (default: true), `maxTags`, `disabled`, `fill` (tinted background)
**Features:** Enter/comma to add, backspace to remove, paste splits on separator, hidden inputs for plain form submission

### File Input (Compact)

```blade
<x-bt-file-input
    wire:model="avatar"
    label="Profile Photo"
    accept="image/*"
    hint="JPG or PNG, max 1MB"
/>
```

**Key Props:** `accept`, `multiple`, `clearable` (default: true), `placeholder` (default: 'Choose file')
**Slots:** `start`, `button`, `end`
**Use for:** Profile avatars, single document uploads, compact inline file selection

### File Dropzone (Full Upload Zone)

```blade
<x-bt-file-dropzone
    wire:model="documents"
    label="Project Documents"
    accept=".pdf,.doc,.docx"
    :maxFileSize="10485760"
    :maxFiles="5"
    preview
    help="Max 10MB per file, up to 5 files"
/>
```

**Key Props:** `accept`, `multiple` (default: true), `maxFileSize` (bytes), `maxFiles`, `preview` (default: true), `clearable` (default: true), `existingFiles` (array of pre-uploaded files), `placeholder`
**Features:** Drag & drop, image previews, upload progress bars, client-side validation, file management (add/remove)
**Use for:** Gallery uploads, document management, batch uploads

**Both file components require Livewire's `WithFileUploads` trait.**

### Datetime

```blade
<x-bt-datetime
    wire:model="appointmentDate"
    label="Appointment"
    enableTime
    :minDate="now()->toDateString()"
/>
```

**Key Props:** `enableTime`, `minDate`, `maxDate`, `dateFormat`, `disable` (array of disabled dates)

### Time Picker

```blade
<x-bt-time-picker
    wire:model="startTime"
    label="Start Time"
    :interval="15"
/>
```

**Key Props:** `interval` (minutes between options), `min`, `max`

### Chat Input

```blade
<x-bt-chat-input wire:model="message" action="sendMessage">
    <x-slot:tools>
        <x-bt-button-icon icon="paper-clip" ghost />
    </x-slot:tools>
    <x-slot:actions>
        <x-bt-button-icon icon="paper-airplane" solid beartropy />
    </x-slot:actions>
</x-bt-chat-input>
```

**Key Props:** `action` (Livewire method to call on submit), `stacked` (layout mode), `submitOnEnter` (default: true), `maxLength`, `border`
**Slots:** `tools` (left side), `footer`/`actions` (right side)

### Slider

```blade
<x-bt-slider
    wire:model="volume"
    label="Volume"
    :min="0"
    :max="100"
    :step="5"
/>
```

**Key Props:** `min`, `max`, `step`

---

## Buttons

### Button
```blade
<x-bt-button type="submit" primary iconStart="check" spinner>
    Save Changes
</x-bt-button>

<x-bt-button outline secondary wire:click="$reset">
    Cancel
</x-bt-button>

<x-bt-button href="/dashboard" ghost>
    Back to Dashboard
</x-bt-button>
```

**Variants:** `solid` (default), `outline`, `ghost`, `link` — use as bare attributes
**Colors:** `primary`, `secondary`, `success`, `warning`, `danger`, `info` — use as bare attributes
**Sizes:** `xs`, `sm`, `md` (default), `lg`, `xl` — use as bare attributes
**Key Props:** `type`, `href`, `disabled`, `iconStart`/`iconEnd`, `label`, `spinner` (default: true)
**Slots:** `start`, `end`

### Button Icon (Icon-Only)
```blade
<x-bt-button-icon icon="trash" danger ghost sm wire:click="delete" />
```

Use for toolbars, table actions, and compact spaces.

---

## Form Validation with Livewire

### Automatic Error Display

Beartropy components automatically show validation errors from Livewire's `$errors` bag:

```blade
{{-- Error appears automatically when $errors->has('email') --}}
<x-bt-input wire:model="email" label="Email" type="email" />
```

### Custom Errors

```blade
<x-bt-input
    wire:model="username"
    label="Username"
    customError="This username is already taken"
/>
```

### Real-Time Validation

```php
// In your Livewire component
public function updatedEmail()
{
    $this->validateOnly('email');
}
```

```blade
<x-bt-input wire:model.live.debounce.500ms="email" label="Email" type="email" />
```

---

## Complete Form Example

### Livewire Component
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class ContactForm extends Component
{
    use WithFileUploads;

    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $subject = '';

    #[Validate('required|min:10')]
    public $message = '';

    public $tags = [];
    public $attachment;
    public $agreeToTerms = false;

    public function submit()
    {
        $this->validate();

        // Process form...

        session()->flash('success', 'Form submitted!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

### Blade Template
```blade
<div>
    <form wire:submit="submit" class="space-y-6 max-w-2xl mx-auto">
        <x-bt-input
            wire:model="name"
            label="Full Name"
            placeholder="John Doe"
            iconStart="user"
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
            :options="['general' => 'General', 'support' => 'Support', 'sales' => 'Sales']"
            label="Subject"
            placeholder="Choose a subject"
        />

        <x-bt-textarea
            wire:model="message"
            label="Message"
            placeholder="Tell us what's on your mind..."
            rows="5"
            hint="Minimum 10 characters"
        />

        <x-bt-tag
            wire:model="tags"
            label="Tags"
            placeholder="Add tags..."
            :maxTags="5"
        />

        <x-bt-file-input
            wire:model="attachment"
            label="Attachment"
            accept=".pdf,.doc,.docx"
            hint="Optional, max 10MB"
        />

        <x-bt-checkbox
            wire:model="agreeToTerms"
            label="I agree to the terms and conditions"
        />

        <div class="flex gap-4">
            <x-bt-button type="submit" primary iconStart="paper-airplane">
                Send Message
            </x-bt-button>

            <x-bt-button type="button" wire:click="$reset" outline secondary>
                Clear Form
            </x-bt-button>
        </div>
    </form>

    @if (session('success'))
        <x-bt-alert success class="mt-4">
            {{ session('success') }}
        </x-bt-alert>
    @endif
</div>
```

---

## Common Form Patterns

### Login Form
```blade
<form wire:submit="login" class="space-y-4">
    <x-bt-input wire:model="email" label="Email" type="email" iconStart="envelope" />
    <x-bt-input wire:model="password" label="Password" type="password" iconStart="lock-closed" />
    <x-bt-checkbox wire:model="remember" label="Remember me" />
    <x-bt-button type="submit" primary class="w-full">Sign In</x-bt-button>
</form>
```

### Search with Debounce
```blade
<x-bt-input
    wire:model.live.debounce.300ms="search"
    placeholder="Search..."
    iconStart="magnifying-glass"
    clearable
/>
```

### Settings with Toggles
```blade
<div class="space-y-4">
    <x-bt-toggle wire:model.live="emailNotifications" label="Email Notifications" description="Receive updates via email" />
    <x-bt-toggle wire:model.live="pushNotifications" label="Push Notifications" description="Receive push notifications" />
    <x-bt-toggle wire:model.live="marketingEmails" label="Marketing" description="Receive marketing emails" />
</div>
```

### File Upload with Dropzone
```blade
<form wire:submit="upload" class="space-y-4">
    <x-bt-file-dropzone
        wire:model="photos"
        label="Upload Photos"
        accept="image/*"
        :maxFileSize="5242880"
        :maxFiles="10"
        help="Max 5MB per file, up to 10 photos"
    />

    <x-bt-button type="submit" primary>Upload All</x-bt-button>
</form>
```

### Tag-Based Filtering
```blade
<x-bt-tag
    wire:model.live="selectedTags"
    label="Filter by Tags"
    :value="$selectedTags"
    fill
    blue
/>
```

---

## Tips

1. **Always use `wire:model`** for reactive Livewire forms
2. **Validation errors show automatically** — no need to manually display them
3. **Use `spinner` prop** on buttons/inputs for loading states (enabled by default)
4. **Icons use Heroicons** — see heroicons.com for names
5. **Magic attributes** — use `primary`, `lg`, `outline` directly as attributes
6. **Use `hint` or `help`** props for user guidance
7. **Debounce search inputs** — always use `.debounce.300ms` on search fields
8. **File uploads need `WithFileUploads`** trait in the Livewire component
9. **Tag input:** Enter/comma adds tags, backspace removes last, paste splits automatically
