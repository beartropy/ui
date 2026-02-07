# Textarea

A textarea input with optional auto-resize, character counter, copy-to-clipboard button, and validation error support.

## Basic Usage

```blade
<x-bt-textarea name="description" label="Description" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Label text above the textarea |
| `placeholder` | `string` | `''` | Placeholder text |
| `rows` | `int` | `4` | Number of visible text rows |
| `cols` | `int\|null` | `null` | Number of visible text columns |
| `name` | `string\|null` | `null` | Input name attribute |
| `id` | `string\|null` | `null` | Input ID (auto-generated if omitted) |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `disabled` | `bool` | `false` | Disabled state |
| `readonly` | `bool` | `false` | Readonly state |
| `required` | `bool` | `false` | Required flag (shows red asterisk on label) |
| `help` | `string\|null` | `null` | Help text below the field |
| `hint` | `string\|null` | `null` | Alias for `help` |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `auto-resize` | `bool` | `false` | Auto-grow height to fit content |
| `resize` | `string\|null` | `null` | CSS resize: `none`, `x`, `y`, `both` (default: `y` when auto-resize is off) |
| `show-counter` | `bool` | `true` | Show character count in bottom-right corner |
| `max-length` | `int\|null` | `null` | Max character length (enforced via `maxlength` attribute) |
| `show-copy-button` | `bool` | `true` | Show copy-to-clipboard button in top-right corner |

## Initial Value

Pass initial content via the default slot:

```blade
<x-bt-textarea name="bio">This is my bio text.</x-bt-textarea>
```

The component also respects `old()` values for form repopulation after validation failures.

## Auto-Resize

```blade
<x-bt-textarea name="notes" :auto-resize="true" placeholder="Grows as you type..." />
```

When enabled, the textarea grows vertically to fit its content. Manual resize is disabled (`resize-none`).

## Character Counter

The counter is shown by default. It displays the current character count, and when `max-length` is set, shows `count / max` with red styling when the limit is reached:

```blade
<x-bt-textarea name="bio" :max-length="500" />
```

Hide the counter:

```blade
<x-bt-textarea name="notes" :show-counter="false" />
```

## Copy Button

A clipboard button appears in the top-right corner by default. It copies the textarea content and shows a checkmark for 1.6 seconds:

```blade
{{-- Hide the copy button --}}
<x-bt-textarea name="notes" :show-copy-button="false" />
```

## Resize Control

```blade
<x-bt-textarea name="notes" resize="none" />   {{-- no resize --}}
<x-bt-textarea name="notes" resize="y" />       {{-- vertical only (default) --}}
<x-bt-textarea name="notes" resize="x" />       {{-- horizontal only --}}
<x-bt-textarea name="notes" resize="both" />    {{-- both directions --}}
```

When `auto-resize` is enabled, resize is forced to `none`.

## Colors

```blade
<x-bt-textarea name="t" />                  {{-- default: primary --}}
<x-bt-textarea name="t" color="beartropy" />
<x-bt-textarea name="t" blue />
<x-bt-textarea name="t" red />
```

All 24 colors: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Colors affect the border, focus ring, background (dark mode), text color, and label color.

## Sizes

```blade
<x-bt-textarea xs name="t" />
<x-bt-textarea sm name="t" />
<x-bt-textarea    name="t" />  {{-- md default --}}
<x-bt-textarea lg name="t" />
<x-bt-textarea xl name="t" />
```

## Validation Errors

Errors are automatically detected from the Laravel validation error bag:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-textarea wire:model="description" label="Description" />

{{-- Custom error --}}
<x-bt-textarea name="bio" :custom-error="'Bio is required'" />
```

## Help Text

```blade
<x-bt-textarea name="notes" help="Maximum 500 characters" />
<x-bt-textarea name="notes" hint="Required field" />
```

`help` and `hint` are aliases; `help` takes precedence.

## Livewire Integration

```blade
{{-- Basic binding --}}
<x-bt-textarea wire:model="description" label="Description" />

{{-- Live binding --}}
<x-bt-textarea wire:model.live="notes" label="Notes" />

{{-- Debounced --}}
<x-bt-textarea wire:model.debounce.500ms="content" label="Content" />
```

## Custom Attributes

Extra attributes are passed through to the native `<textarea>` element:

```blade
<x-bt-textarea name="code" spellcheck="false" autocomplete="off" data-test="editor" />
```

## Dark Mode

All colors include dark mode styles automatically. Backgrounds use `dark:bg-{color}-900/50`, text and labels adapt for dark themes.
