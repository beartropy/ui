# x-bt-datetime — AI Reference

## Component Tag
```blade
<x-bt-datetime />
```

## Architecture
- `Datetime` → extends `BeartropyComponent`
- Renders: `datetime.blade.php` → uses `base/input-trigger-base.blade.php` (trigger) + `base/dropdown-base.blade.php` (dropdown)
- Presets: `resources/views/presets/datetime.php` (calendar + wheel colors per color key)
- Input presets: also loads `input` preset for label/trigger styling
- JS: `resources/js/modules/datetime-picker.js` → registered as `Alpine.data('beartropyDatetimepicker', ...)`
- i18n: `lang/en/ui.php` keys: `select_date`, `select_range`, `clear`, `change_date`, `hour`, `minute_short`, `now`, `today`, `day_mon`–`day_sun`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto-generated | `id="my-picker"` |
| name | `?string` | falls back to id | `name="start_date"` |
| label | `?string` | `null` | `label="Date"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| value | `mixed` | `null` | `value="2024-06-15"` |
| min | `?string` | `null` | `min="2024-01-01"` |
| max | `?string` | `null` | `max="2024-12-31"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| readonly | `bool` | `false` | `:readonly="true"` |
| placeholder | `?string` | `null` | `placeholder="Pick..."` |
| hint | `?string` | `null` | `hint="Hint text"` |
| help | `?string` | `null` | `help="Help text"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| range | `bool` | `false` | `:range="true"` |
| format | `string` | `'Y-m-d'` | `format="Y-m-d"` |
| formatDisplay | `?string` | auto | `format-display="{d}/{m}/{Y}"` |
| showTime | `bool` | `false` | `:show-time="true"` |
| clearable | `bool` | `true` | `:clearable="false"` |

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (inherited from input-trigger-base, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

### Fill / Outline
`fill` — applies tinted background to trigger. `outline` — default border-only mode.

## Alpine Component: `beartropyDatetimepicker(cfg)`

### Config (from PHP → JS)
```js
{
    value,          // initial value or @entangle for Livewire
    range,          // bool
    min,            // "YYYY-MM-DD" or ""
    max,            // "YYYY-MM-DD" or ""
    formatDisplay,  // "{d}/{m}/{Y}" JS format string
    showTime,       // bool
    disabled,       // bool
    i18n: { now, today, changeDate },
}
```

### State
- `value` — the stored date/datetime string or range object `{ start, end }`
- `open` — dropdown visibility
- `start`, `end` — normalized date strings (`YYYY-MM-DD`)
- `startHour`, `startMinute`, `endHour`, `endMinute` — padded strings ("00"-"23", "00"-"59")
- `startTimeSet`, `endTimeSet` — whether time has been explicitly set
- `panel` — current UI panel: `'date-start'`, `'date-end'`, `'time-start'`, `'time-end'`
- `month`, `year` — current calendar view
- `days` — array of day objects `{ label, date, inMonth }`
- `hovered` — date string hovered during range selection
- `displayLabel` — formatted text shown in the trigger

### Calendar Methods
- `updateCalendar()` — rebuild days array for current month/year
- `selectDay(day)` — handle day click (single/range/time flow)
- `prevMonth()`, `nextMonth()` — navigate months
- `isDisabled(day)` — check min/max/inMonth
- `isSelected(day)` — check if day is start or end
- `isInRange(day)` — check if day is between start and end (range mode)
- `isToday(day)` — check if day is today's date
- `goToToday()` — scroll calendar to current month
- `showCalendarPane()` — returns `true` when calendar grid should be visible

### Time Wheel Methods
- `currentTimeType()` — returns `'start'` or `'end'` based on panel
- `isPickingStartTime()` — returns `true` when panel is `'time-start'`
- `isPickingEndTime()` — returns `true` when panel is `'time-end'`
- `getHourForType(type)`, `getMinuteForType(type)` — get current value
- `getAdjacentHour(type, offset)`, `getAdjacentMinute(type, offset)` — get prev/next values for wheel
- `moveHour(type, direction)`, `moveMinute(type, direction)` — step ±1, wrap around
- `wheelHour(type, event)`, `wheelMinute(type, event)` — mouse wheel handlers
- `setTimeNow(type)` — set current time + auto-advance
- `setTime(type, h, m, autoAdvance)` — set time + optionally advance panel/close

### Utility Methods
- `setFromValue()` — parse `value` into internal state (start/end/hours/minutes)
- `updateDisplay()` — rebuild `displayLabel` from current state
- `normalizeDate(str)` — parse various date formats to `YYYY-MM-DD`
- `formatForDisplay(dateStr, format, hour, minute)` — format date for trigger display
- `clearSelection()` — reset all state
- `onDropdownClose()` — commit value on close
- `setInitialPanel()` — determine starting panel based on state

## Dropdown Structure

### Calendar View
```
     ←    2024-06        →        ← month navigation
  Mon Tue Wed Thu Fri Sat Sun     ← weekday headers
                        1   2     ← day grid (out-of-month = disabled)
    3   4   5   6   7   8   9
   10  11 [12]  13  14  15  16    ← selected day highlighted
   17  18  19  20  21  22  23
   24  25  26  27  28  29  30
              Today               ← scroll-to-today button
```

### Time Wheel View (when show-time enabled)
```
  2024-06-12               Change date    ← header with current date
      HOUR    :    MIN                    ← column labels
       09          28                     ← adjacent (gray, clickable)
      [10]    :   [29]                    ← selected (accent color, highlight bg)
       11          30                     ← adjacent (gray, clickable)
                Now                       ← quick-set button
```

## Preset Structure (datetime.php)

```php
'colors' => [
    'beartropy' => [
        // Shared (same across all colors)
        'dropdown_bg'      => 'bg-white dark:bg-gray-900',
        'dropdown_shadow'  => 'shadow-xl',
        'header_text'      => 'text-lg font-semibold ...',
        'weekday_text'     => 'text-neutral-500 ...',
        'grid_bg'          => 'bg-transparent',
        'option_text'      => 'text-neutral-800 ...',
        'trigger_text'     => 'text-neutral-800 ...',
        'placeholder_text' => 'text-neutral-400 ...',
        'column_label'     => 'text-[11px] font-semibold uppercase ...',
        'wheel_adjacent'   => 'text-gray-300 dark:text-gray-600 hover:...',

        // Color-specific
        'dropdown_border'  => 'border border-gray-300 dark:border-gray-600',
        'option_hover'     => 'hover:bg-beartropy-50 ...',
        'option_active'    => 'font-bold bg-beartropy-100 ...',
        'option_selected'  => 'bg-beartropy-200 ...',
        'option_range'     => 'bg-beartropy-100 dark:bg-beartropy-800/50',
        'today_ring'       => 'ring-2 ring-beartropy-400 dark:ring-beartropy-500',
        'select'           => 'bg-white ... focus:ring-2 focus:ring-beartropy-500 ...',
        'wheel_selected'   => 'text-beartropy-600 dark:text-beartropy-400',
        'wheel_highlight'  => 'bg-beartropy-50 dark:bg-beartropy-950/30',
        'now_button'       => 'text-xs ... text-beartropy-500 ...',
    ],
]
```

### Preset Keys Used By dropdown-base (via preset-for="datetime")
- `dropdown_border` — border classes
- `dropdown_bg` — background
- `dropdown_shadow` — shadow

### Preset Keys Used In Blade Template
- `header_text` — month/year header
- `weekday_text` — day-of-week row
- `grid_bg` — calendar grid background
- `option_text` — day text color
- `option_hover` — day hover state
- `option_active` — selected day highlight
- `option_range` — range highlight
- `today_ring` — today's date ring
- `column_label` — "HOUR", "MIN" labels
- `wheel_selected` — text color for selected time value
- `wheel_highlight` — background of highlight band
- `wheel_adjacent` — text color for prev/next time values
- `now_button` — "Now" / "Today" button styles

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

### Basic date
```blade
<x-bt-datetime label="Date" wire:model="date" />
```

### Date + time
```blade
<x-bt-datetime label="Appointment" :show-time="true" wire:model="appointment" />
```

### Date range
```blade
<x-bt-datetime label="Trip" :range="true" wire:model="tripDates" />
```

### Date range + time
```blade
<x-bt-datetime label="Booking" :range="true" :show-time="true" wire:model="booking" />
```

### Constrained dates
```blade
<x-bt-datetime label="This Year" min="2024-01-01" max="2024-12-31" wire:model="date" />
```

### Vanilla form
```blade
<x-bt-datetime label="Date" value="2024-06-15" name="event_date" />
```

### Color + validation
```blade
<x-bt-datetime label="Date" emerald custom-error="Invalid date" wire:model="date" />
```

## Config Defaults

```php
// config/beartropyui.php
'component_defaults' => [
    'datetime' => [
        'color' => env('BEARTROPY_UI_DATETIME_COLOR', 'beartropy'),
    ],
],
```

## Key Notes
- The `id` auto-generates via `'beartropy-datetime-' . uniqid()` when not provided
- The `name` falls back to `id` when not provided
- `primary` is an alias for `beartropy` in the preset
- All color class names are hardcoded (not dynamically constructed) for Tailwind v4 compatibility
- The time wheel design matches `x-bt-time-picker` for visual consistency
- The component uses `input-trigger-base` for the trigger and `dropdown-base` for the dropdown
- Calendar weekday headers use i18n: `__('beartropy-ui::ui.day_mon')` through `__('beartropy-ui::ui.day_sun')`
- Click-outside closing is handled by `dropdown-base` (NOT the root div — the dropdown teleports to `<body>`, so a root-level `@click.outside` would close the dropdown on every internal click)
- Clear button uses `<button type="button">` (not bare `<span>`)
- Disabled state prevents opening via `if (!disabled)` guards on click handlers
- Hidden input only renders when no `wire:model` is present (`@unless($hasWireModel)`)
