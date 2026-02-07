# x-bt-file-input — AI Reference

## Component Tag
```blade
<x-bt-file-input />
```

## Architecture
- `FileInput` → extends `BeartropyComponent`
- Renders: `file-input.blade.php`
- Delegates to: `<x-beartropy-ui::base.input-trigger-base>` for the visible trigger
- Presets: reuses `resources/views/presets/input.php` for colors/sizes
- Field help: uses `support/field-help.blade.php` for error and help text
- **Uses Alpine JS** — reactive `files[]`, `uploading`, `uploaded`, `validationErrors` state
- **Livewire upload events** — listens for `livewire-upload-start`, `livewire-upload-finish`, `livewire-upload-error` on `.window`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto-generated | `id="my-file"` |
| name | `?string` | falls back to `$id` | `name="document"` |
| multiple | `bool` | `false` | `:multiple="true"` |
| accept | `?string` | `null` | `accept="image/*"` |
| placeholder | `?string` | `null` (defaults to `__('beartropy-ui::ui.choose_file')` in Blade) | `placeholder="Drop here..."` |
| clearable | `bool` | `true` | `:clearable="false"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| label | `?string` | `null` | `label="Upload"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |

HTML attributes (`wire:model`, `data-*`, etc.) are forwarded to the native `<input type="file">`.

## Magic Attributes

### Colors (mutually exclusive, default: `primary` via input config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| start | Prepend content (default: paperclip icon) |
| button | Trigger area content (default: label text + clear button + upload icon) |
| end | Append content (default: upload state indicators — spinner/check/error) |

## Alpine State

```js
{
    files: [],
    label: string,          // placeholder or selected file name(s)
    uploading: false,
    uploaded: false,
    validationErrors: false,
    onChange(e) { ... },     // updates files[], label, resets states
    clear() { ... },        // resets files[], label, input value
    openPicker() { ... },   // clicks hidden file input (respects disabled)
}
```

## Livewire Upload Events

Listens on `.window` with property matching to scope events to this specific input:

| Event | Action |
|-------|--------|
| `livewire-upload-start` | `uploading = true`, `uploaded = false` |
| `livewire-upload-finish` | `uploading = false`, `uploaded = true` |
| `livewire-upload-error` | `uploading = false`, `validationErrors = true` |

Property matching: `$event.detail?.property === wireModelValue` or starts with `wireModelValue.`

## x-effect Safety Net

```blade
x-effect="
    if ({{ $hasError ? 'true' : 'false' }}) {
        validationErrors = true;
        uploading = false;
    }
"
```

When the server re-renders with a validation error, this ensures `uploading` is forced to `false` — preventing the spinner from getting stuck if upload events didn't fire properly.

## Visual Structure
- Outer `<div>` with Alpine `x-data` + Livewire upload event listeners
- Optional `<label>` above the input
- Hidden `<input type="file">` (`sr-only`) — the actual file input
- `<x-beartropy-ui::base.input-trigger-base>` — the visible trigger with:
  - `start` slot: paperclip icon
  - `button` slot: label text (`x-text`), clear button (`x-show="files.length"`), upload tray icon
  - `end` slot: spinner (`x-show="uploading"`), check (`x-show="!uploading && uploaded"`), error X (`x-show="!uploading"` when `$hasError`)
- `<x-beartropy-ui::support.field-help>` below

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: input border turns red (via InputTriggerBase `$hasError`), error X icon shown, error message via field-help
4. `x-effect` forces `uploading = false` when server confirms error — prevents stuck spinner

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-file-input name="file" label="Upload" />

{{-- With placeholder --}}
<x-bt-file-input name="file" placeholder="Drop your file here..." />

{{-- Accept types --}}
<x-bt-file-input name="image" accept="image/*" label="Image" />
<x-bt-file-input name="pdf" accept=".pdf" label="PDF Only" />

{{-- Multiple --}}
<x-bt-file-input name="files" :multiple="true" label="Documents" />

{{-- Not clearable --}}
<x-bt-file-input name="file" :clearable="false" />

{{-- Disabled --}}
<x-bt-file-input name="file" :disabled="true" label="Locked" />

{{-- With help text --}}
<x-bt-file-input name="file" help="Max 10MB. PDF only." />

{{-- Custom error --}}
<x-bt-file-input name="file" :custom-error="'File is required.'" />

{{-- Livewire --}}
<x-bt-file-input wire:model="avatar" label="Avatar" accept="image/*" />

{{-- Custom end slot --}}
<x-bt-file-input name="file" label="Custom End">
    <x-slot:end>
        <span class="text-xs text-gray-500 px-2">PDF</span>
    </x-slot:end>
</x-bt-file-input>

{{-- Combined --}}
<x-bt-file-input
    label="Upload Documents"
    placeholder="Choose files..."
    :multiple="true"
    accept=".pdf,.doc,.docx"
    :clearable="true"
    help="Max 10MB per file."
    wire:model="documents"
/>
```

## Config Defaults

Uses the `input` component config:
```php
'input' => [
    'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
    'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
],
```

## Key Notes
- The native `<input type="file">` is `sr-only` — the visible trigger is an `InputTriggerBase` wrapper
- `id` auto-generates as `input-file-{uniqid}` if not provided; `name` falls back to `id`
- `help` and `hint` are aliases; `help` takes precedence in the field-help component
- The component reuses `input` presets for colors/sizes (no dedicated `file-input.php` preset)
- Livewire upload events listen on `.window` — property matching prevents cross-contamination with multiple file inputs on the same page
- The `x-effect` safety net ensures the spinner is cleared even if Livewire upload events are missed (network issues, DOM morphing)
- When `wire:model` is not present, upload state indicators still exist in Alpine but Livewire events won't trigger them
- The `clearable` button only appears when `files.length > 0` (after file selection)
- Multiple file selection shows count label: `"3 files selected"`
- `primary` is the default color (inherited from input config), not `beartropy`
