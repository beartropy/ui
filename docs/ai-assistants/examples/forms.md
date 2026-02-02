# Form Examples - Beartropy UI

Ready-to-use form examples using Beartropy UI components.

## Basic Contact Form

### Livewire Component
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

    public function submit()
    {
        $this->validate();

        // Send email or save to database...

        session()->flash('success', 'Message sent successfully!');
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
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Contact Us</h1>

    @if (session('success'))
        <x-bt-alert success class="mb-6">
            {{ session('success') }}
        </x-bt-alert>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <x-bt-input
            wire:model="name"
            label="Full Name"
            placeholder="John Doe"
            iconStart="user"
        />

        <x-bt-input
            wire:model="email"
            type="email"
            label="Email Address"
            placeholder="you@example.com"
            iconStart="envelope"
        />

        <x-bt-select
            wire:model="subject"
            :options="[
                'general' => 'General Inquiry',
                'support' => 'Support',
                'sales' => 'Sales',
                'feedback' => 'Feedback'
            ]"
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

        <div class="flex justify-end gap-4">
            <x-bt-button type="button" wire:click="$reset" outline>
                Clear
            </x-bt-button>

            <x-bt-button type="submit" primary iconStart="paper-airplane">
                Send Message
            </x-bt-button>
        </div>
    </form>
</div>
```

---

## Login Form

### Livewire Component
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

### Blade Template
```blade
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
    <div class="max-w-md w-full space-y-8 p-6">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900 dark:text-white">
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

## Registration Form

### Livewire Component
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

### Blade Template
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
            @error('agreedToTerms')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

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

## Profile Edit Form with File Upload

### Livewire Component
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

    #[Validate('nullable|max:500')]
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

### Blade Template
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
                    hint="Brief description for your profile (max 500 characters)"
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

## Search Form with Filters

```blade
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4">
        <x-bt-input
            wire:model.live.debounce.300ms="search"
            placeholder="Search..."
            iconStart="magnifying-glass"
            clearable
            class="flex-1"
        />

        <x-bt-select
            wire:model.live="category"
            :options="$categories"
            placeholder="All Categories"
            clearable
            class="w-full sm:w-48"
        />

        <x-bt-select
            wire:model.live="status"
            :options="['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending']"
            placeholder="All Statuses"
            clearable
            class="w-full sm:w-48"
        />
    </div>

    <div wire:loading.delay class="text-gray-500">
        Searching...
    </div>
</div>
```

---

## Multi-Step Form

### Livewire Component
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class MultiStepForm extends Component
{
    public $currentStep = 1;

    // Step 1
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    // Step 2
    #[Validate('required')]
    public $address = '';

    #[Validate('required')]
    public $city = '';

    // Step 3
    #[Validate('required')]
    public $plan = '';

    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function validateCurrentStep()
    {
        if ($this->currentStep === 1) {
            $this->validateOnly('name');
            $this->validateOnly('email');
        } elseif ($this->currentStep === 2) {
            $this->validateOnly('address');
            $this->validateOnly('city');
        }
    }

    public function submit()
    {
        $this->validate();

        // Process form...

        session()->flash('success', 'Registration complete!');
    }

    public function render()
    {
        return view('livewire.multi-step-form');
    }
}
```

### Blade Template
```blade
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Registration (Step {{ $currentStep }} of 3)</h1>

    <div class="mb-8">
        <div class="flex justify-between">
            @for ($i = 1; $i <= 3; $i++)
                <div @class([
                    'flex-1 h-2 rounded',
                    'bg-blue-600' => $i <= $currentStep,
                    'bg-gray-200' => $i > $currentStep,
                    'mr-2' => $i < 3,
                ])></div>
            @endfor
        </div>
    </div>

    <form wire:submit="submit" class="space-y-6">
        @if ($currentStep === 1)
            <x-bt-card>
                <h3 class="text-lg font-semibold mb-4">Personal Information</h3>

                <div class="space-y-4">
                    <x-bt-input wire:model="name" label="Full Name" iconStart="user" />
                    <x-bt-input wire:model="email" type="email" label="Email" iconStart="envelope" />
                </div>
            </x-bt-card>
        @endif

        @if ($currentStep === 2)
            <x-bt-card>
                <h3 class="text-lg font-semibold mb-4">Address</h3>

                <div class="space-y-4">
                    <x-bt-input wire:model="address" label="Street Address" />
                    <x-bt-input wire:model="city" label="City" />
                </div>
            </x-bt-card>
        @endif

        @if ($currentStep === 3)
            <x-bt-card>
                <h3 class="text-lg font-semibold mb-4">Choose Plan</h3>

                <x-bt-radio-group wire:model="plan" label="Select a Plan">
                    <x-bt-radio value="free" label="Free - $0/month" />
                    <x-bt-radio value="pro" label="Pro - $10/month" />
                    <x-bt-radio value="enterprise" label="Enterprise - $50/month" />
                </x-bt-radio-group>
            </x-bt-card>
        @endif

        <div class="flex justify-between">
            @if ($currentStep > 1)
                <x-bt-button type="button" wire:click="previousStep" outline>
                    Previous
                </x-bt-button>
            @else
                <div></div>
            @endif

            @if ($currentStep < 3)
                <x-bt-button type="button" wire:click="nextStep" primary>
                    Next
                </x-bt-button>
            @else
                <x-bt-button type="submit" primary>
                    Complete Registration
                </x-bt-button>
            @endif
        </div>
    </form>
</div>
```

---

These examples are production-ready and follow Beartropy UI best practices. Copy and adapt them for your needs!
