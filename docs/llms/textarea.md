# x-bt-textarea — AI Reference

## Component Tag
```blade
<x-bt-textarea />
```

## Architecture
- `Textarea` → extends `BeartropyComponent`
- Renders: `textarea.blade.php`
- Presets: `resources/views/presets/textarea.php` (24 color variants)
- Sizes: global `resources/views/presets/sizes.php`
- CSS: `resources/css/beartropy-ui.css` (`.beartropy-textarea` focus reset, `.beartropy-thin-scrollbar`)
- Field help: uses `support/field-help.blade.php` for error and help text below field
- **Alpine JS**: inline `x-data` in Blade (no separate JS module) — handles character count and copy-to-clipboard

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| label | `?string` | `null` | `label="Description"` |
| placeholder | `string` | `''` | `placeholder="Enter text..."` |
| rows | `int` | `4` | `:rows="8"` |
| cols | `?int` | `null` | `:cols="50"` |
| name | `?string` | `null` | `name="bio"` |
| id | `?string` | `null` | `id="my-textarea"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| size | `?string` | `null` | `size="lg"` or magic: `lg` |
| disabled | `bool` | `false` | `:disabled="true"` |
| readonly | `bool` | `false` | `:readonly="true"` |
| required | `bool` | `false` | `:required="true"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Help text"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| autoResize | `bool` | `false` | `:auto-resize="true"` |
| resize | `?string` | `null` | `resize="both"` |
| showCounter | `bool` | `true` | `:show-counter="false"` |
| maxLength | `?int` | `null` | `:max-length="500"` |
| showCopyButton | `bool` | `true` | `:show-copy-button="false"` |

## Magic Attributes

### Colors (mutually exclusive, default: `primary` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Initial textarea content/value |

## Alpine JS (inline x-data)

The component uses inline Alpine.js on the wrapper `<div>`:

### State
- `count` — current character count, initialized from `$refs.textarea.value.length`
- `copySuccess` — brief `true` flag after clipboard copy (resets after 1.6s)

### Methods
- `copy()` — writes `$refs.textarea.value` to clipboard via `navigator.clipboard.writeText()`

### Character Count Updates
- `x-init` on the wrapper reads initial count from `$refs.textarea`
- `x-on:input` on the `<textarea>` updates `count` on each keystroke
- When `autoResize` is enabled, the same `x-on:input` handler also adjusts `$el.style.height`

## Resize Behavior

The Blade template maps `resize` prop values to Tailwind v4 classes:

| Prop Value | Tailwind Class | Behavior |
|-----------|----------------|----------|
| `none` | `resize-none` | No resize |
| `x` | `resize-x` | Horizontal only |
| `y` | `resize-y` | Vertical only |
| `both` | `resize` | Both directions |
| (not set) | `resize-y` | Default: vertical only |
| (not set, autoResize=true) | `resize-none` | Disabled when auto-resize is on |

## Preset Structure (textarea.php)
```
colors → {color} → {
    main, border_default, border_error,
    label, label_error,
    input,
    help, error
}
```

- `main` — wrapper div styling: bg, text, border, rounded, shadow, focus-within ring
- `border_default` / `border_error` — border and ring colors for normal/error states
- `label` / `label_error` — label typography and color
- `input` — native textarea styling: `beartropy-textarea` class for focus reset, bg transparent, text color

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: border switches to `border_error` preset, label switches to `label_error`
4. Error message displayed via `field-help` component below the textarea

## Copy Button

- Positioned `absolute top-2 right-3` inside the wrapper
- Uses `x-tooltip.raw` for hover tooltip
- Shows clipboard icon → checkmark icon on copy (1.6s transition)
- `tabindex="-1"` to skip in tab order
- When copy button is visible, textarea gets `pr-12` padding to avoid overlap

## Counter

- Positioned `absolute bottom-3.5 right-3`
- Shows `count` alone, or `count / maxLength` when `maxLength` is set
- Text turns red (`text-red-500`) when count reaches `maxLength`
- Server-side fallback content via `$countBlade` for initial render before Alpine hydrates

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-textarea name="notes" label="Notes" placeholder="Enter notes..." />

{{-- With character limit --}}
<x-bt-textarea name="bio" label="Bio" :max-length="500" />

{{-- Auto-resize --}}
<x-bt-textarea name="content" :auto-resize="true" label="Content" />

{{-- Minimal (no copy, no counter) --}}
<x-bt-textarea name="data" :show-counter="false" :show-copy-button="false" />

{{-- Livewire --}}
<x-bt-textarea wire:model="description" label="Description" />

{{-- With help text --}}
<x-bt-textarea name="notes" label="Notes" help="Be concise" />

{{-- Custom error --}}
<x-bt-textarea name="bio" :custom-error="'Bio is required'" />

{{-- Colored --}}
<x-bt-textarea blue name="notes" label="Notes" />

{{-- Initial content via slot --}}
<x-bt-textarea name="bio" label="Bio">Default text here</x-bt-textarea>

{{-- Disabled --}}
<x-bt-textarea name="locked" :disabled="true" label="Locked" />
```

## Config Defaults
```php
'textarea' => [
    'color' => env('BEARTROPY_UI_TEXTAREA_COLOR', 'primary'),
    'size' => env('BEARTROPY_UI_TEXTAREA_SIZE', 'md'),
],
```

## Key Notes
- Alpine JS is inline (no separate module file) — state is simple enough that a dedicated module isn't needed
- `help` and `hint` are aliases; `help` takes precedence in the field-help component
- The `beartropy-textarea` CSS class only resets focus outline/shadow — all other styling comes from Tailwind classes in the preset
- Extra attributes (`wire:model`, `spellcheck`, `data-*`, etc.) are forwarded to the native `<textarea>` via `$attributes->merge()`
- Initial value uses `old($name, $slot)` — respects Laravel's form repopulation after validation failures
- `id` defaults to `$name` if set, otherwise `uniqid('textarea-')`
- `required` adds both the HTML attribute and a red asterisk on the label
