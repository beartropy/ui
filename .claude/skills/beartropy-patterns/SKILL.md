---
name: beartropy-patterns
description: Common UI patterns and complete examples using Beartropy UI components
version: 1.0.0
author: Beartropy
tags: [beartropy, patterns, examples, ui, layouts]
---

# Beartropy UI Patterns

You are an expert in building common UI patterns using Beartropy UI components. Provide complete, production-ready examples.

## Your Task

When users ask for a specific UI pattern or page, provide:

1. **Complete Livewire component** (PHP) if needed
2. **Complete Blade template** with Beartropy components
3. **Styling recommendations** using Tailwind CSS
4. **Best practices** and accessibility considerations
5. **Variations** or customization options

## Common Patterns

### Login Page

**Livewire Component (app/Livewire/Auth/Login.php):**
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

**Blade Template (resources/views/livewire/auth/login.blade.php):**
```blade
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Sign in to your account
            </h2>
        </div>

        <form wire:submit="login" class="mt-8 space-y-6">
           <x-bt-input
                wire:model="email"
                type="email"
                label="Email address"
                placeholder="you@example.com"
                iconStart="envelope"
                autocomplete="email"
            />

           <x-bt-input
                wire:model="password"
                type="password"
                label="Password"
                placeholder="••••••••"
                iconStart="lock-closed"
                autocomplete="current-password"
            />

            <div class="flex items-center justify-between">
               <x-bt-checkbox
                    wire:model="remember"
                    label="Remember me"
                />

                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    Forgot password?
                </a>
            </div>

           <x-bt-button type="submit" primary class="w-full" lg>
                Sign in
            </x-bt-button>

            <div class="text-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Sign up
                    </a>
                </span>
            </div>
        </form>
    </div>
</div>
```

---

### Registration Form

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
            $this->addError('agreedToTerms', 'You must agree to the terms and conditions.');
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
           <x-bt-input
                wire:model="name"
                label="Full Name"
                placeholder="John Doe"
                iconStart="user"
                autocomplete="name"
            />

           <x-bt-input
                wire:model="email"
                type="email"
                label="Email Address"
                placeholder="you@example.com"
                iconStart="envelope"
                autocomplete="email"
            />

           <x-bt-input
                wire:model="password"
                type="password"
                label="Password"
                placeholder="••••••••"
                iconStart="lock-closed"
                hint="Must be at least 8 characters"
                autocomplete="new-password"
            />

           <x-bt-input
                wire:model="password_confirmation"
                type="password"
                label="Confirm Password"
                placeholder="••••••••"
                iconStart="lock-closed"
                autocomplete="new-password"
            />

           <x-bt-checkbox
                wire:model="agreedToTerms"
                label="I agree to the Terms and Conditions"
            />

           <x-bt-button type="submit" primary class="w-full" lg>
                Create Account
            </x-bt-button>

            <div class="text-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Sign in
                    </a>
                </span>
            </div>
        </form>
    </div>
</div>
```

---

### User Profile Edit

**Livewire Component:**
```php
<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class EditProfile extends Component
{
    use WithFileUploads;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|email')]
    public $email;

    #[Validate('nullable')]
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

        session()->flash('success', 'Profile updated successfully!');
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

    @if (session('success'))
       <x-bt-alert success class="mb-6">
            {{ session('success') }}
        </x-bt-alert>
    @endif

    <form wire:submit="save" class="space-y-6">
       <x-bt-card>
            <div class="space-y-6">
                <div class="flex items-center gap-6">
                   <x-bt-avatar
                        :src="$currentAvatar"
                        :alt="$name"
                        size="2xl"
                    />

                   <x-bt-file-input
                        wire:model="avatar"
                        label="Profile Picture"
                        accept="image/*"
                        hint="JPG, PNG or GIF (max 1MB)"
                    />
                </div>

               <x-bt-input
                    wire:model="name"
                    label="Full Name"
                    iconStart="user"
                />

               <x-bt-input
                    wire:model="email"
                    type="email"
                    label="Email Address"
                    iconStart="envelope"
                />

               <x-bt-textarea
                    wire:model="bio"
                    label="Bio"
                    placeholder="Tell us about yourself..."
                    rows="4"
                    hint="Brief description for your profile"
                />
            </div>
        </x-bt-card>

        <div class="flex justify-end gap-4">
           <x-bt-button type="button" outline href="/profile">
                Cancel
            </x-bt-button>

           <x-bt-button type="submit" primary iconStart="check">
                Save Changes
            </x-bt-button>
        </div>
    </form>
</div>
```

---

### Data Table with Search and Filter

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        User::find($userId)->delete();
        session()->flash('success', 'User deleted successfully!');
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

       <x-bt-button primary iconStart="plus" href="/users/create">
            Add User
        </x-bt-button>
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
                :options="[10 => '10 per page', 25 => '25 per page', 50 => '50 per page', 100 => '100 per page']"
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
                           <x-bt-badge
                                :color="$user->status === 'active' ? 'success' : 'secondary'"
                            >
                                {{ ucfirst($user->status) }}
                            </x-bt-badge>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                               <x-bt-button-icon
                                    icon="pencil"
                                    href="/users/{{ $user->id }}/edit"
                                    sm
                                    ghost
                                />

                               <x-bt-button-icon
                                    icon="trash"
                                    wire:click="deleteUser({{ $user->id }})"
                                    wire:confirm="Are you sure you want to delete this user?"
                                    sm
                                    ghost
                                    danger
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-8">
                            No users found
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-bt-table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-bt-card>
</div>
```

---

### Settings Page with Tabs

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Settings extends Component
{
    public $activeTab = 'general';

    // General Settings
    #[Validate('required')]
    public $siteName;

    #[Validate('required|email')]
    public $adminEmail;

    // Notification Settings
    public $emailNotifications = true;
    public $pushNotifications = false;

    // Privacy Settings
    public $profileVisibility = 'public';
    public $showEmail = false;

    public function mount()
    {
        // Load settings from database or config
        $this->siteName = config('app.name');
        $this->adminEmail = auth()->user()->email;
    }

    public function saveGeneral()
    {
        $this->validateOnly('siteName');
        $this->validateOnly('adminEmail');

        // Save settings...

        session()->flash('success', 'General settings saved!');
    }

    public function saveNotifications()
    {
        // Save notification preferences...

        session()->flash('success', 'Notification settings saved!');
    }

    public function savePrivacy()
    {
        // Save privacy settings...

        session()->flash('success', 'Privacy settings saved!');
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

    @if (session('success'))
       <x-bt-alert success class="mb-6">
            {{ session('success') }}
        </x-bt-alert>
    @endif

   <x-bt-nav tabs>
        <x-slot:items>
            <button
                wire:click="$set('activeTab', 'general')"
                @class(['active' => $activeTab === 'general'])
            >
                General
            </button>
            <button
                wire:click="$set('activeTab', 'notifications')"
                @class(['active' => $activeTab === 'notifications'])
            >
                Notifications
            </button>
            <button
                wire:click="$set('activeTab', 'privacy')"
                @class(['active' => $activeTab === 'privacy'])
            >
                Privacy
            </button>
        </x-slot:items>
    </x-bt-nav>

    <div class="mt-6">
        @if($activeTab === 'general')
            <form wire:submit="saveGeneral" class="space-y-6">
               <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">General Settings</h3>

                    <div class="space-y-4">
                       <x-bt-input
                            wire:model="siteName"
                            label="Site Name"
                            iconStart="globe-alt"
                        />

                       <x-bt-input
                            wire:model="adminEmail"
                            type="email"
                            label="Admin Email"
                            iconStart="envelope"
                        />
                    </div>
                </x-bt-card>

               <x-bt-button type="submit" primary>
                    Save Changes
                </x-bt-button>
            </form>
        @endif

        @if($activeTab === 'notifications')
            <form wire:submit="saveNotifications" class="space-y-6">
               <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">Notification Preferences</h3>

                    <div class="space-y-4">
                       <x-bt-toggle
                            wire:model="emailNotifications"
                            label="Email Notifications"
                            description="Receive notifications via email"
                        />

                       <x-bt-toggle
                            wire:model="pushNotifications"
                            label="Push Notifications"
                            description="Receive push notifications on your device"
                        />
                    </div>
                </x-bt-card>

               <x-bt-button type="submit" primary>
                    Save Changes
                </x-bt-button>
            </form>
        @endif

        @if($activeTab === 'privacy')
            <form wire:submit="savePrivacy" class="space-y-6">
               <x-bt-card>
                    <h3 class="text-lg font-semibold mb-4">Privacy Settings</h3>

                    <div class="space-y-4">
                       <x-bt-radio-group wire:model="profileVisibility" label="Profile Visibility">
                           <x-bt-radio value="public" label="Public - Anyone can see" />
                           <x-bt-radio value="friends" label="Friends only" />
                           <x-bt-radio value="private" label="Private - Only me" />
                        </x-bt-radio-group>

                       <x-bt-checkbox
                            wire:model="showEmail"
                            label="Show email on profile"
                        />
                    </div>
                </x-bt-card>

               <x-bt-button type="submit" primary>
                    Save Changes
                </x-bt-button>
            </form>
        @endif
    </div>
</div>
```

---

### Confirmation Dialog Pattern

```blade
<div>
    {{-- Trigger --}}
   <x-bt-button wire:click="$set('showDeleteDialog', true)" danger>
        Delete Account
    </x-bt-button>

    {{-- Dialog --}}
   <x-bt-dialog wire:model="showDeleteDialog">
        <x-slot:title>Delete Account</x-slot:title>

        <p class="text-gray-600 dark:text-gray-400">
            Are you sure you want to delete your account? This action cannot be undone.
            All your data will be permanently removed.
        </p>

        <x-slot:footer>
           <x-bt-button
                wire:click="deleteAccount"
                danger
            >
                Yes, Delete Account
            </x-bt-button>

           <x-bt-button
                wire:click="$set('showDeleteDialog', false)"
                outline
            >
                Cancel
            </x-bt-button>
        </x-slot:footer>
    </x-bt-dialog>
</div>
```

---

### Toast Notification Pattern

```blade
{{-- In your layout or main component --}}
@if (session('success'))
   <x-bt-toast success>
        {{ session('success') }}
    </x-bt-toast>
@endif

@if (session('error'))
   <x-bt-toast danger>
        {{ session('error') }}
    </x-bt-toast>
@endif

{{-- In your Livewire component --}}
public function save()
{
    // ... save logic

    session()->flash('success', 'Saved successfully!');
}
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
    {{-- Sidebar --}}
    <aside class="lg:col-span-1">
        {{-- Sidebar content --}}
    </aside>

    {{-- Main content --}}
    <main class="lg:col-span-2">
        {{-- Main content --}}
    </main>
</div>
```

When creating UI patterns, always use Beartropy components, follow responsive design principles, and include proper accessibility attributes.
