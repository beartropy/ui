# Datetime

A date/time picker with calendar grid, optional time wheel selection, range mode, min/max constraints, and color presets.

## Basic Usage

```blade
<x-bt-datetime label="Date" wire:model="date" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Label text above the input |
| `placeholder` | `string\|null` | `null` | Placeholder text (defaults to "Select date..." or "Select range...") |
| `value` | `mixed` | `null` | Initial value (`YYYY-MM-DD` or `YYYY-MM-DD HH:mm`) |
| `color` | `string\|null` | config default | Color preset name |
| `id` | `string\|null` | auto-generated | Component id |
| `name` | `string\|null` | falls back to id | Input name for form submission |
| `min` | `string\|null` | `null` | Minimum date (`YYYY-MM-DD`) |
| `max` | `string\|null` | `null` | Maximum date (`YYYY-MM-DD`) |
| `range` | `bool` | `false` | Enable date range selection |
| `show-time` | `bool` | `false` | Enable time wheel after date selection |
| `format` | `string` | `'Y-m-d'` | Date format (PHP format) |
| `format-display` | `string\|null` | auto | Display format (JS: `{d}/{m}/{Y}`, with time: `{d}/{m}/{Y} {H}:{i}`) |
| `disabled` | `bool` | `false` | Disables interaction |
| `readonly` | `bool` | `false` | Readonly state |
| `clearable` | `bool` | `true` | Show clear button when a value is selected |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `help` | `string\|null` | `null` | Help text below the input |
| `hint` | `string\|null` | `null` | Alias for `help` |

## Colors

```blade
{{-- Default color --}}
<x-bt-datetime label="Default" />

{{-- Explicit color prop --}}
<x-bt-datetime label="Blue" color="blue" />

{{-- Magic attribute shorthand --}}
<x-bt-datetime label="Rose" rose />

{{-- Dynamic color --}}
<x-bt-datetime :color="$hasError ? 'red' : 'primary'" label="Appointment" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

## Sizes

Inherited from `input-trigger-base`. Default is `md`.

```blade
<x-bt-datetime label="Small" sm />
<x-bt-datetime label="Medium" />
<x-bt-datetime label="Large" lg />
```

Available sizes: `xs`, `sm`, `md`, `lg`, `xl`.

## Fill / Outline

```blade
{{-- Default: outline mode (border-only trigger) --}}
<x-bt-datetime label="Outline" />

{{-- Fill mode: tinted background on trigger --}}
<x-bt-datetime label="Filled" fill />
```

## Date Range

Enable range mode to select a start and end date. The value becomes an object `{ start, end }`.

```blade
<x-bt-datetime label="Trip Dates" :range="true" wire:model="dateRange" />
```

## Date + Time

Enable `show-time` to add a time wheel after date selection. The value includes time: `YYYY-MM-DD HH:mm`.

```blade
<x-bt-datetime label="Appointment" :show-time="true" wire:model="appointment" />
```

Combine with range for full datetime range:

```blade
<x-bt-datetime label="Booking" :range="true" :show-time="true" wire:model="booking" />
```

## Min / Max Dates

Restrict selectable dates. Days outside the range are disabled in the calendar.

```blade
<x-bt-datetime label="Date" min="2024-01-01" max="2024-12-31" />
```

## Disabled

```blade
<x-bt-datetime label="Locked" :disabled="true" value="2024-06-15" />
```

## Clearable

Clear button is shown by default when a value is selected. Disable with `:clearable="false"`.

```blade
<x-bt-datetime label="Clearable" wire:model="date" />
<x-bt-datetime label="Not Clearable" :clearable="false" value="2024-06-15" />
```

## Validation Errors

### Custom Error

```blade
<x-bt-datetime label="Date" custom-error="Please select a valid date." />
```

### Auto-Detection

Errors are automatically detected from the Laravel `$errors` bag using the `wire:model` name.

## Help Text

```blade
<x-bt-datetime label="Date" help="Choose a date within the allowed range." />
<x-bt-datetime label="Date" hint="Format: DD/MM/YYYY" />
```

## Custom Display Format

```blade
{{-- Default: {d}/{m}/{Y} --}}
<x-bt-datetime label="Date" />

{{-- Custom format --}}
<x-bt-datetime label="Date" format-display="{d}-{m}-{Y}" />

{{-- With month name --}}
<x-bt-datetime label="Date" format-display="{d} {M} {Y}" />

{{-- Full month name --}}
<x-bt-datetime label="Date" format-display="{d} {MMMM} {Y}" />
```

Available tokens: `{d}` (day), `{m}` (month), `{Y}` (year), `{M}` (short month name), `{MMMM}` (full month name), `{H}` (hour), `{i}` (minute).

## Livewire Integration

```blade
{{-- Deferred (default) --}}
<x-bt-datetime label="Date" wire:model="date" />

{{-- Real-time --}}
<x-bt-datetime label="Date" wire:model.live="date" />

{{-- On blur --}}
<x-bt-datetime label="Date" wire:model.blur="date" />
```

## Interaction

### Calendar

- **Month navigation** arrows to move between months
- **Today** button scrolls calendar to current month
- **Today ring** highlights today's date in the grid
- **Click** a day to select it
- Days outside min/max range are disabled
- In range mode, hover preview shows the potential range

### Time Wheel (when `show-time` is enabled)

- **Click** the top/bottom values to scroll through hours/minutes
- **Mouse wheel** on each column to scroll
- **Keyboard** up/down arrows when focused
- **Now** button sets the current time
- **Change date** button returns to the calendar
- Values wrap around (23 → 00, 59 → 00)

## Vanilla Form (no Livewire)

When no `wire:model` is present, a hidden `<input>` is rendered automatically for form submission.

```blade
<x-bt-datetime label="Event Date" name="event_date" value="2024-06-15" />
```

## Full Example

```blade
<x-bt-datetime
    label="Appointment"
    :show-time="true"
    min="2024-01-01"
    max="2024-12-31"
    color="emerald"
    value="2024-06-15 10:00"
    help="Select a date and time for your appointment."
    wire:model="appointmentDate"
/>
```

## Configuration

In `config/beartropyui.php`:

```php
'component_defaults' => [
    'datetime' => [
        'color' => env('BEARTROPY_UI_DATETIME_COLOR', 'beartropy'),
    ],
],
```

## Dark Mode

Dark mode is fully supported automatically via `dark:` Tailwind variants.
