# Radio

A radio button input with label support, colors, sizes, label positioning, validation errors, and help text.

## Basic Usage

```blade
<x-bt-radio name="plan" value="free" label="Free Plan" />
<x-bt-radio name="plan" value="pro" label="Pro Plan" />
<x-bt-radio name="plan" value="enterprise" label="Enterprise Plan" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Label text |
| `label-position` | `string\|null` | `null` | Label placement: `'left'` or `'right'` (default) |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `disabled` | `bool` | `false` | Disabled state |
| `grouped` | `bool` | `false` | Whether it belongs to a RadioGroup (suppresses individual field-help) |
| `help` | `string\|null` | `null` | Help text below the radio |
| `hint` | `string\|null` | `null` | Alias for `help` |

Standard HTML attributes (`name`, `value`, `checked`, `required`, `id`, `wire:model`, etc.) are forwarded to the native `<input>`.

## Label Content

The label can be set via the `label` prop or the default slot. The slot takes precedence:

```blade
{{-- Simple label --}}
<x-bt-radio name="opt" value="1" label="Option 1" />

{{-- Rich label via slot --}}
<x-bt-radio name="tos" value="yes">
    I accept the <a href="/terms" class="underline text-blue-600">Terms of Service</a>
</x-bt-radio>
```

## Label Position

```blade
<x-bt-radio name="demo" value="1" label="Right (default)" />
<x-bt-radio name="demo" value="1" label="Left side" label-position="left" />
```

## Colors

```blade
<x-bt-radio name="r" value="1" label="Default" />         {{-- beartropy --}}
<x-bt-radio primary name="r" value="1" label="Primary" />
<x-bt-radio blue name="r" value="1" label="Blue" />
<x-bt-radio red name="r" value="1" label="Red" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Colors affect the border, checked background, hover state, focus ring, active state, inner dot, and label color.

## Sizes

```blade
<x-bt-radio xs name="r" value="1" label="Extra Small" />
<x-bt-radio sm name="r" value="1" label="Small" />
<x-bt-radio    name="r" value="1" label="Medium (default)" />
<x-bt-radio lg name="r" value="1" label="Large" />
<x-bt-radio xl name="r" value="1" label="Extra Large" />
```

## Disabled

```blade
<x-bt-radio name="r" value="1" label="Disabled" :disabled="true" />
<x-bt-radio name="r" value="1" label="Disabled checked" :disabled="true" checked />
```

## Validation Errors

```blade
{{-- Auto error from $errors bag --}}
<x-bt-radio wire:model="choice" name="choice" value="1" label="Option" />

{{-- Custom error --}}
<x-bt-radio name="choice" value="1" :custom-error="'Please select an option.'" />
```

When in error state, the border turns red, the label turns red, and an error message appears below.

## Help Text

```blade
<x-bt-radio name="r" value="1" label="Option" help="Extra context here." />
<x-bt-radio name="r" value="1" label="Option" hint="Alias for help." />
```

`help` and `hint` are aliases; `help` takes precedence. When `grouped` is true, field-help is suppressed (the group handles it).

## Livewire Integration

```blade
<x-bt-radio name="plan" value="free" label="Free" wire:model="plan" />
<x-bt-radio name="plan" value="pro" label="Pro" wire:model="plan" />

{{-- Live binding --}}
<x-bt-radio name="plan" value="free" label="Free" wire:model.live="plan" />
```

## Configuration

```php
'component_defaults' => [
    'radio' => [
        'color' => env('BEARTROPY_UI_RADIO_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_RADIO_SIZE', 'md'),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically. Borders use `dark:border-{color}-600`, the inner dot uses `dark:bg-black`, labels adapt for dark themes.
