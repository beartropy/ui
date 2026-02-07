# x-bt-checkbox — AI Reference

## Component Tag
```blade
<x-bt-checkbox />
```

## Architecture
- `Checkbox` → extends `BeartropyComponent`
- Renders: `checkbox.blade.php`
- Presets: `resources/views/presets/checkbox.php` (25 color variants)
- Sizes: global `resources/views/presets/sizes.php`
- Field help: uses `support/field-help.blade.php` for error and help text below field
- **No Alpine JS** — pure server-rendered with CSS `peer-checked:` for state

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto-generated | `id="my-checkbox"` |
| name | `?string` | `null` | `name="terms"` |
| value | `mixed` | `null` | `value="1"` |
| checked | `bool` | `false` | `:checked="true"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| size | `?string` | `null` | `size="lg"` or magic: `lg` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| label | `?string` | `null` | `label="Accept"` |
| labelPosition | `string` | `'right'` | `label-position="left"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Label content override (takes precedence over `label` prop). Supports rich HTML. |

## Attribute Forwarding
- `class` and `style` → outer wrapper `<div>` (merged with `flex flex-col min-h-full justify-center`)
- All other attributes → native `<input type="checkbox">` (merged with `peer sr-only`, `disabled`, `checked`, `value`)

## Preset Structure (checkbox.php)
```
colors → {color} → {
    checked, bg, border, border_error,
    hover, focus, focus_error,
    active, disabled,
    label, label_error
}
```

- `checked` — `peer-checked:border-{color}-600 peer-checked:bg-{color}-600`
- `border` / `border_error` — border width + color for normal/error states
- `hover` — border and bg change on hover
- `focus` / `focus_error` — ring for keyboard focus
- `active` — pressed state styling
- `disabled` — `opacity-60 cursor-not-allowed`
- `label` / `label_error` — label text color for normal/error states

## Rounded Corners
- Sizes `xs`, `sm`, `md` → `rounded-sm`
- Sizes `lg`, `xl` → `rounded-md`

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: border switches to `border_error`, focus to `focus_error`, label to `label_error`
4. Error message displayed via `field-help` component below the checkbox

## Checkmark SVG
- Inline SVG path: `M4 8l3 3 5-5`
- Uses CSS `peer-checked:scale-100` transition (from `scale-0`) for animation
- White on light (`text-white`), dark neutral on dark (`dark:text-neutral-900`)

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-checkbox label="Accept terms" />

{{-- With name and value --}}
<x-bt-checkbox name="role" value="admin" label="Admin" />

{{-- Pre-checked --}}
<x-bt-checkbox label="Checked" :checked="true" />

{{-- Colored --}}
<x-bt-checkbox blue label="Blue checkbox" />

{{-- Sized --}}
<x-bt-checkbox lg label="Large checkbox" />

{{-- Label on left --}}
<x-bt-checkbox label="Left label" label-position="left" />

{{-- Rich label via slot --}}
<x-bt-checkbox name="tos">
    I accept the <a href="/terms" class="underline">Terms</a>
</x-bt-checkbox>

{{-- Disabled --}}
<x-bt-checkbox label="Locked" :disabled="true" :checked="true" />

{{-- With help text --}}
<x-bt-checkbox label="Notifications" help="Email and push notifications." />

{{-- Custom error --}}
<x-bt-checkbox label="Accept" :custom-error="'Required'" />

{{-- Livewire --}}
<x-bt-checkbox wire:model="accepted" label="Accept" />

{{-- Livewire live --}}
<x-bt-checkbox wire:model.live="newsletter" label="Newsletter" />

{{-- Custom attributes --}}
<x-bt-checkbox label="Custom" data-testid="checkbox" aria-label="Accept" />
```

## Config Defaults
```php
'checkbox' => [
    'color' => env('BEARTROPY_UI_CHECKBOX_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_CHECKBOX_SIZE', 'md'),
],
```

## Key Notes
- No Alpine JS — state is handled entirely through CSS `peer`/`peer-checked:` selectors on the hidden `<input>`
- The native `<input>` is `sr-only` (visually hidden but accessible) — the visible checkbox is a styled `<span>`
- `id` auto-generates as `beartropy-checkbox-{uniqid}` if not provided
- `help` and `hint` are aliases; `help` takes precedence in the field-help component
- Label precedence: slot content > `label` prop (checked via `trim($slot) !== ''`)
- Extra attributes (`wire:model`, `data-*`, `aria-*`, etc.) are forwarded to the native `<input>` via `$attributes->except(['class', 'style'])`
- `primary` preset maps to beartropy colors — identical to `beartropy` preset
