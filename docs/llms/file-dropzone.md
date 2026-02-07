# x-bt-file-dropzone — AI Reference

## Component Tag
```blade
<x-bt-file-dropzone />
```

## Architecture
- `FileDropzone` → extends `BeartropyComponent` (no size support, color only)
- Renders: `file-dropzone.blade.php`
- Presets: `resources/views/presets/file-dropzone.php` — 24 colors + `primary` alias (flat, no variants)
- Field help: uses `support/field-help.blade.php` for error and help text
- **Uses Alpine JS** — registered as `beartropyFileDropzone` Alpine data component via `resources/js/modules/file-dropzone.js` (bundled into `beartropy-ui.js`)
- **Livewire upload events** — listens for `livewire-upload-start/finish/error/progress` on `.window`, property-scoped
- **PHP helpers**: `formatBytes(int): string`, `getAcceptHint(): string` — used in Blade for the auto-generated hint subtext

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto-generated | `id="my-dropzone"` |
| name | `?string` | falls back to `$id` | `name="files"` |
| label | `?string` | `null` | `label="Upload"` |
| color | `?string` | `'beartropy'` (via config) | `color="blue"` |
| multiple | `bool` | `true` | `:multiple="false"` |
| accept | `?string` | `null` | `accept="image/*"` |
| maxFileSize | `?int` | `null` | `:max-file-size="5242880"` |
| maxFiles | `?int` | `null` | `:max-files="3"` |
| placeholder | `?string` | `null` (auto from `multiple`) | `placeholder="Drop here"` |
| preview | `bool` | `true` | `:preview="false"` |
| clearable | `bool` | `true` | `:clearable="false"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |
| existingFiles | `array` | `[]` | `:existing-files="$files"` |

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy`)
`primary`, `beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `slate`, `gray`, `zinc`, `neutral`, `stone`

## Alpine State (beartropyFileDropzone)

```js
{
    files: [],              // [{file, id, status, progress, preview}]
    existingFiles: [],      // [{name, url, size, type, id}]
    dragging: false,
    uploading: false,
    progress: 0,
    errors: [],             // client-side validation error strings

    openPicker(),           // clicks hidden file input (respects disabled)
    addFiles(e),            // from drop/input change, validates, adds to files[]
    removeFile(id),         // revoke object URL, remove from files[], sync input
    removeExisting(id),     // remove from existingFiles[], dispatch event
    clearFiles(),           // revoke all URLs, clear files[], sync input
    formatSize(bytes),      // human-readable: B, KB, MB, GB
    getFileIcon(mimeType),  // maps MIME → heroicon name
}
```

## Client-Side Validation (in addFiles)

Performed before files are added to state:

| Check | Config Prop | Error Key |
|-------|-------------|-----------|
| File type | `accept` | `file_type_not_accepted` |
| File size | `maxFileSize` | `file_too_large` |
| File count | `maxFiles` | `max_files_exceeded` |

Error strings use `i18n` translations passed from PHP. Invalid files are rejected; valid files are added. In single mode (`multiple: false`), new selection replaces previous.

## Livewire Upload Events

Listens on `.window` with property matching to scope events:

| Event | Action |
|-------|--------|
| `livewire-upload-start` | `uploading = true`, `progress = 0` |
| `livewire-upload-finish` | `uploading = false`, files set to `complete` |
| `livewire-upload-error` | `uploading = false`, files set to `error` |
| `livewire-upload-progress` | `progress = event.detail.progress` |

Property matching: `$event.detail?.property === wireModelValue` or starts with `wireModelValue.`

## Visual Structure
- Outer `<div>` with `x-data="beartropyFileDropzone({...})"` + Livewire upload event listeners
- Optional `<label>` above the dropzone
- Hidden `<input type="file">` (`sr-only`)
- Dropzone area (click/drag/drop/keyboard accessible):
  - Empty state: upload icon, placeholder text, accept/size hint
  - File list: existing files + new files, each with icon/preview, name, size, progress bar, status icon, remove button
  - "Add more" button (multiple mode)
- Client-side validation errors
- "Clear all" button (when files.length > 1)
- `<x-beartropy-ui::support.field-help>` for server errors and help text

## File Item Display

Each file shows:
- **Image preview** (if `preview` enabled and file is image type) or **file type icon**
- File name (truncated)
- File size (human-readable)
- Upload progress bar (when uploading via Livewire)
- Status icon: green check (complete), red X (error)
- Remove button (if clearable)

File type icons: `photo` (image/*), `document-text` (PDF), `film` (video/*), `musical-note` (audio/*), `document` (default)

## Existing Files

Pass pre-uploaded files via `:existing-files`:
```blade
<x-bt-file-dropzone
    :existing-files="[
        ['name' => 'report.pdf', 'url' => '/storage/report.pdf', 'size' => 1024, 'type' => 'application/pdf'],
    ]"
/>
```

Removing dispatches `existing-file-removed` Alpine event.

## Preset Structure (per color)

```php
'dropzone'       => '...',  // base dropzone border/bg/rounded
'dropzone_hover' => '...',  // hover state
'dropzone_drag'  => '...',  // drag-over state (ring, bg change)
'dropzone_error' => '...',  // error border
'icon'           => '...',  // upload icon color
'text'           => '...',  // placeholder text
'subtext'        => '...',  // hint subtext
'label'          => '...',  // label text
'label_error'    => '...',  // label when error
'file_item'      => '...',  // file row bg/border
'file_name'      => '...',  // file name text
'file_size'      => '...',  // file size text
'progress'       => '...',  // progress bar fill
'progress_track' => '...',  // progress bar track
'remove'         => '...',  // remove button
'clear_all'      => '...',  // clear all button
'disabled'       => '...',  // disabled overlay
```

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-file-dropzone name="files" label="Upload" />

{{-- Single file --}}
<x-bt-file-dropzone name="avatar" :multiple="false" accept="image/*" />

{{-- With restrictions --}}
<x-bt-file-dropzone name="docs" accept=".pdf,.doc" :max-file-size="5242880" :max-files="3" />

{{-- Custom placeholder --}}
<x-bt-file-dropzone name="photos" placeholder="Drag your photos here!" />

{{-- No preview --}}
<x-bt-file-dropzone name="docs" :preview="false" />

{{-- Not clearable --}}
<x-bt-file-dropzone name="required" :clearable="false" />

{{-- Disabled --}}
<x-bt-file-dropzone name="locked" :disabled="true" />

{{-- Custom error --}}
<x-bt-file-dropzone name="file" :custom-error="'Upload failed.'" />

{{-- With help text --}}
<x-bt-file-dropzone name="file" help="Max 10MB per file." />

{{-- Livewire --}}
<x-bt-file-dropzone wire:model="documents" label="Documents" />

{{-- Color --}}
<x-bt-file-dropzone name="file" color="blue" />
<x-bt-file-dropzone name="file" green />

{{-- Existing files --}}
<x-bt-file-dropzone name="files" :existing-files="$existingFiles" />

{{-- Combined --}}
<x-bt-file-dropzone
    label="Upload Documents"
    accept=".pdf,.doc,.docx"
    :max-file-size="10485760"
    :max-files="5"
    help="Max 10MB per file."
    wire:model="documents"
    color="blue"
/>
```

## Config Defaults

```php
'file-dropzone' => [
    'color' => env('BEARTROPY_UI_FILE_DROPZONE_COLOR', 'beartropy'),
],
```

## Key Notes
- The native `<input type="file">` is `sr-only`; the visible area is a styled div with drag/drop/click/keyboard handlers
- `id` auto-generates as `beartropy-filedropzone-{uniqid}` if not provided; `name` falls back to `id`
- `name` attribute gets `[]` suffix automatically in multiple mode
- `help` and `hint` are aliases; `help` takes precedence in field-help
- Client-side validation happens in JS (`addFiles`) before files are added to state — errors are shown inline below the dropzone
- Livewire events are property-scoped — multiple dropzones on the same page don't cross-contaminate
- Object URLs (`URL.createObjectURL`) are properly revoked on remove/clear to prevent memory leaks
- `DataTransfer` API is used to sync the hidden input's `FileList` after add/remove (wrapped in try/catch for env compatibility)
- `primary` is an alias for `beartropy` in this component's preset
- When `preview` is `false`, the `x-if="f.preview"` image template is omitted at compile time (Blade `@if`)
- `accept` matching supports: file extensions (`.pdf`), MIME wildcards (`image/*`), exact MIME types (`application/pdf`)
- Existing file images only show as thumbnails when both `url` and `type` (starting with `image/`) are present; otherwise a generic document icon is shown
- The `@click.stop` on the file list prevents re-opening the picker when interacting with file items
