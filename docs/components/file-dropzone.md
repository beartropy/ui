# File Dropzone

A drag-and-drop file upload area with client-side validation, image previews, existing-file support, file type icons, and Livewire integration.

## Basic Usage

```blade
<x-bt-file-dropzone label="Upload Files" wire:model="files" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Component ID |
| `name` | `string\|null` | falls back to `id` | Input name attribute |
| `label` | `string\|null` | `null` | Label text above the dropzone |
| `color` | `string\|null` | `'beartropy'` | Color preset |
| `multiple` | `bool` | `true` | Allow multiple files |
| `accept` | `string\|null` | `null` | Accepted file types (e.g., `image/*`, `.pdf,.doc`) |
| `max-file-size` | `int\|null` | `null` | Max file size in bytes (client-side validation) |
| `max-files` | `int\|null` | `null` | Max number of files (client-side validation) |
| `placeholder` | `string\|null` | auto from `multiple` | Override empty-state text |
| `preview` | `bool` | `true` | Show image thumbnails |
| `clearable` | `bool` | `true` | Show remove/clear buttons |
| `disabled` | `bool` | `false` | Disabled state |
| `custom-error` | `mixed` | `null` | Custom error message |
| `help` | `string\|null` | `null` | Help text below the dropzone |
| `hint` | `string\|null` | `null` | Help text (alias for `help`) |
| `existing-files` | `array` | `[]` | Existing files `[{name, url, size?, type?}]` |

## Single File Mode

```blade
<x-bt-file-dropzone name="avatar" :multiple="false" accept="image/*" />
```

In single mode, selecting a new file replaces the existing one.

## Accept Types

```blade
<x-bt-file-dropzone name="images" accept="image/*" label="Images Only" />
<x-bt-file-dropzone name="docs" accept=".pdf,.doc,.docx" label="Documents" />
```

An auto-generated hint shows accepted types and size limits below the upload icon. For example, `accept=".pdf,.doc" :max-file-size="5242880"` produces the hint text `PDF, DOC — Max 5 MB`.

## Client-Side Validation

```blade
<x-bt-file-dropzone
    name="uploads"
    accept="image/*,.pdf"
    :max-file-size="5242880"
    :max-files="3"
/>
```

Files are validated before being added:
- **Type check**: Rejects files that don't match the `accept` attribute
- **Size check**: Rejects files exceeding `max-file-size` (bytes)
- **Count check**: Prevents adding more than `max-files` total files

Rejected files show inline error messages below the dropzone.

## Image Previews

When `preview` is enabled (default), image files show thumbnails. Non-image files show type-specific icons:
- Images → photo icon
- PDF → document-text icon
- Video → film icon
- Audio → musical-note icon
- Other → document icon

```blade
{{-- Disable previews --}}
<x-bt-file-dropzone name="docs" :preview="false" />
```

## Existing Files

Display previously uploaded files alongside new selections:

```blade
<x-bt-file-dropzone
    name="documents"
    :existing-files="[
        ['name' => 'report.pdf', 'url' => '/storage/report.pdf', 'size' => 1024000, 'type' => 'application/pdf'],
        ['name' => 'photo.jpg', 'url' => '/storage/photo.jpg', 'size' => 512000, 'type' => 'image/jpeg'],
    ]"
/>
```

Removing an existing file dispatches an `existing-file-removed` Alpine event with the file ID.

## Clearable

```blade
{{-- Clearable (default) --}}
<x-bt-file-dropzone name="file" :clearable="true" />

{{-- No clear/remove buttons --}}
<x-bt-file-dropzone name="file" :clearable="false" />
```

When clearable, individual files have a remove button and a "Clear all" button appears when multiple files are selected.

## Disabled

```blade
<x-bt-file-dropzone name="file" :disabled="true" />
```

## Validation Errors

```blade
{{-- Auto error from $errors bag --}}
<x-bt-file-dropzone wire:model="file" name="file" />

{{-- Custom error --}}
<x-bt-file-dropzone name="file" :custom-error="'Please upload a file.'" />
```

## Help Text

```blade
<x-bt-file-dropzone name="file" help="Max 10MB. PDF only." />
<x-bt-file-dropzone name="file" hint="Recommended: 1200x630px." />
```

## Livewire Upload Integration

When used with `wire:model`, the component shows upload progress:

```blade
<x-bt-file-dropzone label="Upload Avatar" accept="image/*" wire:model="avatar" />
```

Upload events are property-scoped — multiple dropzones on the same page don't cross-contaminate.

Upload states:
- **Uploading**: Progress bar appears on each file
- **Complete**: Green checkmark icon
- **Error**: Red X icon

## Color Presets

25 color presets: `beartropy` (default), `primary`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `slate`, `gray`, `zinc`, `neutral`, `stone`.

```blade
<x-bt-file-dropzone name="file" color="blue" />
<x-bt-file-dropzone name="file" red />
```

## Configuration

```php
'component_defaults' => [
    'file-dropzone' => [
        'color' => env('BEARTROPY_UI_FILE_DROPZONE_COLOR', 'beartropy'),
    ],
],
```

## Keyboard Accessibility

The dropzone supports:
- `Enter` / `Space` to open the file picker
- `role="button"` and `tabindex="0"` for screen readers

## Dark Mode

Dark mode styles are applied automatically via the color preset. Borders, backgrounds, text, and icons all adapt.
