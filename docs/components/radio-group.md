# Radio Group

A wrapper component that renders multiple radio buttons from an options array, with shared name, color, size, layout, validation, and default selection.

## Basic Usage

```blade
<x-bt-radio-group
    name="plan"
    :options="[
        ['value' => 'free', 'label' => 'Free'],
        ['value' => 'pro', 'label' => 'Pro'],
        ['value' => 'enterprise', 'label' => 'Enterprise'],
    ]"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | `''` | Shared input name for all radios |
| `options` | `array` | `[]` | Array of `['value' => '...', 'label' => '...']` |
| `value` | `mixed` | `null` | Default selected value (pre-checks matching radio) |
| `color` | `string\|null` | config default | Color applied to all radios |
| `size` | `string\|null` | `'md'` | Size applied to all radios |
| `inline` | `bool` | `false` | Horizontal layout (`flex gap-4 flex-wrap`) |
| `disabled` | `bool` | `false` | Disables all radios in the group |
| `required` | `bool` | `false` | Shows red asterisk on label |
| `label` | `string\|null` | `null` | Group label text above the options |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `help` | `string\|null` | `null` | Help text below the group |
| `hint` | `string\|null` | `null` | Alias for `help` |

## Options Format

Each option needs `value` and `label`:

```php
$options = [
    ['value' => 'sm', 'label' => 'Small'],
    ['value' => 'md', 'label' => 'Medium'],
    ['value' => 'lg', 'label' => 'Large'],
];
```

## Layout

```blade
{{-- Vertical (default) --}}
<x-bt-radio-group name="plan" :options="$options" />

{{-- Inline --}}
<x-bt-radio-group name="plan" :inline="true" :options="$options" />
```

## Default Selection

```blade
<x-bt-radio-group name="plan" value="pro" :options="$options" />
```

The radio matching `value` will be pre-checked.

## Group Label

```blade
<x-bt-radio-group name="plan" label="Choose a plan" :options="$options" />

{{-- With required asterisk --}}
<x-bt-radio-group name="plan" label="Plan" :required="true" :options="$options" />
```

## Colors

```blade
<x-bt-radio-group name="plan" color="blue" :options="$options" />
<x-bt-radio-group name="plan" color="red" :options="$options" />
```

The color is applied uniformly to all child radio buttons.

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Sizes

```blade
<x-bt-radio-group name="plan" size="sm" :options="$options" />
<x-bt-radio-group name="plan" size="lg" :options="$options" />
```

## Disabled

```blade
<x-bt-radio-group name="plan" :disabled="true" :options="$options" />
```

All child radios inherit the disabled state.

## Validation Errors

```blade
{{-- Auto error from $errors bag --}}
<x-bt-radio-group wire:model="plan" name="plan" :options="$options" />

{{-- Custom error --}}
<x-bt-radio-group name="plan" :custom-error="'Please select a plan.'" :options="$options" />
```

Individual radio field-help is suppressed in grouped mode — only the group shows the error.

## Help Text

```blade
<x-bt-radio-group name="plan" help="Choose a plan that fits your needs." :options="$options" />
<x-bt-radio-group name="plan" hint="Required field." :options="$options" />
```

`help` and `hint` are aliases; `help` takes precedence.

## Livewire Integration

```blade
<x-bt-radio-group name="plan" :options="$options" wire:model="plan" />

{{-- Live binding --}}
<x-bt-radio-group name="plan" :options="$options" wire:model.live="plan" />
```

The `wire:model` is forwarded to each child radio.

## Configuration

The RadioGroup uses the same config as the Radio component:

```php
'component_defaults' => [
    'radio' => [
        'color' => env('BEARTROPY_UI_RADIO_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_RADIO_SIZE', 'md'),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically, inherited from the Radio component presets.
