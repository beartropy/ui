# x-bt-toggle — AI Reference

## Component Tag
```blade
<x-bt-toggle />
```

## Architecture
- `Toggle` → extends `BeartropyComponent`
- Renders: `toggle.blade.php`
- Presets: `resources/views/presets/toggle.php` (25 color variants)
- Sizes: global `resources/views/presets/sizes.php` (toggle-specific keys: `trackHeight`, `trackWidth`, `thumb`, `thumbTranslate`, `thumbTop`, `thumbLeft`, `font`)
- Field help: uses `support/field-help.blade.php` for error and help text below toggle
- **Uses Alpine JS** — reactive `checked` state, `x-model` on hidden input, autosave logic with `triggerAutosave()`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| name | `?string` | `null` | `name="setting"` |
| label | `?string` | `null` | `label="Dark mode"` |
| labelPosition | `?string` | `'right'` | `label-position="left"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| size | `?string` | `null` | `size="lg"` or magic: `lg` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| hint | `?string` | `null` | `hint="Hint text"` |
| help | `?string` | `null` | `help="Help text"` |
| autosave | `bool` | `false` | `:autosave="true"` |
| autosaveMethod | `string` | `'savePreference'` | `autosave-method="customSave"` |
| autosaveKey | `?string` | `null` | `autosave-key="dark_mode"` |
| autosaveDebounce | `int` | `300` | `:autosave-debounce="500"` |

HTML attributes (`id`, `checked`, `wire:model`, etc.) are NOT constructor props — they pass through `$attributes` to the native `<input>`.

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Label content override (takes precedence over `label` prop). Supports rich HTML. |

## Label Position

| Position | Layout | CSS |
|----------|--------|-----|
| `top` | Vertical, label above | `flex flex-col gap-1` |
| `bottom` | Vertical, label below | `flex flex-col gap-1` |
| `left` | Horizontal, label left | `inline-flex items-center gap-2` |
| `right` (default) | Horizontal, label right | `inline-flex items-center gap-2` |

## Preset Structure (toggle.php)
```
colors → {color} → {
    checked, bg, border, border_error,
    thumb, hover, focus, focus_error,
    active, disabled,
    label, label_error
}
```

- `checked` — `peer-checked:bg-{color}-600` (track fill on check)
- `bg` — unchecked track: `bg-neutral-300 dark:bg-gray-700`
- `thumb` — `bg-white` (always white)
- `border_error` — `ring-2 ring-red-500`
- `disabled` — `opacity-60 cursor-not-allowed`

## Alpine State

```js
{
    autosave: bool,
    method: string,
    key: string|null,
    debounceMs: int,
    saving: false,
    saved: false,
    error: false,
    checked: bool | $wire.entangle(wireModel).live,
    triggerAutosave() { ... }
}
```

When `wire:model` is present, `checked` is entangled via `$wire.entangle()`. Otherwise, it reads from the `checked` HTML attribute.

## Autosave Behavior

When `autosave` is true and `@change` fires:
1. Sets `saving: true`, clears previous timeout
2. Debounces for `debounceMs` milliseconds
3. Calls `$wire.call(method, boolValue, key)` on the Livewire component
4. On success: `saving: false`, `saved: true`
5. On error: `saving: false`, `error: true`

Visual indicators (only rendered when `$autosave` is true):
- Spinner (`animate-spin`) while saving
- Green checkmark on success
- Red X on error
- Border transitions: dotted gray (saving), solid green (saved), solid red (error)

`autosaveKey` defaults to `$wireModelValue` if not explicitly provided.

## Error State

1. Checks `$errors` bag for field name or `wire:model` value
2. Falls back to `customError` prop
3. When error present: track gets red ring (`border_error`), label turns red (`label_error`), focus ring switches to red (`focus_error`)
4. Error message displayed via `field-help` component below the toggle

## Visual Structure
- Outer `<div>` wraps Alpine state + autosave border transitions
- Inner `<div>` contains label + toggle + autosave indicators
- Native `<input type="checkbox">` is `peer sr-only` (visually hidden but accessible)
- Track is a `rounded-full` `<span>` with color presets
- Thumb is an absolutely positioned `rounded-full` `<span>` with `peer-checked:translate-x-{N}` animation

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-toggle label="Enable feature" />

{{-- With name --}}
<x-bt-toggle name="dark_mode" label="Dark mode" />

{{-- Pre-checked --}}
<x-bt-toggle label="On by default" checked />

{{-- Colored --}}
<x-bt-toggle blue label="Blue toggle" />

{{-- Sized --}}
<x-bt-toggle lg label="Large toggle" />

{{-- Label positions --}}
<x-bt-toggle label="Left label" label-position="left" />
<x-bt-toggle label="Top label" label-position="top" />

{{-- Rich label via slot --}}
<x-bt-toggle name="tos">
    I accept the <a href="/terms">Terms</a>
</x-bt-toggle>

{{-- Disabled --}}
<x-bt-toggle label="Locked" :disabled="true" checked />

{{-- With help text --}}
<x-bt-toggle label="Notifications" help="Email and push notifications." />

{{-- Custom error --}}
<x-bt-toggle label="Accept" :custom-error="'Required'" />

{{-- Livewire --}}
<x-bt-toggle wire:model="enabled" label="Enabled" />

{{-- Livewire live --}}
<x-bt-toggle wire:model.live="darkMode" label="Dark mode" />

{{-- Autosave --}}
<x-bt-toggle label="Setting" :autosave="true" wire:model="setting" />

{{-- Autosave with custom method --}}
<x-bt-toggle
    label="Notifications"
    :autosave="true"
    autosave-method="savePreference"
    autosave-key="notifications"
    :autosave-debounce="500"
    wire:model="notifications"
/>
```

## Config Defaults
```php
'toggle' => [
    'color' => env('BEARTROPY_UI_TOGGLE_COLOR', 'beartropy'),
    'size' => env('BEARTROPY_UI_TOGGLE_SIZE', 'md'),
],
```

## Key Notes
- Uses Alpine JS (unlike Checkbox/Radio which are pure CSS) — `x-data`, `x-model`, `@change`
- The native `<input type="checkbox">` is `sr-only` — the visible toggle is styled `<span>` elements
- `id` auto-generates as `beartropy-toggle-{uniqid}` if not provided
- `help` and `hint` are aliases; `help` takes precedence in the field-help component
- Label precedence: slot content > `label` prop (checked via `trim($slot) !== ''`)
- `autosaveKey` falls back to `$wireModelValue` when not explicitly set — this is resolved after `$getWireModelState()` in the Blade template
- `primary` preset maps to beartropy colors — identical to `beartropy` preset
- Autosave indicators (spinner, check, X) are only rendered server-side when `$autosave` is true
- The outer wrapper has autosave border transitions via Alpine `:class` bindings even when autosave is false (border is transparent)
