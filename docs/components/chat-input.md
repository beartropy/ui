# Chat Input

A specialized textarea for chat applications with auto-resize, single-line/stacked layout switching, submit-on-Enter, and Livewire integration.

## Basic Usage

```blade
<x-bt-chat-input wire:model="message" />
```

## With Label

```blade
<x-bt-chat-input wire:model="message" label="Message" />
```

## Placeholder

Defaults to the localized "Type a message..." text. Override with the `placeholder` prop:

```blade
<x-bt-chat-input wire:model="message" placeholder="Ask anything..." />
```

## Submit on Enter

By default, pressing Enter submits the form. Shift+Enter adds a newline. Disable with:

```blade
<x-bt-chat-input wire:model="message" :submitOnEnter="false" />
```

## Livewire Action

Fire a specific Livewire action on submit instead of form submission:

```blade
<x-bt-chat-input wire:model="message" action="sendMessage" />
```

## Stacked Layout

In stacked mode, tools and actions wrap below the textarea instead of being inline:

```blade
<x-bt-chat-input wire:model="message" :stacked="true" />
```

## With Tools Slot

Add tool buttons to the left side:

```blade
<x-bt-chat-input wire:model="message">
    <x-slot:tools>
        <x-bt-button-icon icon="paper-clip" ghost />
    </x-slot:tools>
</x-bt-chat-input>
```

## With Actions/Footer Slot

Add action buttons (submit, etc.):

```blade
<x-bt-chat-input wire:model="message">
    <x-slot:actions>
        <x-bt-button-icon icon="paper-airplane" solid beartropy />
    </x-slot:actions>
</x-bt-chat-input>
```

## Max Length

```blade
<x-bt-chat-input wire:model="message" :maxLength="500" />
```

## Border

Add a visible border:

```blade
<x-bt-chat-input wire:model="message" :border="true" />
```

## Colors

```blade
<x-bt-chat-input wire:model="message" color="blue" />
<x-bt-chat-input wire:model="message" blue />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| id | `?string` | auto-generated | Component ID |
| name | `?string` | same as id | Input name |
| label | `?string` | `null` | Label text |
| color | `?string` | `null` | Color preset |
| placeholder | `string` | `'Type a message...'` (localized) | Placeholder text |
| disabled | `bool` | `false` | Disabled state |
| readonly | `bool` | `false` | Readonly state |
| required | `bool` | `false` | Required state |
| help | `?string` | `null` | Helper text |
| hint | `?string` | `null` | Hint text |
| customError | `mixed` | `null` | Custom error message |
| maxLength | `?int` | `null` | Max character length |
| stacked | `bool` | `false` | Stacked layout mode |
| submitOnEnter | `bool` | `true` | Submit on Enter key |
| action | `?string` | `null` | Livewire action on submit |
| border | `bool` | `false` | Show border |

## Slots

| Slot | Description |
|------|-------------|
| tools | Left-side tool buttons |
| footer / actions | Action buttons area (aliases) |
