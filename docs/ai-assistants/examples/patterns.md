# Common UI Patterns - Beartropy UI

Ready-to-use UI patterns for common application needs.

## Settings Page with Tabs

### Livewire Component
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
    public $smsNotifications = false;

    // Privacy Settings
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

        // Save to config or database...

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

### Blade Template
```blade
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>

    @if (session('success'))
        <x-bt-alert success class="mb-6">
            {{ session('success') }}
        </x-bt-alert>
    @endif

    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
        <div class="flex gap-4">
            <button
                wire:click="$set('activeTab', 'general')"
                @class([
                    'px-4 py-2 border-b-2 font-medium',
                    'border-blue-600 text-blue-600' => $activeTab === 'general',
                    'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== 'general'
                ])
            >
                General
            </button>
            <button
                wire:click="$set('activeTab', 'notifications')"
                @class([
                    'px-4 py-2 border-b-2 font-medium',
                    'border-blue-600 text-blue-600' => $activeTab === 'notifications',
                    'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== 'notifications'
                ])
            >
                Notifications
            </button>
            <button
                wire:click="$set('activeTab', 'privacy')"
                @class([
                    'px-4 py-2 border-b-2 font-medium',
                    'border-blue-600 text-blue-600' => $activeTab === 'privacy',
                    'border-transparent text-gray-500 hover:text-gray-700' => $activeTab !== 'privacy'
                ])
            >
                Privacy
            </button>
        </div>
    </div>

    {{-- Tab Content --}}
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

                    <x-bt-toggle
                        wire:model="smsNotifications"
                        label="SMS Notifications"
                        description="Receive SMS notifications for important updates"
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
                        label="Show email address on profile"
                    />
                </div>
            </x-bt-card>

            <x-bt-button type="submit" primary>
                Save Changes
            </x-bt-button>
        </form>
    @endif
</div>
```

---

## Confirmation Dialog Pattern

```blade
<div>
    {{-- Action Button --}}
    <x-bt-button wire:click="$set('showDeleteDialog', true)" danger>
        Delete Account
    </x-bt-button>

    {{-- Confirmation Dialog --}}
    <x-bt-dialog wire:model="showDeleteDialog">
        <x-slot:title>Delete Account</x-slot:title>

        <div class="space-y-4">
            <p class="text-gray-600 dark:text-gray-400">
                Are you sure you want to delete your account? This action cannot be undone.
                All your data will be permanently removed.
            </p>

            <x-bt-alert warning>
                This will delete all your data, including:
                <ul class="list-disc list-inside mt-2">
                    <li>Personal information</li>
                    <li>Files and uploads</li>
                    <li>Settings and preferences</li>
                </ul>
            </x-bt-alert>
        </div>

        <x-slot:footer>
            <x-bt-button
                wire:click="deleteAccount"
                danger
            >
                Yes, Delete My Account
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

## File Upload with Preview

### Livewire Component
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class FileUpload extends Component
{
    use WithFileUploads;

    #[Validate('required|image|max:2048')]
    public $photo;

    public $currentPhoto = null;

    public function mount()
    {
        $this->currentPhoto = auth()->user()->photo_url;
    }

    public function save()
    {
        $this->validate();

        $path = $this->photo->store('photos', 'public');

        auth()->user()->update(['photo_url' => $path]);

        $this->currentPhoto = $path;
        $this->photo = null;

        session()->flash('success', 'Photo uploaded successfully!');
    }

    public function removePhoto()
    {
        auth()->user()->update(['photo_url' => null]);
        $this->currentPhoto = null;

        session()->flash('success', 'Photo removed!');
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
```

### Blade Template
```blade
<div class="max-w-xl mx-auto p-6">
    <x-bt-card>
        <h3 class="text-lg font-semibold mb-4">Profile Photo</h3>

        @if (session('success'))
            <x-bt-alert success class="mb-4">
                {{ session('success') }}
            </x-bt-alert>
        @endif

        <div class="space-y-4">
            {{-- Current Photo --}}
            @if($currentPhoto)
                <div>
                    <label class="block text-sm font-medium mb-2">Current Photo</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($currentPhoto) }}" alt="Profile" class="w-24 h-24 rounded-full object-cover">

                        <x-bt-button wire:click="removePhoto" danger outline sm>
                            Remove Photo
                        </x-bt-button>
                    </div>
                </div>
            @endif

            {{-- Preview New Photo --}}
            @if($photo)
                <div>
                    <label class="block text-sm font-medium mb-2">Preview</label>
                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-24 h-24 rounded-full object-cover">
                </div>
            @endif

            {{-- Upload New Photo --}}
            <x-bt-file-input
                wire:model="photo"
                label="Upload New Photo"
                accept="image/*"
                hint="JPG, PNG or GIF (max 2MB)"
            />

            @if($photo)
                <x-bt-button wire:click="save" primary>
                    Upload Photo
                </x-bt-button>
            @endif
        </div>
    </x-bt-card>
</div>
```

---

## Empty State

```blade
<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
        No items yet
    </h3>

    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-sm mx-auto">
        Get started by creating your first item. It only takes a few seconds.
    </p>

    <x-bt-button primary iconStart="plus" href="/items/create">
        Create First Item
    </x-bt-button>
</div>
```

---

## Loading State

```blade
<div wire:loading.delay class="flex items-center justify-center py-12">
    <div class="text-center">
        <x-bt-loading size="lg" />
        <p class="mt-4 text-gray-600 dark:text-gray-400">
            Loading...
        </p>
    </div>
</div>
```

---

## Toast Notifications

**In Layout File:**
```blade
<!DOCTYPE html>
<html>
<head>
    {{-- ... --}}
</head>
<body>
    {{ $slot }}

    {{-- Toast Notifications --}}
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

    @if (session('warning'))
        <x-bt-toast warning>
            {{ session('warning') }}
        </x-bt-toast>
    @endif

    @if (session('info'))
        <x-bt-toast info>
            {{ session('info') }}
        </x-bt-toast>
    @endif
</body>
</html>
```

**In Livewire Components:**
```php
// Success
session()->flash('success', 'Operation completed successfully!');

// Error
session()->flash('error', 'Something went wrong. Please try again.');

// Warning
session()->flash('warning', 'Please review your information.');

// Info
session()->flash('info', 'Your session will expire in 5 minutes.');
```

---

## Dashboard Stats Cards

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Total Users --}}
    <x-bt-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Users</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                <p class="text-sm text-green-600">+12% from last month</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </x-bt-card>

    {{-- Revenue --}}
    <x-bt-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Revenue</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">$45,231</p>
                <p class="text-sm text-green-600">+23% from last month</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </x-bt-card>

    {{-- Active Sessions --}}
    <x-bt-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Active Sessions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">573</p>
                <p class="text-sm text-red-600">-3% from last hour</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </x-bt-card>

    {{-- Pending Tasks --}}
    <x-bt-card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Tasks</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">24</p>
                <p class="text-sm text-gray-600">Awaiting review</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </x-bt-card>
</div>
```

---

These patterns provide a solid foundation for common application UIs. Customize colors, sizes, and content to match your needs!
