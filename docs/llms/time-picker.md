# x-bt-time-picker — AI Reference

## Component Tag
```blade
<x-bt-time-picker />
```

## Architecture
- `TimePicker` → extends `BeartropyComponent`
- Renders: `time-picker.blade.php` → uses `base/input-trigger-base.blade.php` (trigger) + `base/dropdown-base.blade.php` (dropdown)
- Presets: `resources/views/presets/time-picker.php` (wheel colors per color key)
- Input presets: also loads `input` preset for label/trigger styling
- JS: `resources/js/modules/time-picker.js` → registered as `Alpine.data('beartropyTimepicker', ...)`
- i18n: `lang/en/ui.php` keys: `hour`, `minute_short`, `second_short`, `select_time`, `now`, `clear`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto-generated | `id="my-picker"` |
| name | `?string` | falls back to id | `name="start_time"` |
| label | `?string` | `null` | `label="Time"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| value | `mixed` | `null` | `value="14:30"` |
| min | `?string` | `null` | `min="08:00"` |
| max | `?string` | `null` | `max="17:30"` |
| interval | `int` | `1` | `:interval="15"` |
| format | `string` | `'H:i'` | `format="h:i A"` |
| seconds | `bool` | `false` | `:seconds="true"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| readonly | `bool` | `false` | `:readonly="true"` |
| placeholder | `?string` | `null` | `placeholder="Pick..."` |
| clearable | `bool` | `true` | `:clearable="false"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (inherited from input-trigger-base, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

### Fill / Outline
`fill` — applies tinted background to trigger. `outline` — default border-only mode.

## Format Detection

12-hour mode is auto-detected from the `format` prop via regex `/[hgGA]/`:
- `H:i` → 24h (default)
- `h:i A` → 12h with AM/PM buttons
- Any format containing `h`, `g`, `G`, or `A` → 12h

Regardless of display format, the stored value is always 24-hour: `HH:mm` or `HH:mm:ss`.

## Alpine Component: `beartropyTimepicker(cfg)`

### Config (from PHP → JS)
```js
{
    value,          // initial value or @entangle for Livewire
    is12h,          // bool — derived from format
    showSeconds,    // bool — $seconds prop
    min,            // "HH:mm" or null
    max,            // "HH:mm" or null
    interval,       // int — minute step
    disabled,       // bool
    i18n: { now },  // translated strings
}
```

### State
- `value` — the stored time string (`HH:mm` or `HH:mm:ss`, 24h always)
- `open` — dropdown visibility
- `hour`, `minute`, `second` — padded strings ("00"-"23", "00"-"59")
- `period` — `'AM'` or `'PM'` (only meaningful in 12h mode)
- `displayLabel` — formatted text shown in the trigger

### Key Methods
- `selectHour(h)`, `selectMinute(m)`, `selectSecond(s)` — set value + update
- `togglePeriod(p)` — switch AM/PM
- `moveHour(dir)`, `moveMinute(dir)`, `moveSecond(dir)` — step ±1, skip disabled, wrap
- `getAdjacentHour(offset)`, `getAdjacentMinute(offset)`, `getAdjacentSecond(offset)` — get prev/next values for wheel display
- `wheelHour(event)`, `wheelMinute(event)`, `wheelSecond(event)` — mouse wheel handlers
- `isHourDisabled(h)`, `isMinuteDisabled(m)` — min/max range enforcement
- `getHours()`, `getMinutes()`, `getSeconds()` — generate value arrays
- `clear()` — null everything, close dropdown
- `setNow()` — current time rounded to interval
- `_to24h(h, period)` — 12h → 24h conversion

## Dropdown Structure (Wheel Design)

The dropdown uses a wheel-style selector showing 3 values per column (previous, selected, next):

```
   HOUR    :    MIN        ← column labels
    11          28         ← adjacent (gray, clickable)
   [12]    :   [29]        ← selected (accent color, highlight bg)
    13          30         ← adjacent (gray, clickable)
          Now              ← quick-set button
```

With seconds + 12h:
```
   HOUR    :    MIN    :    SEC    AM
    11          28          44     PM
   [12]    :   [29]   :   [45]
    13          30          46
               Now
```

### Wheel Interaction
- Mouse wheel: `@wheel.prevent` on each column
- Click adjacent values: steps ±1
- Keyboard: up/down arrows when column focused
- Values wrap around continuously
- Disabled values (outside min/max) are skipped

## Preset Structure (time-picker.php)

```php
'colors' => [
    'beartropy' => [
        // Shared (same across all colors)
        'dropdown_bg'     => 'bg-white dark:bg-gray-900',
        'dropdown_shadow' => 'shadow-xl',
        'option_text'     => 'text-gray-800 dark:text-gray-100',
        'column_label'    => 'text-[11px] font-semibold uppercase ...',
        'wheel_adjacent'  => 'text-gray-300 dark:text-gray-600 hover:...',
        'ampm_button'     => 'px-3 py-1.5 rounded-lg text-xs font-bold ...',

        // Color-specific
        'dropdown_border' => 'border border-gray-200 dark:border-gray-700',
        'wheel_selected'  => 'text-beartropy-600 dark:text-beartropy-400',
        'wheel_highlight' => 'bg-beartropy-50 dark:bg-beartropy-950/30',
        'ampm_active'     => 'bg-beartropy-500 text-white shadow-sm',
        'now_button'      => 'text-xs ... text-beartropy-500 ...',
    ],
]
```

### Preset Keys Used By dropdown-base (via preset-for="time-picker")
- `dropdown_border` — border classes
- `dropdown_bg` — background
- `dropdown_shadow` — shadow

### Preset Keys Used In Blade Template
- `column_label` — "HOUR", "MIN", "SEC" labels
- `wheel_selected` — text color for center (selected) value
- `wheel_highlight` — background of highlight band behind selected value
- `wheel_adjacent` — text color for prev/next values
- `ampm_button` — base AM/PM button styles
- `ampm_active` — active AM/PM button color
- `now_button` — "Now" button styles
- `option_text` — trigger display text color

## Error State Detection
- Auto-reads from Laravel `$errors` bag using `wire:model` name
- Override with `:custom-error="$message"` prop
- Error state: red border, red label via `input` preset

## Hidden Input
When no `wire:model` is present, a hidden `<input>` is rendered for form submission:
```html
<input type="hidden" name="..." :value="value">
```

## Common Patterns

### Basic
```blade
<x-bt-time-picker label="Time" wire:model="time" />
```

### 12h with AM/PM
```blade
<x-bt-time-picker label="Time" format="h:i A" wire:model="time" />
```

### Business hours with 15-min slots
```blade
<x-bt-time-picker label="Slot" :interval="15" min="09:00" max="17:00" wire:model="slot" />
```

### Full precision
```blade
<x-bt-time-picker label="Precise" :seconds="true" format="h:i A" wire:model="time" />
```

### Vanilla form
```blade
<x-bt-time-picker label="Start" value="14:30" name="start_time" />
```

### Color + validation
```blade
<x-bt-time-picker label="Check-in" emerald custom-error="Invalid time" wire:model="checkin" />
```

## Config Defaults

```php
// config/beartropyui.php
'component_defaults' => [
    'time-picker' => [
        'color' => env('BEARTROPY_UI_TIME_PICKER_COLOR', 'beartropy'),
    ],
],
```

## Key Notes
- The `id` auto-generates via `'beartropy-timepicker-' . uniqid()` when not provided
- The `name` falls back to `id` when not provided
- Stored value is always 24h format regardless of `format` prop
- `primary` is an alias for `beartropy` in the preset
- All color class names are hardcoded (not dynamically constructed) for Tailwind v4 compatibility
- The component uses `input-trigger-base` for the trigger and `dropdown-base` for the dropdown, inheriting their sizing/styling
- Column labels use i18n: `__('beartropy-ui::ui.hour')`, `__('beartropy-ui::ui.minute_short')`, `__('beartropy-ui::ui.second_short')`
