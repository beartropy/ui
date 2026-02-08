# Time Picker

A time selection input with a wheel-style dropdown, 12/24-hour format, seconds support, min/max range, minute intervals, and color presets.

## Basic Usage

```blade
<x-bt-time-picker label="Meeting Time" wire:model="time" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Label text above the input |
| `placeholder` | `string\|null` | `null` | Placeholder text (defaults to "Select time...") |
| `value` | `mixed` | `null` | Initial value in `HH:mm` or `HH:mm:ss` format |
| `format` | `string` | `'H:i'` | Time format. `H:i` = 24-hour, `h:i A` = 12-hour with AM/PM |
| `color` | `string\|null` | config default | Color preset name |
| `id` | `string\|null` | auto-generated | Component id |
| `name` | `string\|null` | falls back to id | Input name for form submission |
| `min` | `string\|null` | `null` | Minimum allowed time (`HH:mm` format) |
| `max` | `string\|null` | `null` | Maximum allowed time (`HH:mm` format) |
| `interval` | `int` | `1` | Minute step: `1`, `5`, `10`, `15`, `30`, `60` |
| `seconds` | `bool` | `false` | Show seconds wheel |
| `disabled` | `bool` | `false` | Disables interaction |
| `clearable` | `bool` | `true` | Show clear button when a value is selected |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `help` | `string\|null` | `null` | Help text below the input |
| `hint` | `string\|null` | `null` | Alias for `help` |

## Colors

```blade
{{-- Default color --}}
<x-bt-time-picker label="Default" />

{{-- Explicit color prop --}}
<x-bt-time-picker label="Blue" color="blue" />

{{-- Magic attribute shorthand --}}
<x-bt-time-picker label="Rose" rose />

{{-- Dynamic color --}}
<x-bt-time-picker :color="$hasError ? 'red' : 'primary'" label="Appointment" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## 12-Hour Format (AM/PM)

Use `format="h:i A"` for 12-hour mode with AM/PM toggle buttons. The stored value is always in 24-hour format.

```blade
<x-bt-time-picker label="12h Format" format="h:i A" wire:model="time" />
```

## Seconds

Enable the seconds wheel with `:seconds="true"`. The value format becomes `HH:mm:ss`.

```blade
<x-bt-time-picker label="Precise Time" :seconds="true" wire:model="time" />
```

Combine with 12-hour mode:

```blade
<x-bt-time-picker label="Full" format="h:i A" :seconds="true" value="02:30:15" />
```

## Min / Max Range

Restrict selectable times. Hours and minutes outside the range are disabled in the wheel.

```blade
<x-bt-time-picker label="Business Hours" min="08:00" max="17:30" />
```

## Interval

Only show minutes that are multiples of the interval.

```blade
{{-- 15-minute slots: 00, 15, 30, 45 --}}
<x-bt-time-picker label="Quarter Hour" :interval="15" />

{{-- 30-minute slots: 00, 30 --}}
<x-bt-time-picker label="Half Hour" :interval="30" />

{{-- Combine with range --}}
<x-bt-time-picker label="Appointments" :interval="30" min="09:00" max="16:00" />
```

## Disabled

```blade
<x-bt-time-picker label="Locked" :disabled="true" value="10:00" />
```

## Clearable

Clear button is shown by default when a value is selected. Disable with `:clearable="false"`.

```blade
<x-bt-time-picker label="Clearable" wire:model="time" />
<x-bt-time-picker label="Not Clearable" :clearable="false" value="09:00" />
```

## Validation Errors

### Custom Error

```blade
<x-bt-time-picker label="Check-in" custom-error="Please select a valid time." />
```

### Auto-Detection

Errors are automatically detected from the Laravel `$errors` bag using the `wire:model` name.

## Help Text

```blade
<x-bt-time-picker label="Time" help="Choose your preferred time." />
<x-bt-time-picker label="Time" hint="Format: HH:mm (24-hour)" />
```

## Initial Value

```blade
{{-- Vanilla form --}}
<x-bt-time-picker label="Start" value="14:30" name="start_time" />

{{-- Livewire --}}
<x-bt-time-picker label="Start" wire:model="startTime" />
```

## Livewire Integration

```blade
{{-- Deferred (default) --}}
<x-bt-time-picker label="Time" wire:model="time" />

{{-- Real-time --}}
<x-bt-time-picker label="Time" wire:model.live="time" />

{{-- On blur --}}
<x-bt-time-picker label="Time" wire:model.blur="time" />
```

## Interaction

The dropdown uses a **wheel-style** selector:

- **Click** the top/bottom values to scroll through options
- **Mouse wheel** on each column to scroll
- **Keyboard** up/down arrows when focused
- **Now** button sets the current time (rounded to the nearest interval)
- Values wrap around (23 → 00, 59 → 00)
- Disabled times (outside min/max range) are automatically skipped

## Full Example

```blade
<x-bt-time-picker
    label="Appointment"
    format="h:i A"
    :seconds="true"
    :interval="15"
    min="08:00"
    max="18:00"
    color="emerald"
    value="10:00"
    help="Business hours only, 15-minute slots."
    wire:model="appointmentTime"
/>
```

## Configuration

In `config/beartropyui.php`:

```php
'component_defaults' => [
    'time-picker' => [
        'color' => env('BEARTROPY_UI_TIME_PICKER_COLOR', 'beartropy'),
    ],
],
```

## Dark Mode

Dark mode is fully supported automatically via `dark:` Tailwind variants.
