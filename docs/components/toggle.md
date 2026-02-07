# Toggle

A toggle switch (checkbox alternative) with label support, colors, sizes, label positioning, validation errors, help text, and built-in autosave support.

## Basic Usage

```blade
<x-bt-toggle label="Enable notifications" />
<x-bt-toggle label="Dark mode" wire:model.live="darkMode" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string\|null` | `null` | Input name attribute |
| `label` | `string\|null` | `null` | Label text |
| `label-position` | `string\|null` | `'right'` | Label placement: `'top'`, `'bottom'`, `'left'`, `'right'` |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `disabled` | `bool` | `false` | Disabled state |
| `hint` | `string\|null` | `null` | Help text below the toggle |
| `help` | `string\|null` | `null` | Help text below the toggle (takes precedence over `hint`) |
| `autosave` | `bool` | `false` | Enable automatic server-side persistence |
| `autosave-method` | `string` | `'savePreference'` | Livewire method to call on toggle change |
| `autosave-key` | `string\|null` | wire:model value | Key passed to the autosave method |
| `autosave-debounce` | `int` | `300` | Debounce delay in milliseconds |

Standard HTML attributes (`id`, `checked`, `wire:model`, etc.) are forwarded to the native `<input>`.

## Label Content

The label can be set via the `label` prop or the default slot. The slot takes precedence:

```blade
{{-- Simple label --}}
<x-bt-toggle label="Enable feature" />

{{-- Rich label via slot --}}
<x-bt-toggle name="tos">
    I accept the <a href="/terms" class="underline text-blue-600">Terms of Service</a>
</x-bt-toggle>
```

## Label Position

```blade
<x-bt-toggle label="Right (default)" />
<x-bt-toggle label="Left side" label-position="left" />
<x-bt-toggle label="Top" label-position="top" />
<x-bt-toggle label="Bottom" label-position="bottom" />
```

## Colors

```blade
<x-bt-toggle label="Default" />              {{-- beartropy --}}
<x-bt-toggle primary label="Primary" />
<x-bt-toggle blue label="Blue" />
<x-bt-toggle red label="Red" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Colors affect the checked track background, hover state, focus ring, and active state. The thumb is always white.

## Sizes

```blade
<x-bt-toggle xs label="Extra Small" />
<x-bt-toggle sm label="Small" />
<x-bt-toggle    label="Medium (default)" />
<x-bt-toggle lg label="Large" />
<x-bt-toggle xl label="Extra Large" />
```

## Checked State

```blade
<x-bt-toggle label="Unchecked" />
<x-bt-toggle label="Checked" checked />
```

## Disabled

```blade
<x-bt-toggle label="Disabled off" :disabled="true" />
<x-bt-toggle label="Disabled on" :disabled="true" checked />
```

Disabled toggles get `opacity-60 cursor-not-allowed` styling.

## Validation Errors

```blade
{{-- Auto error from $errors bag --}}
<x-bt-toggle wire:model="setting" name="setting" label="Setting" />

{{-- Custom error --}}
<x-bt-toggle label="Accept" :custom-error="'You must accept the terms.'" />
```

When in error state, the track gets a red ring, the label turns red, and an error message appears below.

## Help Text

```blade
<x-bt-toggle label="Notifications" help="You will receive email notifications." />
<x-bt-toggle label="Public profile" hint="Your profile will be visible to everyone." />
```

`help` and `hint` are aliases; `help` takes precedence.

## Autosave

The toggle has built-in autosave support that calls a Livewire method when toggled:

```blade
{{-- Basic autosave --}}
<x-bt-toggle
    label="Dark mode"
    :autosave="true"
    wire:model="darkMode"
/>

{{-- Custom method and debounce --}}
<x-bt-toggle
    label="Notifications"
    :autosave="true"
    autosave-method="savePreference"
    :autosave-debounce="500"
    wire:model="notifications"
/>
```

When autosave is enabled:
- A spinner appears while saving
- A green checkmark appears on success
- A red X appears on error
- The toggle gets a dotted border during save, green border on success, red on error

The autosave calls `$wire.call(method, value, key)` where:
- `method` defaults to `'savePreference'`
- `value` is the boolean toggle state
- `key` defaults to the `wire:model` value, or can be set via `autosave-key`

## Livewire Integration

```blade
{{-- Deferred (default) --}}
<x-bt-toggle wire:model="enabled" label="Enabled" />

{{-- Live binding --}}
<x-bt-toggle wire:model.live="darkMode" label="Dark mode" />
```

When `wire:model` is present, the Alpine `checked` state is entangled with the Livewire property via `$wire.entangle()`.

## Configuration

```php
'component_defaults' => [
    'toggle' => [
        'color' => env('BEARTROPY_UI_TOGGLE_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_TOGGLE_SIZE', 'md'),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically. The unchecked track uses `dark:bg-gray-700`, labels adapt for dark themes.
