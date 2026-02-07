# x-bt-radio — AI Reference

## Component Tag
```blade
<x-bt-radio />
```

## Architecture
- `Radio` → extends `BeartropyComponent`
- Renders: `radio.blade.php`
- Presets: `resources/views/presets/radio.php` (24 color variants + primary)
- Sizes: global `resources/views/presets/sizes.php`
- Field help: uses `support/field-help.blade.php` for error and help text below field (suppressed when `$grouped` is true)
- **No Alpine JS** — pure server-rendered with CSS `peer-checked:` for state

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| labelPosition | `?string` | `null` | `label-position="left"` |
| size | `?string` | `null` | `size="lg"` or magic: `lg` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| label | `?string` | `null` | `label="Option"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| grouped | `bool` | `false` | `:grouped="true"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |

HTML attributes (`name`, `value`, `checked`, `required`, `id`, `wire:model`, etc.) are NOT constructor props — they pass through `$attributes` to the native `<input>`.

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Label content override (takes precedence over `label` prop). Supports rich HTML. |

## Preset Structure (radio.php)
```
colors → {color} → {
    checked, bg, border, border_error,
    hover, focus, focus_error,
    active, disabled, dot,
    label, label_error
}
```

- `checked` — `peer-checked:border-{color}-600 peer-checked:bg-{color}-600`
- `dot` — inner dot color: `bg-white dark:bg-black` (or color-tinted for some variants)
- `disabled` — `opacity-60 cursor-not-allowed`

## Visual Structure
- Outer `<div>` wraps `<label>` + field-help
- `<label>` contains the radio circle and label text
- Native `<input type="radio">` is `sr-only` (visually hidden but accessible)
- Visible radio is a `rounded-full` `<span>` with border/bg presets
- Inner dot is an absolutely positioned `rounded-full` `<span>` centered via `translate`, animated with `scale-0 peer-checked:scale-100`

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: border switches to `border_error`, focus to `focus_error`, label to `label_error`
4. Error message displayed via `field-help` component below the radio
5. When `grouped` is true, field-help is suppressed (the RadioGroup handles it)

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-radio name="plan" value="free" label="Free Plan" />

{{-- Multiple options --}}
<x-bt-radio name="plan" value="free" label="Free" wire:model="plan" />
<x-bt-radio name="plan" value="pro" label="Pro" wire:model="plan" />

{{-- Pre-checked --}}
<x-bt-radio name="plan" value="free" label="Free" checked />

{{-- Colored --}}
<x-bt-radio blue name="opt" value="1" label="Blue option" />

{{-- Sized --}}
<x-bt-radio lg name="opt" value="1" label="Large option" />

{{-- Label on left --}}
<x-bt-radio name="opt" value="1" label="Left label" label-position="left" />

{{-- Rich label via slot --}}
<x-bt-radio name="tos" value="yes">
    I accept the <a href="/terms">Terms</a>
</x-bt-radio>

{{-- Disabled --}}
<x-bt-radio name="opt" value="1" label="Locked" :disabled="true" />

{{-- With help text --}}
<x-bt-radio name="opt" value="1" label="Option" help="Extra context." />

{{-- Custom error --}}
<x-bt-radio name="opt" value="1" :custom-error="'Required'" />

{{-- Livewire --}}
<x-bt-radio name="plan" value="free" label="Free" wire:model.live="plan" />
```

## Config Defaults
```php
'radio' => [
    'color' => env('BEARTROPY_UI_RADIO_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_RADIO_SIZE', 'md'),
],
```

## Key Notes
- No Alpine JS — state is handled entirely through CSS `peer`/`peer-checked:` selectors
- The native `<input>` is `sr-only` — the visible radio is a styled `<span>` with `rounded-full`
- `name`, `value`, `checked`, `required`, `id` are NOT constructor props — they pass through `$attributes->merge()` to the native `<input>`
- `help` and `hint` are aliases; `help` takes precedence
- Label precedence: slot content > `label` prop (checked via `trim($slot) !== ''`)
- When `grouped` is true, field-help is hidden — the parent RadioGroup shows errors/help instead
- `primary` preset maps to beartropy colors
