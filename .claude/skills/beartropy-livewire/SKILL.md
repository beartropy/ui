---
name: beartropy-livewire
description: Best practices for integrating Beartropy UI components with Laravel Livewire
version: 1.0.0
author: Beartropy
tags: [beartropy, livewire, laravel, tall-stack, reactive]
---

# Beartropy + Livewire Integration Guide

You are an expert in integrating Beartropy UI components with Laravel Livewire for reactive, dynamic user interfaces.

## Your Task

Help users integrate Beartropy UI components with Livewire by:

1. **Explaining wire:model usage** - How to bind components to Livewire properties
2. **Showing validation patterns** - How errors display automatically
3. **Demonstrating loading states** - Using wire:loading with Beartropy components
4. **Real-time updates** - Live search, filters, and reactive UIs
5. **Form submissions** - Handling form posts with Livewire
6. **State management** - Managing component state in Livewire

## Core Concepts

### Wire Model Binding

Beartropy UI components work seamlessly with `wire:model`:

```blade
{{-- Two-way binding --}}
<x-beartropy-ui::input wire:model="name" label="Name" />

{{-- Live updates (no debounce) --}}
<x-beartropy-ui::input wire:model.live="search" label="Search" />

{{-- Debounced live updates --}}
<x-beartropy-ui::input wire:model.live.debounce.300ms="search" label="Search" />

{{-- Blur updates --}}
<x-beartropy-ui::input wire:model.blur="email" label="Email" />
```

### Automatic Validation Errors

Livewire validation errors display automatically:

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class UserForm extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    public function save()
    {
        $this->validate(); // Errors show automatically in components

        // Save logic...
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
```

**Blade Template:**
```blade
<form wire:submit="save">
    {{-- Validation errors show automatically --}}
   <x-bt-input
        wire:model="name"
        label="Name"
    />

   <x-bt-input
        wire:model="email"
        label="Email"
        type="email"
    />

   <x-bt-button type="submit" primary>
        Save
    </x-bt-button>
</form>
```

### Loading States

Buttons and inputs show loading spinners automatically with `wire:loading`:

```blade
{{-- Button shows spinner when save is running --}}
<x-beartropy-ui::button
    wire:click="save"
    type="button"
    primary
>
    Save Changes
</x-bt-button>

{{-- Input shows spinner when search is updating --}}
<x-beartropy-ui::input
    wire:model.live="search"
    label="Search"
    iconStart="magnifying-glass"
/>
```

Disable spinner if needed:
```blade
<x-beartropy-ui::button wire:click="save" :spinner="false">
    Save
</x-bt-button>
```

### Wire Actions

Use Livewire actions with components:

```blade
{{-- Click actions --}}
<x-beartropy-ui::button wire:click="delete({{ $id }})" danger>
    Delete
</x-bt-button>

{{-- Confirm before action --}}
<x-beartropy-ui::button
    wire:click="delete"
    wire:confirm="Are you sure you want to delete this?"
    danger
>
    Delete
</x-bt-button>

{{-- Multiple wire attributes --}}
<x-beartropy-ui::input
    wire:model.live.debounce.500ms="search"
    wire:loading.class="opacity-50"
    label="Search"
/>
```

## Common Patterns

### Live Search / Filter

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearch extends Component
{
    public $search = '';
    public $status = '';

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->get();

        return view('livewire.user-search', compact('users'));
    }
}
```

**Blade Template:**
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
            wire:model.live="status"
            :options="['active' => 'Active', 'inactive' => 'Inactive']"
            placeholder="All Statuses"
            clearable
        />
    </div>

    <div wire:loading.delay class="text-gray-500">
        Searching...
    </div>

    @foreach($users as $user)
        <div>{{ $user->name }}</div>
    @endforeach
</div>
```

### Dynamic Form Fields

Add/remove fields dynamically:

**Livewire Component:**
```php
public $emails = [''];

public function addEmail()
{
    $this->emails[] = '';
}

public function removeEmail($index)
{
    unset($this->emails[$index]);
    $this->emails = array_values($this->emails);
}
```

**Blade Template:**
```blade
<div class="space-y-4">
    @foreach($emails as $index => $email)
        <div class="flex gap-2">
           <x-bt-input
                wire:model="emails.{{ $index }}"
                label="Email {{ $index + 1 }}"
                type="email"
                class="flex-1"
            />

            @if($index > 0)
               <x-bt-button
                    wire:click="removeEmail({{ $index }})"
                    danger
                    ghost
                    iconStart="trash"
                    class="mt-6"
                />
            @endif
        </div>
    @endforeach

   <x-bt-button
        wire:click="addEmail"
        outline
        iconStart="plus"
    >
        Add Email
    </x-bt-button>
</div>
```

### Modal with Form

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

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

        $this->dispatch('user-created');
        session()->flash('success', 'User created successfully!');
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
```

**Blade Template:**
```blade
<div>
   <x-bt-button wire:click="$set('showModal', true)" primary>
        Create User
    </x-bt-button>

   <x-bt-modal wire:model="showModal">
        <x-slot:title>Create New User</x-slot:title>

        <div class="space-y-4">
           <x-bt-input
                wire:model="name"
                label="Name"
                placeholder="John Doe"
            />

           <x-bt-input
                wire:model="email"
                label="Email"
                type="email"
                placeholder="john@example.com"
            />
        </div>

        <x-slot:footer>
           <x-bt-button wire:click="save" primary>
                Create User
            </x-bt-button>

           <x-bt-button
                wire:click="$set('showModal', false)"
                outline
            >
                Cancel
            </x-bt-button>
        </x-slot:footer>
    </x-bt-modal>
</div>
```

### Dependent Select Dropdowns

**Livewire Component:**
```php
public $selectedCountry = null;
public $selectedState = null;
public $states = [];

public function updatedSelectedCountry($countryId)
{
    $this->states = State::where('country_id', $countryId)->get();
    $this->selectedState = null; // Reset state when country changes
}
```

**Blade Template:**
```blade
<div class="space-y-4">
   <x-bt-select
        wire:model.live="selectedCountry"
        :options="$countries"
        label="Country"
        optionLabel="name"
        optionValue="id"
        searchable
    />

   <x-bt-select
        wire:model="selectedState"
        :options="$states"
        label="State"
        optionLabel="name"
        optionValue="id"
        :disabled="!$selectedCountry"
        searchable
    />
</div>
```

### File Upload

**Livewire Component:**
```php
use Livewire\WithFileUploads;

class UploadDocument extends Component
{
    use WithFileUploads;

    public $document;

    public function save()
    {
        $this->validate([
            'document' => 'required|file|max:10240', // 10MB
        ]);

        $path = $this->document->store('documents');

        // Save to database...
    }
}
```

**Blade Template:**
```blade
<form wire:submit="save">
   <x-bt-file-input
        wire:model="document"
        label="Upload Document"
        accept=".pdf,.doc,.docx"
    />

   <x-bt-button type="submit" primary>
        Upload
    </x-bt-button>
</form>
```

### Real-time Validation

Show validation errors as user types:

**Livewire Component:**
```php
public $username = '';

public function updatedUsername()
{
    $this->validateOnly('username');
}

protected function rules()
{
    return [
        'username' => 'required|min:3|unique:users,username',
    ];
}
```

**Blade Template:**
```blade
<x-beartropy-ui::input
    wire:model.live.debounce.500ms="username"
    label="Username"
    hint="Must be unique and at least 3 characters"
/>
```

### Toggling Features

**Livewire Component:**
```php
public $sendNotifications = true;
public $receiveEmails = false;
```

**Blade Template:**
```blade
<div class="space-y-4">
   <x-bt-toggle
        wire:model.live="sendNotifications"
        label="Send Notifications"
        description="Receive push notifications on your device"
    />

   <x-bt-toggle
        wire:model.live="receiveEmails"
        label="Email Notifications"
        description="Receive email updates"
    />
</div>
```

### Polling for Updates

Auto-refresh data every N seconds:

```blade
<div wire:poll.5s>
   <x-bt-input
        wire:model="status"
        label="Status"
        disabled
    />
</div>
```

### Dirty State Detection

Show unsaved changes indicator:

```blade
<form wire:submit="save">
   <x-bt-input wire:model="title" label="Title" />

    <div>
       <x-bt-button type="submit" primary>
            Save
        </x-bt-button>

        <span wire:dirty class="text-amber-600 ml-2">
            Unsaved changes
        </span>
    </div>
</form>
```

## Advanced Patterns

### Remote Select with Livewire

Instead of using the `remote` prop, handle search in Livewire:

**Livewire Component:**
```php
public $userSearch = '';
public $selectedUser = null;

public function render()
{
    $users = User::query()
        ->when($this->userSearch, fn($q) =>
            $q->where('name', 'like', "%{$this->userSearch}%")
        )
        ->limit(50)
        ->get();

    return view('livewire.user-selector', compact('users'));
}
```

### Bulk Actions

**Livewire Component:**
```php
public $selectedItems = [];
public $selectAll = false;

public function updatedSelectAll($value)
{
    $this->selectedItems = $value
        ? $this->items->pluck('id')->toArray()
        : [];
}

public function deleteSelected()
{
    // Delete items...
    $this->selectedItems = [];
}
```

**Blade Template:**
```blade
<div>
   <x-bt-checkbox
        wire:model.live="selectAll"
        label="Select All"
    />

    @foreach($items as $item)
       <x-bt-checkbox
            wire:model.live="selectedItems"
            value="{{ $item->id }}"
            label="{{ $item->name }}"
        />
    @endforeach

    @if(count($selectedItems) > 0)
       <x-bt-button
            wire:click="deleteSelected"
            danger
        >
            Delete {{ count($selectedItems) }} item(s)
        </x-bt-button>
    @endif
</div>
```

## Tips & Best Practices

1. **Use `.live` sparingly** - It increases server requests. Use `.blur` or regular `wire:model` when possible
2. **Debounce search inputs** - Always use `.debounce.300ms` on search fields
3. **Reset forms after submit** - Use `$this->reset()` to clear form data
4. **Flash messages** - Use `session()->flash()` for success/error messages
5. **Disable during loading** - Prevent multiple submissions with loading states
6. **Validate early** - Use `validateOnly()` in `updated` methods for real-time validation
7. **Use computed properties** - Cache expensive queries with Livewire computed properties
8. **Leverage events** - Use `$dispatch()` to communicate between components

## Common Issues

### Validation not showing
- Ensure property names match between Livewire and `wire:model`
- Check that validation rules are defined
- Verify error bag is not being cleared

### Loading states not working
- Ensure `spinner` prop is not set to `false`
- Check that the wire action name matches exactly
- Use `wire:target` if multiple actions exist

### Select not updating
- Ensure `wire:model` is set
- Check that options array has correct structure
- Verify `optionValue` and `optionLabel` match your data structure

When helping users with Livewire integration, always show complete examples with both the Livewire component and the Blade template.
