# Tag

A tag input component that allows users to add and remove tags as chips. Supports separators, unique enforcement, max limits, paste handling, and form submission via hidden inputs.

## Basic Usage

```blade
<x-bt-tag label="Tags" name="tags" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Input element ID |
| `name` | `string\|null` | falls back to `id` | Form input name (used for hidden inputs) |
| `label` | `string\|null` | `null` | Label text above the input |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `placeholder` | `string` | i18n `'Add tag...'` | Placeholder text (shown when no tags exist) |
| `value` | `array` | `[]` | Initial tags |
| `separator` | `array\|string` | `','` | Character(s) used to split tags |
| `disabled` | `bool` | `false` | Disables input and tag removal |
| `unique` | `bool` | `true` | Prevents duplicate tags |
| `max-tags` | `int\|null` | `null` | Maximum number of tags allowed |
| `help` | `string\|null` | `null` | Help text below the input |
| `hint` | `string\|null` | `null` | Hint text below the input (takes precedence over `help`) |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |

## Colors

Two modes — **outline** (default) and **fill**:

```blade
{{-- Outline mode: transparent bg, colored ring on focus --}}
<x-bt-tag label="Default" />
<x-bt-tag label="Blue" blue />

{{-- Fill mode: tinted background matching the color --}}
<x-bt-tag fill label="Default Fill" />
<x-bt-tag fill blue label="Blue Fill" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Dynamic color:

```blade
<x-bt-tag :color="$hasError ? 'red' : 'primary'" label="Tags" />
```

## Sizes

Chip size scales automatically with the input size — smaller chips for `xs`/`sm`, larger for `lg`/`xl`:

```blade
<x-bt-tag xs label="Extra Small" />
<x-bt-tag sm label="Small" />
<x-bt-tag md label="Medium (default)" />
<x-bt-tag lg label="Large" />
<x-bt-tag xl label="Extra Large" />
```

## Initial Values

```blade
<x-bt-tag label="Skills" :value="['PHP', 'Laravel', 'Vue']" />
```

## Separator

By default, tags are split on commas. You can change the separator:

```blade
{{-- Single separator --}}
<x-bt-tag label="Tags" separator=";" />

{{-- Multiple separators (string — each character is a separator) --}}
<x-bt-tag label="Tags" separator=",; " />

{{-- Multiple separators (array) --}}
<x-bt-tag label="Tags" :separator="[',', ';', ' ']" />
```

## Unique & Max Tags

```blade
{{-- Allow duplicate tags --}}
<x-bt-tag label="Tags" :unique="false" />

{{-- Limit to 5 tags --}}
<x-bt-tag label="Tags" :max-tags="5" />
```

## Slots

### Start Slot

```blade
<x-bt-tag label="Tags">
    <x-slot:start>
        <x-bt-icon name="tag" class="w-5 h-5 text-gray-400 ml-2" />
    </x-slot:start>
</x-bt-tag>
```

### End Slot

Slot content is vertically centered and chrome-stripped automatically, matching the Input component behavior. Use `clearAll()` from the Alpine component to clear all tags:

```blade
<x-bt-tag label="Tags" :value="['one', 'two']">
    <x-slot:end>
        <x-bt-button color="gray" soft @click.stop="clearAll()">Clear All</x-bt-button>
    </x-slot:end>
</x-bt-tag>
```

## Livewire Integration

```blade
{{-- Deferred (default) --}}
<x-bt-tag wire:model="tags" label="Tags" />

{{-- Real-time --}}
<x-bt-tag wire:model.live="tags" label="Tags" />
```

When `wire:model` is present, the component uses `@entangle` for two-way binding and hidden inputs are not rendered (Livewire handles the data).

## Form Submission (Non-Livewire)

Without `wire:model`, the component renders hidden `<input>` elements (one per tag) using `name[]` syntax so tags are submitted as an array:

```blade
<form method="POST" action="/submit">
    @csrf
    <x-bt-tag name="tags" label="Tags" :value="$existingTags" />
    <x-bt-button type="submit">Save</x-bt-button>
</form>
```

On the server side, `$request->input('tags')` returns an array of tag strings.

## Validation Errors

Errors are automatically detected from the Laravel validation error bag using the `wire:model` name:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-tag wire:model="tags" label="Tags" />

{{-- Custom error message --}}
<x-bt-tag label="Tags" :custom-error="$tagError" />
```

## Help & Hint Text

```blade
<x-bt-tag label="Tags" help="Separate with commas" />
<x-bt-tag label="Tags" hint="Max 10 tags" />
```

When both are provided, `hint` takes precedence over `help`.

## Disabled

```blade
<x-bt-tag label="Tags" :disabled="true" :value="['locked']" />
```

Disabled state prevents adding, removing, and focusing the input.

## Keyboard & Paste Behavior

- **Enter** / **Tab**: Adds the current input as a tag
- **Backspace** (empty input): Removes the last tag
- **Paste**: If pasted text contains separators, it's split into multiple tags automatically

## Configuration

Tag shares the `input` preset for colors and sizes. Configure defaults via the input config:

```php
'component_defaults' => [
    'input' => [
        'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
        'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
        'outline' => env('BEARTROPY_UI_INPUT_OUTLINE', true),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically. Outline mode uses `bg-white dark:bg-gray-900`, fill mode uses color-tinted backgrounds with dark variants. Chips use color-appropriate dark backgrounds.
