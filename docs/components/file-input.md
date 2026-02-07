# File Input

A styled file input with upload state indicators, clearable button, Livewire upload integration, and customizable slots.

## Basic Usage

```blade
<x-bt-file-input label="Upload File" wire:model="file" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Input ID attribute |
| `name` | `string\|null` | falls back to `id` | Input name attribute |
| `multiple` | `bool` | `false` | Allow multiple file selection |
| `accept` | `string\|null` | `null` | Accepted file types (e.g., `image/*`, `.pdf,.doc`) |
| `placeholder` | `string\|null` | `'Choose file'` | Placeholder text when no file selected |
| `clearable` | `bool` | `true` | Show clear button after file selection |
| `disabled` | `bool` | `false` | Disabled state |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `label` | `string\|null` | `null` | Label text above the input |
| `help` | `string\|null` | `null` | Help text below the input (takes precedence over `hint`) |
| `hint` | `string\|null` | `null` | Help text below the input |

Standard HTML attributes (`wire:model`, `data-*`, etc.) are forwarded to the native file input.

## Custom Placeholder

```blade
<x-bt-file-input name="resume" placeholder="Drop your resume here..." />
```

## Accept Types

```blade
<x-bt-file-input name="image" accept="image/*" label="Images Only" />
<x-bt-file-input name="pdf" accept=".pdf" label="PDF Documents" />
<x-bt-file-input name="docs" accept=".pdf,.doc,.docx" label="Office Files" />
```

## Multiple Files

```blade
<x-bt-file-input name="files" :multiple="true" label="Upload Documents" />
```

When multiple files are selected, the label shows the count (e.g., "3 files selected").

## Clearable

```blade
{{-- Clearable (default) --}}
<x-bt-file-input name="file" :clearable="true" />

{{-- No clear button --}}
<x-bt-file-input name="file" :clearable="false" />
```

The clear button appears after a file is selected and resets the input.

## Disabled

```blade
<x-bt-file-input name="file" :disabled="true" label="Disabled" />
```

## Validation Errors

```blade
{{-- Auto error from $errors bag --}}
<x-bt-file-input wire:model="file" name="file" label="Upload" />

{{-- Custom error --}}
<x-bt-file-input name="file" :custom-error="'Please upload a file.'" />
```

When in error state, the input border turns red and an error icon appears. The error message is shown below the input.

## Help Text

```blade
<x-bt-file-input name="file" help="Max 10MB. PDF only." />
<x-bt-file-input name="file" hint="Recommended: 1200x630px." />
```

`help` and `hint` are aliases; `help` takes precedence.

## Livewire Upload Integration

When used with `wire:model`, the component listens for Livewire upload events and shows visual indicators:

```blade
<x-bt-file-input label="Upload Avatar" accept="image/*" wire:model="avatar" />
```

Upload states:
- **Uploading**: Spinner icon appears
- **Uploaded**: Green checkmark appears
- **Error**: Red X icon appears (upload failure or server-side validation)

The spinner is automatically cleared when the server reports a validation error, preventing it from getting stuck.

## Slots

| Slot | Description |
|------|-------------|
| `start` | Prepend content (default: paperclip icon) |
| `button` | Trigger area content (default: label + upload icon) |
| `end` | Append content (default: spinner/status indicators) |

```blade
<x-bt-file-input name="file" label="Custom End">
    <x-slot:end>
        <span class="inline-flex items-center px-2 text-xs text-gray-500">PDF</span>
    </x-slot:end>
</x-bt-file-input>
```

## Keyboard Accessibility

The trigger area supports:
- `Enter` / `Space` to open the file picker
- `role="button"` and `tabindex="0"` for screen readers

## Configuration

The file input uses the `input` component config for colors/sizes:

```php
'component_defaults' => [
    'input' => [
        'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
        'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
    ],
],
```

## Dark Mode

Dark mode styles are applied automatically via the input preset. Background, borders, label text, and icons all adapt.
