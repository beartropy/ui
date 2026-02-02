# Table Examples - Beartropy UI

Ready-to-use data table examples with search, filters, and actions.

## Basic Data Table

### Livewire Component
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::paginate(10);

        return view('livewire.user-table', compact('users'));
    }
}
```

### Blade Template
```blade
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>

    <x-bt-card>
        <x-bt-table>
            <x-slot:header>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
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
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <x-bt-button-icon
                                icon="pencil"
                                href="/users/{{ $user->id }}/edit"
                                sm
                                ghost
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-8">
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

## Table with Search and Filters

### Livewire Component
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
    public $roleFilter = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
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
            ->when($this->roleFilter, fn($q) =>
                $q->where('role', $this->roleFilter)
            )
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.user-table', compact('users'));
    }
}
```

### Blade Template
```blade
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>

        <x-bt-button primary iconStart="plus" href="/users/create">
            Add User
        </x-bt-button>
    </div>

    @if (session('success'))
        <x-bt-alert success>
            {{ session('success') }}
        </x-bt-alert>
    @endif

    <x-bt-card>
        {{-- Filters --}}
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
                :options="[
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'pending' => 'Pending'
                ]"
                placeholder="All Statuses"
                clearable
                class="w-full sm:w-48"
            />

            <x-bt-select
                wire:model.live="roleFilter"
                :options="[
                    'admin' => 'Admin',
                    'user' => 'User',
                    'moderator' => 'Moderator'
                ]"
                placeholder="All Roles"
                clearable
                class="w-full sm:w-48"
            />

            <x-bt-select
                wire:model.live="perPage"
                :options="[
                    10 => '10 per page',
                    25 => '25 per page',
                    50 => '50 per page',
                    100 => '100 per page'
                ]"
                class="w-full sm:w-40"
            />
        </div>

        {{-- Loading indicator --}}
        <div wire:loading.delay class="text-gray-500 mb-4">
            Loading...
        </div>

        {{-- Table --}}
        <x-bt-table>
            <x-slot:header>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
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
                            <x-bt-badge secondary>
                                {{ ucfirst($user->role) }}
                            </x-bt-badge>
                        </td>
                        <td>
                            <x-bt-badge :color="match($user->status) {
                                'active' => 'success',
                                'inactive' => 'secondary',
                                'pending' => 'warning',
                                default => 'secondary'
                            }">
                                {{ ucfirst($user->status) }}
                            </x-bt-badge>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <x-bt-button-icon
                                    icon="eye"
                                    href="/users/{{ $user->id }}"
                                    sm
                                    ghost
                                />

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
                        <td colspan="6" class="text-center text-gray-500 py-8">
                            No users found
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-bt-table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-bt-card>
</div>
```

---

## Table with Bulk Actions

### Livewire Component
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTableWithBulk extends Component
{
    use WithPagination;

    public $selectedUsers = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = User::pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function deleteSelected()
    {
        User::whereIn('id', $this->selectedUsers)->delete();
        $this->selectedUsers = [];
        $this->selectAll = false;

        session()->flash('success', 'Selected users deleted successfully!');
    }

    public function exportSelected()
    {
        // Export logic here
        session()->flash('success', 'Export started!');
    }

    public function render()
    {
        $users = User::paginate(10);

        return view('livewire.user-table-with-bulk', compact('users'));
    }
}
```

### Blade Template
```blade
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>

    @if (session('success'))
        <x-bt-alert success>
            {{ session('success') }}
        </x-bt-alert>
    @endif

    {{-- Bulk actions bar --}}
    @if(count($selectedUsers) > 0)
        <x-bt-card>
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium">
                    {{ count($selectedUsers) }} user(s) selected
                </span>

                <div class="flex gap-2">
                    <x-bt-button
                        wire:click="exportSelected"
                        outline
                        iconStart="arrow-down-tray"
                    >
                        Export
                    </x-bt-button>

                    <x-bt-button
                        wire:click="deleteSelected"
                        wire:confirm="Are you sure you want to delete {{ count($selectedUsers) }} user(s)?"
                        danger
                        iconStart="trash"
                    >
                        Delete Selected
                    </x-bt-button>
                </div>
            </div>
        </x-bt-card>
    @endif

    <x-bt-card>
        <x-bt-table>
            <x-slot:header>
                <tr>
                    <th class="w-12">
                        <x-bt-checkbox
                            wire:model.live="selectAll"
                            label=""
                        />
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </x-slot:header>

            <x-slot:body>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <x-bt-checkbox
                                wire:model.live="selectedUsers"
                                value="{{ $user->id }}"
                                label=""
                            />
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <x-bt-avatar :src="$user->avatar" :alt="$user->name" />
                                <span class="font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <x-bt-button-icon
                                icon="pencil"
                                href="/users/{{ $user->id }}/edit"
                                sm
                                ghost
                            />
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-bt-table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-bt-card>
</div>
```

---

## Sortable Table

### Livewire Component
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SortableUserTable extends Component
{
    use WithPagination;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $users = User::orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.sortable-user-table', compact('users'));
    }
}
```

### Blade Template
```blade
<div class="space-y-4">
    <x-bt-card>
        <x-bt-table>
            <x-slot:header>
                <tr>
                    <th>
                        <button wire:click="sortBy('name')" class="flex items-center gap-2">
                            Name
                            @if($sortField === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th>
                        <button wire:click="sortBy('email')" class="flex items-center gap-2">
                            Email
                            @if($sortField === 'email')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th>
                        <button wire:click="sortBy('created_at')" class="flex items-center gap-2">
                            Created
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th>Actions</th>
                </tr>
            </x-slot:header>

            <x-slot:body>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <x-bt-button-icon icon="pencil" href="/users/{{ $user->id }}/edit" sm ghost />
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-bt-table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-bt-card>
</div>
```

---

## Table with Inline Editing

### Livewire Component
```php
<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class InlineEditTable extends Component
{
    public $users;
    public $editingId = null;
    public $editName = '';
    public $editEmail = '';

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function edit($userId)
    {
        $user = User::find($userId);
        $this->editingId = $userId;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
    }

    public function save()
    {
        $this->validate([
            'editName' => 'required|min:3',
            'editEmail' => 'required|email',
        ]);

        User::find($this->editingId)->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
        ]);

        $this->cancel();
        $this->loadUsers();

        session()->flash('success', 'User updated!');
    }

    public function cancel()
    {
        $this->editingId = null;
        $this->editName = '';
        $this->editEmail = '';
    }

    public function render()
    {
        return view('livewire.inline-edit-table');
    }
}
```

### Blade Template
```blade
<div class="space-y-4">
    @if (session('success'))
        <x-bt-alert success>{{ session('success') }}</x-bt-alert>
    @endif

    <x-bt-card>
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
                        <td>
                            @if($editingId === $user->id)
                                <x-bt-input wire:model="editName" placeholder="Name" />
                            @else
                                {{ $user->name }}
                            @endif
                        </td>
                        <td>
                            @if($editingId === $user->id)
                                <x-bt-input wire:model="editEmail" type="email" placeholder="Email" />
                            @else
                                {{ $user->email }}
                            @endif
                        </td>
                        <td>
                            @if($editingId === $user->id)
                                <div class="flex gap-2">
                                    <x-bt-button wire:click="save" success sm>Save</x-bt-button>
                                    <x-bt-button wire:click="cancel" outline sm>Cancel</x-bt-button>
                                </div>
                            @else
                                <x-bt-button-icon
                                    icon="pencil"
                                    wire:click="edit({{ $user->id }})"
                                    sm
                                    ghost
                                />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-bt-table>
    </x-bt-card>
</div>
```

---

These table examples cover common data management patterns. Customize them based on your specific needs!
