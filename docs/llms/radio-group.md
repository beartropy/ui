# x-bt-radio-group — AI Reference

## Component Tag
```blade
<x-bt-radio-group />
```

## Architecture
- `RadioGroup` → extends `BeartropyComponent`
- Renders: `radio-group.blade.php`
- Delegates to: `<x-beartropy-ui::radio>` for each option (with `:grouped="true"`)
- Presets: reuses `resources/views/presets/radio.php` (same as Radio)
- Sizes: global `resources/views/presets/sizes.php`
- Field help: uses `support/field-help.blade.php` for group-level error and help text
- **No Alpine JS** — pure server-rendered

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| name | `string` | `''` | `name="plan"` |
| options | `array` | `[]` | `:options="$options"` |
| color | `?string` | `null` | `color="blue"` |
| size | `?string` | `null` | `size="lg"` |
| inline | `bool` | `false` | `:inline="true"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| required | `bool` | `false` | `:required="true"` |
| value | `mixed` | `null` | `value="pro"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| label | `?string` | `null` | `label="Choose"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |

## Options Format
```php
[
    ['value' => 'free', 'label' => 'Free Plan'],
    ['value' => 'pro', 'label' => 'Pro Plan'],
]
```

Each option requires `value`; `label` defaults to `''` if missing.

## Layout Modes

| `inline` | Layout | CSS |
|----------|--------|-----|
| `false` (default) | Vertical stack | `flex flex-col gap-2` |
| `true` | Horizontal wrap | `flex gap-4 flex-wrap` |

## Default Selection

The `value` prop pre-checks the matching radio via `:checked="(string) $value === (string) $option['value']"`.

## What Gets Forwarded to Child Radios

Each `<x-beartropy-ui::radio>` receives:
- `name`, `value`, `label` — from the option
- `color`, `size` — from group props (defaults to `beartropy` / `md`)
- `:disabled` — from group prop
- `:grouped="true"` — suppresses individual field-help
- `:checked` — based on `$value` match
- `wire:model` — forwarded via `$attributes->whereStartsWith('wire:model')`

## Group Label

When `$label` is set, renders a `<span>` above the options with error-aware styling. When `$required` is true, adds a red asterisk.

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: group label turns red, error message shown via field-help below the group
4. Individual radio error display is suppressed (`:grouped="true"`)

## Common Patterns

```blade
{{-- Basic vertical --}}
<x-bt-radio-group name="plan" :options="$options" />

{{-- Inline --}}
<x-bt-radio-group name="plan" :inline="true" :options="$options" />

{{-- With default value --}}
<x-bt-radio-group name="plan" value="pro" :options="$options" />

{{-- With label --}}
<x-bt-radio-group name="plan" label="Choose a plan" :options="$options" />

{{-- Required --}}
<x-bt-radio-group name="plan" label="Plan" :required="true" :options="$options" />

{{-- Colored --}}
<x-bt-radio-group name="plan" color="blue" :options="$options" />

{{-- Sized --}}
<x-bt-radio-group name="plan" size="lg" :options="$options" />

{{-- Disabled --}}
<x-bt-radio-group name="plan" :disabled="true" :options="$options" />

{{-- With help text --}}
<x-bt-radio-group name="plan" help="Pick one." :options="$options" />

{{-- Custom error --}}
<x-bt-radio-group name="plan" :custom-error="'Required'" :options="$options" />

{{-- Livewire --}}
<x-bt-radio-group name="plan" :options="$options" wire:model="plan" />

{{-- Livewire live --}}
<x-bt-radio-group name="plan" :options="$options" wire:model.live="plan" />

{{-- Combined --}}
<x-bt-radio-group
    name="plan"
    label="Subscription"
    :required="true"
    :inline="true"
    color="blue"
    size="lg"
    value="pro"
    help="Choose wisely."
    :options="$options"
    wire:model.live="plan"
/>
```

## Config Defaults

Uses the Radio component config:
```php
'radio' => [
    'color' => env('BEARTROPY_UI_RADIO_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_RADIO_SIZE', 'md'),
],
```

## Key Notes
- RadioGroup has NO dedicated preset file — it reuses `radio.php` presets
- All `wire:model` variants are forwarded to child radios via `$attributes->whereStartsWith('wire:model')`
- `class` and `style` are applied to the inner options wrapper `<div>`, not the outer root
- `help` and `hint` are aliases; `help` takes precedence
- Individual radio field-help is suppressed when `:grouped="true"` — only the group shows errors/help
- The `value` prop comparison uses string casting: `(string) $value === (string) $option['value']`
- `primary` preset maps to beartropy colors in the radio preset file
