# Checkbox

A checkbox input with label support, colors, sizes, label positioning, validation errors, and help text.

## Basic Usage

```blade
<x-bt-checkbox label="I agree to the terms" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Input ID attribute |
| `name` | `string\|null` | `null` | Input name attribute |
| `value` | `mixed` | `null` | Input value attribute |
| `checked` | `bool` | `false` | Checked state |
| `disabled` | `bool` | `false` | Disabled state |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `label` | `string\|null` | `null` | Label text |
| `label-position` | `string` | `'right'` | Label placement: `'left'` or `'right'` |
| `help` | `string\|null` | `null` | Help text below the checkbox |
| `hint` | `string\|null` | `null` | Alias for `help` |

## Label Content

The label can be set via the `label` prop or the default slot. The slot takes precedence:

```blade
{{-- Simple label --}}
<x-bt-checkbox label="Accept terms" />

{{-- Rich label via slot --}}
<x-bt-checkbox name="tos">
    I accept the <a href="/terms" class="underline text-blue-600">Terms of Service</a>
</x-bt-checkbox>
```

## Label Position

```blade
<x-bt-checkbox label="Label on the right (default)" />
<x-bt-checkbox label="Label on the left" label-position="left" />
```

## Colors

```blade
<x-bt-checkbox label="Default" />              {{-- beartropy --}}
<x-bt-checkbox primary label="Primary" />
<x-bt-checkbox blue label="Blue" />
<x-bt-checkbox red label="Red" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Colors affect the border, checked background, hover state, focus ring, active state, and label color (for some variants).

## Sizes

```blade
<x-bt-checkbox xs label="Extra Small" />
<x-bt-checkbox sm label="Small" />
<x-bt-checkbox    label="Medium (default)" />
<x-bt-checkbox lg label="Large" />
<x-bt-checkbox xl label="Extra Large" />
```

Sizes `xs`, `sm`, `md` use `rounded-sm`; sizes `lg`, `xl` use `rounded-md`.

## Checked State

```blade
<x-bt-checkbox label="Unchecked" />
<x-bt-checkbox label="Checked" :checked="true" />
```

## Disabled

```blade
<x-bt-checkbox label="Disabled" :disabled="true" />
<x-bt-checkbox label="Disabled checked" :disabled="true" :checked="true" />
```

Disabled checkboxes get `opacity-60 cursor-not-allowed` styling.

## With Value

```blade
<x-bt-checkbox name="role" value="admin" label="Admin" />
<x-bt-checkbox name="role" value="editor" label="Editor" />
```

## Validation Errors

Errors are automatically detected from the Laravel validation error bag:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-checkbox wire:model="accepted" label="Accept terms" />

{{-- Custom error --}}
<x-bt-checkbox label="Accept" :custom-error="'You must accept the terms.'" />
```

When in error state, the border turns red, the label turns red, and an error message appears below.

## Help Text

```blade
<x-bt-checkbox label="Notifications" help="You will receive email notifications." />
<x-bt-checkbox label="Public profile" hint="Your profile will be visible to everyone." />
```

`help` and `hint` are aliases; `help` takes precedence.

## Livewire Integration

```blade
{{-- Deferred (default) --}}
<x-bt-checkbox wire:model="accepted" label="Accept" />

{{-- Live binding --}}
<x-bt-checkbox wire:model.live="newsletter" label="Newsletter" />
```

## Custom Attributes

Extra attributes are forwarded to the native `<input>` element:

```blade
<x-bt-checkbox label="Custom" data-testid="my-checkbox" aria-label="Accept" />
```

`class` and `style` are applied to the outer wrapper `<div>`, all other attributes go to the `<input>`.

## Configuration

```php
'component_defaults' => [
    'checkbox' => [
        'color' => env('BEARTROPY_UI_CHECKBOX_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_CHECKBOX_SIZE', 'md'),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically. Borders use `dark:border-{color}-600`, checkmark uses `dark:text-neutral-900`, labels adapt for dark themes.
