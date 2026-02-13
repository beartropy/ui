---
name: beartropy-patterns
description: Common UI patterns and complete examples using Beartropy UI components
version: 2.0.0
author: Beartropy
tags: [beartropy, patterns, examples, ui, layouts]
---

# Beartropy UI Patterns

You are an expert in building common UI patterns using Beartropy UI components. Provide complete, production-ready examples.

All components use the `<x-bt-*>` tag prefix (short alias for `<x-beartropy-ui::*>`).

## Your Task

When users ask for a specific UI pattern or page, provide:

1. **Complete Livewire component** (PHP) if needed
2. **Complete Blade template** with Beartropy components
3. **Styling recommendations** using Tailwind CSS
4. **Best practices** and accessibility considerations

---

## Login Page

**Livewire Component:**
```php
<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Invalid credentials.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
```

**Blade Template:**
```blade
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900 dark:text-white">
            Sign in to your account
        </h2>

        <form wire:submit="login" class="mt-8 space-y-6">
            <x-bt-input
                wire:model="email"
                type="email"
                label="Email address"
                placeholder="you@example.com"
                iconStart="envelope"
            />

            <x-bt-input
                wire:model="password"
                type="password"
                label="Password"
                placeholder="••••••••"
                iconStart="lock-closed"
            />

            <div class="flex items-center justify-between">
                <x-bt-checkbox wire:model="remember" label="Remember me" />
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Forgot password?
                </a>
            </div>

            <x-bt-button type="submit" primary class="w-full" lg>
                Sign in
            </x-bt-button>
        </form>
    </div>
</div>
```

---

## Registration Form

**Livewire Component:**
```php
<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email|unique:users,email')]
    public $email = '';

    #[Validate('required|min:8|confirmed')]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    public $agreedToTerms = false;

    public function register()
    {
        if (!$this->agreedToTerms) {
            $this->addError('agreedToTerms', 'You must agree to the terms.');
            return;
        }

        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
```

**Blade Template:**
```blade
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4">
    <div class="max-w-md w-full space-y-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900 dark:text-white">
            Create your account
        </h2>

        <form wire:submit="register" class="space-y-6">
            <x-bt-input wire:model="name" label="Full Name" placeholder="John Doe" iconStart="user" />
            <x-bt-input wire:model="email" type="email" label="Email" placeholder="you@example.com" iconStart="envelope" />
            <x-bt-input wire:model="password" type="password" label="Password" iconStart="lock-closed" hint="At least 8 characters" />
            <x-bt-input wire:model="password_confirmation" type="password" label="Confirm Password" iconStart="lock-closed" />
            <x-bt-checkbox wire:model="agreedToTerms" label="I agree to the Terms and Conditions" />
            <x-bt-button type="submit" primary class="w-full" lg>Create Account</x-bt-button>
        </form>
    </div>
</div>
```

---

## User Profile Edit

**Livewire Component:**
```php
<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Beartropy\Ui\Traits\HasToasts;

class EditProfile extends Component
{
    use WithFileUploads, HasToasts;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|email')]
    public $email;

    public $bio;

    #[Validate('nullable|image|max:1024')]
    public $avatar;

    public $currentAvatar;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->currentAvatar = $user->avatar_url;
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        if ($this->avatar) {
            $user->avatar_url = $this->avatar->store('avatars', 'public');
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'bio' => $this->bio,
        ]);

        $this->toast()->success('Saved!', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}
```

**Blade Template:**
```blade
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Profile</h1>

    <form wire:submit="save" class="space-y-6">
        <x-bt-card>
            <div class="space-y-6">
                <div class="flex items-center gap-6">
                    <x-bt-avatar :src="$currentAvatar" :alt="$name" size="2xl" />
                    <x-bt-file-input wire:model="avatar" label="Profile Picture" accept="image/*" hint="JPG, PNG or GIF (max 1MB)" />
                </div>

                <x-bt-input wire:model="name" label="Full Name" iconStart="user" />
                <x-bt-input wire:model="email" type="email" label="Email" iconStart="envelope" />
                <x-bt-textarea wire:model="bio" label="Bio" placeholder="Tell us about yourself..." rows="4" />
            </div>
        </x-bt-card>

        <div class="flex justify-end gap-4">
            <x-bt-button type="button" outline href="/profile">Cancel</x-bt-button>
            <x-bt-button type="submit" primary iconStart="check">Save Changes</x-bt-button>
        </div>
    </form>

    <x-bt-toast />
</div>
```

---

## Data Table with Search and Filter

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Beartropy\Ui\Traits\HasDialogs;
use Beartropy\Ui\Traits\HasToasts;

class UserTable extends Component
{
    use WithPagination, HasDialogs, HasToasts;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($userId)
    {
        $this->dialog()->delete(
            'Delete this user?',
            'This action cannot be undone. All their data will be removed.',
            [
                'method' => 'deleteUser',
                'params' => [$userId],
            ]
        );
    }

    public function deleteUser($userId)
    {
        User::find($userId)->delete();
        $this->toast()->success('Deleted', 'User removed successfully.');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->statusFilter, fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->paginate($this->perPage);

        return view('livewire.user-table', compact('users'));
    }
}
```

**Blade Template:**
```blade
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
        <x-bt-button primary iconStart="plus" href="/users/create">Add User</x-bt-button>
    </div>

    <x-bt-card>
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <x-bt-input
                wire:model.live.debounce.300ms="search"
                placeholder="Search users..."
                iconStart="magnifying-glass"
                clearable
                class="flex-1"
            />

            <x-bt-select
                wire:model.live="statusFilter"
                :options="['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending']"
                placeholder="All Statuses"
                clearable
                class="w-full sm:w-48"
            />

            <x-bt-select
                wire:model.live="perPage"
                :options="[10 => '10 per page', 25 => '25 per page', 50 => '50 per page']"
                class="w-full sm:w-40"
            />
        </div>

        <x-bt-table>
            <x-slot:header>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </x-slot:header>

            <x-slot:body>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <x-bt-avatar :src="$user->avatar" :alt="$user->name" />
                                <span class="font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <x-bt-badge :color="$user->status === 'active' ? 'success' : 'secondary'">
                                {{ ucfirst($user->status) }}
                            </x-bt-badge>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <x-bt-button-icon icon="pencil" href="/users/{{ $user->id }}/edit" sm ghost />
                                <x-bt-button-icon icon="trash" wire:click="confirmDelete({{ $user->id }})" sm ghost danger />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-8">No users found</td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-bt-table>

        <div class="mt-4">{{ $users->links() }}</div>
    </x-bt-card>

    <x-bt-dialog />
    <x-bt-toast />
</div>
```

---

## Settings Page with Tabs

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Beartropy\Ui\Traits\HasToasts;

class Settings extends Component
{
    use HasToasts;

    public $activeTab = 'general';

    #[Validate('required')]
    public $siteName;

    #[Validate('required|email')]
    public $adminEmail;

    public $emailNotifications = true;
    public $pushNotifications = false;
    public $profileVisibility = 'public';
    public $showEmail = false;

    public function mount()
    {
        $this->siteName = config('app.name');
        $this->adminEmail = auth()->user()->email;
    }

    public function saveGeneral()
    {
        $this->validateOnly('siteName');
        $this->validateOnly('adminEmail');
        // Save...
        $this->toast()->success('Saved!', 'General settings updated.');
    }

    public function saveNotifications()
    {
        // Save...
        $this->toast()->success('Saved!', 'Notification preferences updated.');
    }

    public function savePrivacy()
    {
        // Save...
        $this->toast()->success('Saved!', 'Privacy settings updated.');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
```

**Blade Template:**
```blade
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>

    <x-bt-nav tabs>
        <x-slot:items>
            <button wire:click="$set('activeTab', 'general')" @class(['active' => $activeTab === 'general'])>General</button>
            <button wire:click="$set('activeTab', 'notifications')" @class(['active' => $activeTab === 'notifications'])>Notifications</button>
            <button wire:click="$set('activeTab', 'privacy')" @class(['active' => $activeTab === 'privacy'])>Privacy</button>
        </x-slot:items>
    </x-bt-nav>

    <div class="mt-6">
        @if($activeTab === 'general')
            <form wire:submit="saveGeneral" class="space-y-6">
                <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">General Settings</h3>
                    <div class="space-y-4">
                        <x-bt-input wire:model="siteName" label="Site Name" iconStart="globe-alt" />
                        <x-bt-input wire:model="adminEmail" type="email" label="Admin Email" iconStart="envelope" />
                    </div>
                </x-bt-card>
                <x-bt-button type="submit" primary>Save Changes</x-bt-button>
            </form>
        @endif

        @if($activeTab === 'notifications')
            <form wire:submit="saveNotifications" class="space-y-6">
                <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">Notification Preferences</h3>
                    <div class="space-y-4">
                        <x-bt-toggle wire:model="emailNotifications" label="Email Notifications" description="Receive notifications via email" />
                        <x-bt-toggle wire:model="pushNotifications" label="Push Notifications" description="Receive push notifications on your device" />
                    </div>
                </x-bt-card>
                <x-bt-button type="submit" primary>Save Changes</x-bt-button>
            </form>
        @endif

        @if($activeTab === 'privacy')
            <form wire:submit="savePrivacy" class="space-y-6">
                <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">Privacy Settings</h3>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Profile Visibility</label>
                            <x-bt-radio wire:model="profileVisibility" value="public" label="Public — Anyone can see" />
                            <x-bt-radio wire:model="profileVisibility" value="friends" label="Friends only" />
                            <x-bt-radio wire:model="profileVisibility" value="private" label="Private — Only me" />
                        </div>
                        <x-bt-checkbox wire:model="showEmail" label="Show email on profile" />
                    </div>
                </x-bt-card>
                <x-bt-button type="submit" primary>Save Changes</x-bt-button>
            </form>
        @endif
    </div>

    <x-bt-toast />
</div>
```

---

## Dialog Pattern (Programmatic Confirm/Alert)

**Important:** Dialog is event-driven and programmatic. It does NOT use `wire:model` or Blade slots for content. Place `<x-bt-dialog />` once per page.

**Livewire Component:**
```php
use Beartropy\Ui\Traits\HasDialogs;

class MyComponent extends Component
{
    use HasDialogs;

    // Simple alerts
    public function showSuccess()
    {
        $this->dialog()->success('Saved!', 'Your changes have been saved.');
    }

    public function showError()
    {
        $this->dialog()->error('Failed', 'Could not save your changes.');
    }

    // Confirmation dialog
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

    // Custom confirm
    public function confirmPublish()
    {
        $this->dialog()->confirm([
            'title' => 'Publish this article?',
            'description' => 'It will be visible to all users immediately.',
            'accept' => [
                'label' => 'Yes, publish',
                'method' => 'publish',
            ],
            'reject' => [
                'label' => 'Not yet',
            ],
        ]);
    }

    public function deleteItem($id)
    {
        // Delete logic...
    }

    public function publish()
    {
        // Publish logic...
    }
}
```

**Blade Template:**
```blade
<div>
    <x-bt-button wire:click="confirmDelete({{ $item->id }})" danger>
        Delete
    </x-bt-button>

    <x-bt-button wire:click="confirmPublish" primary>
        Publish
    </x-bt-button>

    {{-- Place once in the layout — NO slots, NO wire:model --}}
    <x-bt-dialog />
</div>
```

**JS Alternative (no Livewire needed):**
```js
// From JavaScript
dialog.success('Saved!', 'Your changes have been saved.');
dialog.confirm({
    title: 'Are you sure?',
    accept: { label: 'Yes', method: 'doAction', params: [1] },
    reject: { label: 'Cancel' },
    componentId: 'livewire-component-id',
});
```

---

## Toast Notification Pattern

**Important:** Toast is also event-driven. Place `<x-bt-toast />` once in your layout.

**Livewire Component:**
```php
use Beartropy\Ui\Traits\HasToasts;

class MyComponent extends Component
{
    use HasToasts;

    public function save()
    {
        // Save logic...
        $this->toast()->success('Saved!', 'Your changes have been saved.');
    }

    public function delete()
    {
        // Delete logic...
        $this->toast()->success('Deleted', 'Item removed.', 5000, null, 'Undo', '/restore/123');
    }

    public function handleError()
    {
        $this->toast()->error('Error', 'Could not complete the operation.');
    }
}
```

**Blade Template (in layout):**
```blade
{{-- Place once in your main layout, not per-page --}}
<x-bt-toast />

{{-- Custom position and limit --}}
<x-bt-toast position="bottom-right" :max-visible="3" />
```

**JS Alternative:**
```js
window.$beartropy.toast.success('Saved!', 'Your changes have been saved.');
window.$beartropy.toast.error('Error', 'Something went wrong.');
```

---

## File Upload Pattern

**Compact (File Input):**
```blade
<x-bt-file-input
    wire:model="document"
    label="Upload Document"
    accept=".pdf,.doc,.docx"
    hint="Max 10MB"
/>
```

**Full Upload Zone (File Dropzone):**

```php
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $documents = [];

    public function upload()
    {
        $this->validate([
            'documents.*' => 'file|max:10240',
        ]);

        foreach ($this->documents as $doc) {
            $doc->store('documents');
        }
    }
}
```

```blade
<form wire:submit="upload" class="space-y-4">
    <x-bt-file-dropzone
        wire:model="documents"
        label="Project Documents"
        accept=".pdf,.doc,.docx"
        :maxFileSize="10485760"
        :maxFiles="5"
        help="Max 10MB per file, up to 5 files"
    />

    <x-bt-button type="submit" primary>Upload All</x-bt-button>
</form>
```

**With Existing Files (edit mode):**
```blade
<x-bt-file-dropzone
    wire:model="newDocuments"
    :existing-files="[
        ['name' => 'report.pdf', 'url' => '/storage/report.pdf', 'size' => 1024, 'type' => 'application/pdf'],
    ]"
    label="Documents"
/>
```

---

## Modal with Form

**Important:** Modal is slot-based and controlled with `wire:model`. Use Modal for custom content (forms, layouts). Use Dialog for programmatic alerts/confirms.

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

---

## Layout Tips

### Responsive Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Cards or content --}}
</div>
```

### Page Container
```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page content --}}
</div>
```

### Two-Column Layout
```blade
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <aside class="lg:col-span-1">{{-- Sidebar --}}</aside>
    <main class="lg:col-span-2">{{-- Main content --}}</main>
</div>
```

---

## Key Distinctions

| Want to... | Use this | API Style |
|---|---|---|
| Show alert/confirm popup | `<x-bt-dialog />` | **Programmatic:** `$this->dialog()->success(...)` — no slots, no wire:model |
| Show custom popup with forms | `<x-bt-modal>` | **Slot-based:** `wire:model`, `<x-slot:title>`, `<x-slot:footer>` |
| Show brief auto-dismiss message | `<x-bt-toast />` | **Programmatic:** `$this->toast()->success(...)` — no slots, no wire:model |
| Show inline page message | `<x-bt-alert>` | **Blade component:** `<x-bt-alert success>Message</x-bt-alert>` |
