# Dialog

A programmatic alert/confirm dialog controlled entirely via events. Place `<x-bt-dialog />` once per page — all state is managed by Alpine.js through events dispatched from Livewire (`HasDialogs` trait) or JavaScript (`dialog()` helper).

## Setup

Add the component once in your layout:

```blade
<x-bt-dialog />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `string\|null` | `md` | Default panel width (sm, md, lg, xl, 2xl). Per-dialog events can override. |

> **Size mapping:** Dialogs use one step wider than the named size for readability — `sm` → `max-w-md`, `md` → `max-w-lg`, etc.

## Livewire Usage (HasDialogs trait)

Add the trait to your Livewire component:

```php
use Beartropy\Ui\Traits\HasDialogs;

class MyComponent extends Component
{
    use HasDialogs;
}
```

### Simple Alerts

```php
// Success
$this->dialog()->success('Saved!', 'Your changes have been saved.');

// Info
$this->dialog()->info('Heads up', 'New version available.');

// Warning
$this->dialog()->warning('Careful', 'This may take a while.');

// Error
$this->dialog()->error('Failed', 'Could not save your changes.');
```

### Confirm Dialog

```php
$this->dialog()->confirm([
    'title'       => 'Are you sure?',
    'description' => 'This action cannot be undone.',
    'accept'      => [
        'label'  => 'Yes, do it',
        'method' => 'handleConfirm',
        'params' => [1],
    ],
    'reject' => [
        'label' => 'No, cancel',
    ],
]);
```

### Delete (Danger) Dialog

```php
$this->dialog()->delete(
    'Delete this item?',
    'This cannot be undone.',
    [
        'method' => 'destroyItem',
        'params' => [$itemId],
    ]
);
```

### Options

All alert methods (`success`, `info`, `warning`, `error`) accept a third `$options` array:

| Option | Description |
|--------|-------------|
| `size` | Override panel size for this dialog |
| `accept_label` | Custom accept button label |
| `accept_method` | Livewire method to call on accept |
| `accept_params` | Parameters for the accept method |
| `allowOutsideClick` | Allow closing by clicking the backdrop |
| `allowEscape` | Allow closing with Escape key |

## JavaScript Usage

```js
import { dialog } from 'beartropy-ui';

// Simple alerts
dialog.success('Saved!', 'Your changes have been saved.');
dialog.info('Note', 'Something happened.');
dialog.warning('Warning', 'Be careful.');
dialog.error('Error', 'Something went wrong.');

// Confirm
dialog.confirm({
    title: 'Are you sure?',
    description: 'This will take effect immediately.',
    accept: { label: 'Yes', method: 'doAction', params: [1] },
    reject: { label: 'Cancel' },
    componentId: 'livewire-component-id', // required for Livewire callbacks
});

// Delete
dialog.delete('Delete item?', 'Cannot be undone.', {
    method: 'destroyItem',
    params: [42],
    componentId: 'livewire-component-id',
});
```

## Dialog Types

| Type | Icon | Use Case |
|------|------|----------|
| `success` | check-circle | Operation completed |
| `info` | information-circle | Informational notice |
| `warning` | exclamation-triangle | Caution notice |
| `error` | x-circle | Error / failure |
| `confirm` | question-mark-circle | Requires user decision |
| `danger` | x-circle | Destructive action (red styling) |

## Sizes

| Size | Panel Width |
|------|-------------|
| `sm` | `max-w-md` |
| `md` | `max-w-lg` |
| `lg` | `max-w-xl` |
| `xl` | `max-w-2xl` |
| `2xl` | `max-w-3xl` |

## Accessibility

- `role="dialog"` and `aria-modal="true"` on the root element
- Focus trap (`x-trap.noscroll.inert`) prevents interaction outside the dialog
- Close button always visible for keyboard/mouse dismissal
- Escape key support via `allowEscape` option
