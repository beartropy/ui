# Modal

A dialog overlay component with Alpine.js state management, optional Livewire `wire:model` sync, teleportation, and styled/unstyled modes.

## Basic Usage

```blade
{{-- Open via Alpine event --}}
<x-bt-button @click="$dispatch('open-modal-my-modal')">Open</x-bt-button>

<x-bt-modal id="my-modal">
    <p>Modal content here.</p>
</x-bt-modal>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Custom modal ID |
| `maxWidth` | `string` | `3xl` | Max width: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full |
| `zIndex` | `string` | `30` | Tailwind z-index number |
| `blur` | `string` | `none` | Backdrop blur: none, sm, md, lg, xl, 2xl, 3xl |
| `bgColor` | `string` | `bg-white dark:bg-gray-900` | Background color classes |
| `closeOnClickOutside` | `bool` | `true` | Close when clicking overlay |
| `styled` | `bool` | `false` | Enable styled mode (padded, bordered title/footer) |
| `showCloseButton` | `bool` | `true` | Show X close button |
| `centered` | `bool` | `false` | Center vertically (default: top with margin) |
| `teleport` | `bool` | `true` | Teleport to target element |
| `teleportTarget` | `string` | `body` | Teleport target selector |

## Slots

| Slot | Description |
|------|-------------|
| default | Modal body content |
| title | Modal title (styled mode applies preset classes) |
| footer | Modal footer (styled mode applies preset classes) |

## Opening & Closing

### Via Alpine Events

Each modal listens for `open-modal-{id}` and `close-modal-{id}` window events:

```blade
<x-bt-button @click="$dispatch('open-modal-settings')">Open</x-bt-button>

<x-bt-modal id="settings">
    <p>Settings content.</p>
    <x-bt-button @click="$dispatch('close-modal-settings')">Done</x-bt-button>
</x-bt-modal>
```

### Via JavaScript

```js
import { openModal, closeModal } from 'beartropy-ui';

openModal('settings');
closeModal('settings');
```

> **Note:** The JS helpers lowercase the ID before dispatching. Use lowercase IDs to avoid mismatches.

### Via Livewire `wire:model`

```blade
<x-bt-modal wire:model="showConfirm">
    <p>Are you sure?</p>
</x-bt-modal>
```

When using `wire:model`, the model value becomes the modal ID. The modal syncs bidirectionally: Livewire property changes open/close the modal, and user close actions update the Livewire property.

## Sizes

```blade
<x-bt-modal maxWidth="sm">Small modal</x-bt-modal>
<x-bt-modal maxWidth="full">Full-width modal</x-bt-modal>
```

## Backdrop Blur

```blade
<x-bt-modal blur="lg">Blurred backdrop</x-bt-modal>
```

## Styled Mode

Styled mode adds padding, bordered title/footer, and text styling:

```blade
<x-bt-modal :styled="true">
    <x-slot:title>Confirm Action</x-slot:title>
    Are you sure you want to proceed?
    <x-slot:footer>
        <x-bt-button @click="$dispatch('close-modal-...')">Cancel</x-bt-button>
        <x-bt-button solid blue>Confirm</x-bt-button>
    </x-slot:footer>
</x-bt-modal>
```

## Centered

```blade
<x-bt-modal :centered="true">Vertically centered</x-bt-modal>
```

## Close Behavior

The modal closes via:
- Escape key (always active)
- Clicking the overlay (when `closeOnClickOutside` is true)
- The X button (when `showCloseButton` is true or `styled` is true)
- Dispatching the `close-modal-{id}` event

```blade
{{-- Persistent modal: no overlay click, no close button --}}
<x-bt-modal :closeOnClickOutside="false" :showCloseButton="false">
    Must click a button to close.
</x-bt-modal>
```

The `close-on-click-outside` kebab-case attribute is also supported:

```blade
<x-bt-modal :close-on-click-outside="false">Content</x-bt-modal>
```

## Teleport

By default, the modal teleports to `<body>` to avoid z-index stacking issues. Disable or change the target:

```blade
{{-- Disable teleport --}}
<x-bt-modal :teleport="false">Inline modal</x-bt-modal>

{{-- Custom target --}}
<x-bt-modal teleportTarget="#app">Content</x-bt-modal>
```

## Custom Background

```blade
<x-bt-modal bgColor="bg-gray-800 text-white">Dark modal</x-bt-modal>
```

## Dark Mode

Dark mode styles are included automatically via `dark:` classes on the background, overlay, and styled mode elements.
