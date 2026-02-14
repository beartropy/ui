---
name: bt-ui-livewire
description: Best practices for integrating Beartropy UI components with Laravel Livewire
version: 2.0.0
author: Beartropy
tags: [beartropy, livewire, laravel, tall-stack, reactive]
---

# Beartropy + Livewire Integration Guide

You are an expert in integrating Beartropy UI components with Laravel Livewire for reactive, dynamic user interfaces.

All components use the `<x-bt-*>` tag prefix (short alias for `<x-beartropy-ui::*>`).

---

## Core Concepts

### Wire Model Binding

Beartropy UI components work seamlessly with `wire:model`:

```blade
{{-- Two-way binding (updates on form submit or action) --}}
<x-bt-input wire:model="name" label="Name" />

{{-- Live updates (no debounce) --}}
<x-bt-input wire:model.live="search" label="Search" />

{{-- Debounced live updates --}}
<x-bt-input wire:model.live.debounce.300ms="search" label="Search" />

{{-- Blur updates --}}
<x-bt-input wire:model.blur="email" label="Email" />
```

### Automatic Validation Errors

Livewire validation errors display automatically — no manual error rendering needed:

```php
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
}
```

```blade
<form wire:submit="save">
    <x-bt-input wire:model="name" label="Name" />
    <x-bt-input wire:model="email" label="Email" type="email" />
    <x-bt-button type="submit" primary>Save</x-bt-button>
</form>
```

### Loading States

Buttons and inputs show loading spinners automatically:

```blade
{{-- Button shows spinner when save is running --}}
<x-bt-button wire:click="save" primary>Save Changes</x-bt-button>

{{-- Input shows spinner when search is updating --}}
<x-bt-input wire:model.live="search" label="Search" iconStart="magnifying-glass" />

{{-- Disable spinner if needed --}}
<x-bt-button wire:click="save" :spinner="false">Save</x-bt-button>
```

### Wire Actions

```blade
{{-- Click actions --}}
<x-bt-button wire:click="delete({{ $id }})" danger>Delete</x-bt-button>

{{-- Native confirm (simple, no styling) --}}
<x-bt-button wire:click="delete" wire:confirm="Are you sure?" danger>Delete</x-bt-button>

{{-- Multiple wire attributes --}}
<x-bt-input
    wire:model.live.debounce.500ms="search"
    wire:loading.class="opacity-50"
    label="Search"
/>
```

---

## Beartropy Livewire Traits

### HasDialogs — Programmatic Alerts & Confirms

```php
use Beartropy\Ui\Traits\HasDialogs;

class MyComponent extends Component
{
    use HasDialogs;

    // Simple alerts
    public function onSave()
    {
        // ...save logic...
        $this->dialog()->success('Saved!', 'Your changes have been saved.');
    }

    public function onError()
    {
        $this->dialog()->error('Failed', 'Could not save your changes.');
    }

    // Confirm before action
    public function confirmDelete($id)
    {
        $this->dialog()->delete(
            'Delete this item?',
            'This action cannot be undone.',
            [
                'method' => 'deleteItem',
                'params' => [$id],
            ]
        );
    }

    // Custom confirm with both buttons
    public function confirmPublish()
    {
        $this->dialog()->confirm([
            'title' => 'Publish this article?',
            'description' => 'It will be visible to all users immediately.',
            'accept' => ['label' => 'Yes, publish', 'method' => 'publish'],
            'reject' => ['label' => 'Not yet'],
        ]);
    }

    public function deleteItem($id) { /* ... */ }
    public function publish() { /* ... */ }
}
```

```blade
{{-- Place once in layout — event-driven, no slots, no wire:model --}}
<x-bt-dialog />
```

**Available methods:** `success()`, `info()`, `warning()`, `error()`, `confirm()`, `delete()`

### HasToasts — Auto-Dismissing Notifications

```php
use Beartropy\Ui\Traits\HasToasts;

class MyComponent extends Component
{
    use HasToasts;

    public function save()
    {
        // ...save logic...
        $this->toast()->success('Saved!', 'Your changes have been saved.');
    }

    public function handleError()
    {
        $this->toast()->error('Error', 'Could not complete the operation.');
    }

    public function deleteWithUndo($id)
    {
        // ...delete logic...
        $this->toast()->success('Deleted', 'Item removed.', 5000, null, 'Undo', "/restore/{$id}");
    }
}
```

```blade
{{-- Place once in layout — event-driven, no slots --}}
<x-bt-toast />

{{-- Custom position and limit --}}
<x-bt-toast position="bottom-right" :max-visible="3" />
```

**Method signature:** `success(title, message='', duration=4000, position=null, action=null, actionUrl=null)`
**Available methods:** `success()`, `error()`, `warning()`, `info()`

---

## Common Patterns

### Live Search / Filter

```php
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

    @foreach($users as $user)
        <div>{{ $user->name }}</div>
    @endforeach
</div>
```

### Dynamic Form Fields

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

```blade
<div class="space-y-4">
    @foreach($emails as $index => $email)
        <div class="flex gap-2">
            <x-bt-input wire:model="emails.{{ $index }}" label="Email {{ $index + 1 }}" type="email" class="flex-1" />

            @if($index > 0)
                <x-bt-button-icon wire:click="removeEmail({{ $index }})" icon="trash" danger ghost class="mt-6" />
            @endif
        </div>
    @endforeach

    <x-bt-button wire:click="addEmail" outline iconStart="plus">Add Email</x-bt-button>
</div>
```

### Modal with Form

```php
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
    }
}
```

```blade
<div>
    <x-bt-button wire:click="$set('showModal', true)" primary>Create User</x-bt-button>

    <x-bt-modal wire:model="showModal">
        <x-slot:title>Create New User</x-slot:title>

        <div class="space-y-4">
            <x-bt-input wire:model="name" label="Name" />
            <x-bt-input wire:model="email" label="Email" type="email" />
        </div>

        <x-slot:footer>
            <x-bt-button wire:click="save" primary>Create</x-bt-button>
            <x-bt-button wire:click="$set('showModal', false)" outline>Cancel</x-bt-button>
        </x-slot:footer>
    </x-bt-modal>
</div>
```

### Dependent Select Dropdowns

```php
public $selectedCountry = null;
public $selectedState = null;
public $states = [];

public function updatedSelectedCountry($countryId)
{
    $this->states = State::where('country_id', $countryId)->get();
    $this->selectedState = null;
}
```

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

### File Upload — Compact (File Input)

```php
use Livewire\WithFileUploads;

class UploadDocument extends Component
{
    use WithFileUploads;

    public $document;

    public function save()
    {
        $this->validate(['document' => 'required|file|max:10240']);
        $path = $this->document->store('documents');
    }
}
```

```blade
<form wire:submit="save">
    <x-bt-file-input wire:model="document" label="Upload Document" accept=".pdf,.doc,.docx" />
    <x-bt-button type="submit" primary>Upload</x-bt-button>
</form>
```

### File Upload — Drag & Drop (File Dropzone)

```php
use Livewire\WithFileUploads;

class UploadPhotos extends Component
{
    use WithFileUploads;

    public $photos = [];

    public function upload()
    {
        $this->validate(['photos.*' => 'image|max:5120']);

        foreach ($this->photos as $photo) {
            $photo->store('photos', 'public');
        }

        $this->toast()->success('Uploaded!', count($this->photos) . ' photos saved.');
    }
}
```

```blade
<form wire:submit="upload" class="space-y-4">
    <x-bt-file-dropzone
        wire:model="photos"
        label="Upload Photos"
        accept="image/*"
        :maxFileSize="5242880"
        :maxFiles="10"
        help="Max 5MB per photo, up to 10"
    />

    <x-bt-button type="submit" primary>Upload All</x-bt-button>
</form>
```

**Key difference:** `file-input` is compact (inline button-style). `file-dropzone` is a full drop zone with drag & drop, image previews, progress bars, and file management.

### Real-time Validation

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

```blade
<x-bt-input wire:model.live.debounce.500ms="username" label="Username" hint="Must be unique, 3+ characters" />
```

### Toggling Features

```blade
<div class="space-y-4">
    <x-bt-toggle wire:model.live="sendNotifications" label="Send Notifications" description="Push notifications on your device" />
    <x-bt-toggle wire:model.live="receiveEmails" label="Email Updates" description="Receive email notifications" />
</div>
```

### Polling for Updates

```blade
<div wire:poll.5s>
    <x-bt-input wire:model="status" label="Status" disabled />
</div>
```

### Dirty State Detection

```blade
<form wire:submit="save">
    <x-bt-input wire:model="title" label="Title" />
    <div>
        <x-bt-button type="submit" primary>Save</x-bt-button>
        <span wire:dirty class="text-amber-600 ml-2">Unsaved changes</span>
    </div>
</form>
```

### Bulk Actions with Checkboxes

```php
public $selectedItems = [];
public $selectAll = false;

public function updatedSelectAll($value)
{
    $this->selectedItems = $value ? $this->items->pluck('id')->toArray() : [];
}

public function deleteSelected()
{
    // Delete items...
    $this->selectedItems = [];
}
```

```blade
<div>
    <x-bt-checkbox wire:model.live="selectAll" label="Select All" />

    @foreach($items as $item)
        <x-bt-checkbox wire:model.live="selectedItems" value="{{ $item->id }}" label="{{ $item->name }}" />
    @endforeach

    @if(count($selectedItems) > 0)
        <x-bt-button wire:click="deleteSelected" danger>
            Delete {{ count($selectedItems) }} item(s)
        </x-bt-button>
    @endif
</div>
```

---

## Tips & Best Practices

1. **Use `.live` sparingly** — increases server requests. Use `.blur` or regular `wire:model` when possible
2. **Debounce search inputs** — always use `.debounce.300ms` on search fields
3. **Reset forms after submit** — use `$this->reset()` to clear form data
4. **Use `HasToasts` for feedback** — prefer `$this->toast()->success(...)` over `session()->flash()`
5. **Use `HasDialogs` for confirms** — prefer `$this->dialog()->confirm(...)` over `wire:confirm`
6. **Disable during loading** — prevent double submissions with loading states (enabled by default)
7. **Validate early** — use `validateOnly()` in `updated*` methods for real-time validation
8. **Use computed properties** — cache expensive queries with Livewire computed properties
9. **File uploads need `WithFileUploads`** — both `file-input` and `file-dropzone` require this trait

## Common Issues

### Validation not showing
- Ensure property names match between Livewire and `wire:model`
- Check that validation rules are defined
- Verify error bag is not being cleared

### Loading states not working
- Ensure `spinner` prop is not set to `false`
- Use `wire:target` if multiple actions exist

### Select not updating
- Ensure `wire:model` is set
- Check that `optionValue` and `optionLabel` match your data structure
- For dependent selects, use `wire:model.live` on the parent

### Dialog not appearing
- Ensure `<x-bt-dialog />` is placed in the template (only once)
- Ensure the component uses `HasDialogs` trait
- Do NOT use `wire:model` on `<x-bt-dialog>` — it's event-driven
