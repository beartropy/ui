---
name: beartropy-component
description: Get detailed information and examples for specific Beartropy UI components
version: 2.0.0
author: Beartropy
tags: [beartropy, ui, components, documentation, examples]
---

# Beartropy Component Helper

You are an expert in Beartropy UI components. Use this guide to pick the right component and provide accurate props, slots, and examples.

All components use the `<x-bt-*>` tag prefix (short alias for `<x-beartropy-ui::*>`).

---

## Choosing the Right Component

When a user describes what they need, use this guide to pick the correct component. This is the most important section — read it first.

### File Uploads

| User says... | Use this | Why |
|---|---|---|
| "upload a file", "attach a document", "profile photo" | `<x-bt-file-input>` | Button-style trigger, looks like a text input, simple single/multiple file selection |
| "drag and drop files", "upload multiple images with preview", "batch upload" | `<x-bt-file-dropzone>` | Dedicated drop area with drag-drop, image previews, progress bars, file management (add/remove), client-side validation (maxFileSize, maxFiles) |

**Key difference:** `file-input` is compact (inline trigger). `file-dropzone` is a dedicated upload zone with drag-drop, previews, and progress tracking.

### Overlays & Popups

| User says... | Use this | Why |
|---|---|---|
| "confirmation before delete", "success message popup", "alert the user" | `<x-bt-dialog>` | **Event-driven, programmatic.** Use `HasDialogs` trait: `$this->dialog()->confirm(...)`. Single instance per page, no Blade slots. Has built-in types: success, warning, error, confirm, danger. |
| "form in a popup", "edit modal", "custom popup content" | `<x-bt-modal>` | **Slot-based, wire:model controlled.** Has `title`, `footer`, and default slots. Use for custom content (forms, complex layouts). |
| "tooltip on hover" | `<x-bt-tooltip>` | Hover/focus tooltip with text content |
| "right-click menu", "action menu" | `<x-bt-dropdown>` | Click-triggered dropdown with menu items |
| "command palette", "Cmd+K search" | `<x-bt-command-palette>` | Keyboard-driven search/command interface |

**Quick rule:** Dialog = programmatic alerts/confirms (no slots). Modal = custom content with slots (forms, layouts).

### Dropdowns & Selection

| User says... | Use this | Why |
|---|---|---|
| "dropdown select", "multi-select", "remote data dropdown", "select with search" | `<x-bt-select>` | Full-featured: search, multi-select, remote data (remoteUrl), infinite scroll, object mapping (optionLabel/optionValue/optionIcon/optionAvatar), autosave |
| "autocomplete", "type-ahead search", "simple text lookup" | `<x-bt-lookup>` | Autocomplete-style: text input with filtered dropdown, diacritic-insensitive search, simpler than Select |

**Key difference:** Select is a full dropdown with badges, avatars, remote data. Lookup is a text input with autocomplete suggestions.

### Notifications & Feedback

| User says... | Use this | Why |
|---|---|---|
| "show success after saving", "confirm action popup" | `<x-bt-dialog>` | Programmatic: `$this->dialog()->success('Saved!')` or `dialog.success('Saved!')` from JS |
| "toast notification", "brief message" | `<x-bt-toast>` | Auto-dismissing notification, positioned at screen edge |
| "static alert banner", "info box", "warning message in page" | `<x-bt-alert>` | Inline alert block with icon, colors, dismissible option |

**Quick rule:** Dialog = interactive popup. Toast = auto-dismiss notification. Alert = inline in-page message.

### Tags & Badges

| User says... | Use this | Why |
|---|---|---|
| "removable tags", "chip input", "editable labels" | `<x-bt-tag>` | Interactive: removable, has close button, used in tag inputs |
| "status label", "count indicator", "small label" | `<x-bt-badge>` | Display-only: small colored label, no interaction |

---

## Available Components

### Form Components
- **Input** (`x-bt-input`) — Text, email, password, number inputs with icons, clear button, copy button, spinner
- **Textarea** (`x-bt-textarea`) — Multi-line text input with auto-resize option
- **Select** (`x-bt-select`) — Single/multiple select with search, remote data, object mapping, autosave
- **Checkbox** (`x-bt-checkbox`) — Checkbox with label, description, indeterminate state
- **Radio** (`x-bt-radio`) — Radio button with label
- **Toggle** (`x-bt-toggle`) — Toggle/switch with label and description
- **FileInput** (`x-bt-file-input`) — Button-style file picker (looks like an input)
- **FileDropzone** (`x-bt-file-dropzone`) — Drag-and-drop upload area with preview, progress, validation
- **Datetime** (`x-bt-datetime`) — Date and date+time picker (Flatpickr-based)
- **TimePicker** (`x-bt-time-picker`) — Time-only picker with interval support
- **Slider** (`x-bt-slider`) — Range slider with min/max/step
- **Lookup** (`x-bt-lookup`) — Autocomplete text input with dropdown suggestions
- **ChatInput** (`x-bt-chat-input`) — Message input with file attachment and submit button
- **Tag** (`x-bt-tag`) — Removable tag/chip component

### Button Components
- **Button** (`x-bt-button`) — Action button with variants (solid/outline/ghost/link), sizes, colors, loading spinner
- **ButtonIcon** (`x-bt-button-icon`) — Icon-only button (compact, for toolbars/tables)
- **Fab** (`x-bt-fab`) — Floating action button (fixed position)

### Display Components
- **Alert** (`x-bt-alert`) — Inline alert messages (success/warning/error/info)
- **Badge** (`x-bt-badge`) — Small status label
- **Avatar** (`x-bt-avatar`) — User avatar with image or initials fallback
- **Card** (`x-bt-card`) — Content container with optional header/footer
- **Skeleton** (`x-bt-skeleton`) — Loading placeholder animation
- **Toast** (`x-bt-toast`) — Auto-dismissing notification

### Overlay Components
- **Modal** (`x-bt-modal`) — Slot-based popup for custom content (wire:model controlled)
- **Dialog** (`x-bt-dialog`) — Event-driven programmatic alerts/confirms (HasDialogs trait)
- **Dropdown** (`x-bt-dropdown`) — Click-triggered dropdown menu
- **Tooltip** (`x-bt-tooltip`) — Hover tooltip
- **CommandPalette** (`x-bt-command-palette`) — Keyboard-driven command/search interface

### Layout Components
- **Table** (`x-bt-table`) — Styled data table with header/body slots
- **Nav** (`x-bt-nav`) — Navigation tabs/pills
- **Icon** (`x-bt-icon`) — Icon renderer (Heroicons + custom sets)
- **ToggleTheme** (`x-bt-toggle-theme`) — Dark/light mode toggle

---

## Dialog vs Modal — Critical Distinction

### Dialog (event-driven, programmatic)
```php
// In your Livewire component — use HasDialogs trait
use Beartropy\Ui\Traits\HasDialogs;

class MyComponent extends Component
{
    use HasDialogs;

    public function confirmDelete()
    {
        $this->dialog()->confirm(
            'Delete this item?',
            'This action cannot be undone.',
            [
                'accept' => ['label' => 'Delete', 'method' => 'delete'],
                'reject' => ['label' => 'Cancel'],
            ]
        );
    }

    public function delete()
    {
        // perform deletion...
        $this->dialog()->success('Item deleted successfully.');
    }
}
```

```blade
{{-- Just place one <x-bt-dialog /> in your layout — no slots needed --}}
<x-bt-dialog />
```

### Modal (slot-based, wire:model)
```blade
<x-bt-modal wire:model="showEditForm">
    <x-slot:title>Edit User</x-slot:title>

    <div class="space-y-4">
        <x-bt-input wire:model="name" label="Name" />
        <x-bt-input wire:model="email" label="Email" type="email" />
    </div>

    <x-slot:footer>
        <x-bt-button wire:click="save" primary>Save</x-bt-button>
        <x-bt-button wire:click="$set('showEditForm', false)" outline>Cancel</x-bt-button>
    </x-slot:footer>
</x-bt-modal>
```

---

## File Upload — Choosing the Right Component

### file-input (compact, inline)
```blade
<x-bt-file-input
    wire:model="avatar"
    label="Profile Photo"
    accept="image/*"
    hint="JPG or PNG, max 1MB"
/>
```
Use for: profile avatars, single document uploads, any case where a compact input-style trigger is preferred.

### file-dropzone (full upload zone)
```blade
<x-bt-file-dropzone
    wire:model="documents"
    label="Project Documents"
    accept=".pdf,.doc,.docx"
    :max-file-size="10485760"
    :max-files="5"
    preview
    help="Max 10MB per file, up to 5 files"
/>
```
Use for: gallery uploads, document management, any case needing drag-drop, previews, progress bars, or multi-file management.

Both require Livewire's `WithFileUploads` trait in the PHP component.

---

## Integration Tips

### With Livewire
- Use `wire:model` for two-way binding
- Use `wire:model.live` for real-time updates
- Use `wire:model.live.debounce.300ms` for debounced updates
- Components automatically show validation errors from `$errors`

### With Alpine.js
- Components use Alpine.js internally
- You can add Alpine directives: `x-on:click`, `x-show`, etc.
- Access component state through Alpine's `$wire` magic

### Validation
- Validation errors from Livewire display automatically
- Use `customError` prop to override or show custom errors
- Combine with Laravel validation rules in Livewire components

## Best Practices

1. **Use the intent-mapping table above** to select the right component for the job
2. **Leverage magic attributes** for cleaner code — `primary`, `lg`, `outline` as bare attributes
3. **Provide helpful `hint` text** to guide users
4. **Use appropriate sizes** — `xs` through `xl` via magic attributes
5. **Be consistent with colors** — Use a color scheme throughout your app
6. **Show loading states** — The `spinner` prop is enabled by default on inputs and buttons
7. **Combine components** — Use slots to compose complex UIs

When helping users with components, always provide working examples and explain the props clearly.
