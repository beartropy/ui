# x-bt-select — AI Reference

## Component Tag
```blade
<x-bt-select />
```

## Architecture
- `Select` → extends `InputTriggerBase` → extends `BeartropyComponent`
- Renders: `select.blade.php` → delegates trigger to `base/input-trigger-base.blade.php`, dropdown to `base/dropdown-base.blade.php`
- Presets: `resources/views/presets/select.php` (dropdown-specific color classes)
- Input presets: also reads `resources/views/presets/input.php` (for trigger border, ring, label styling)
- Sizes: global `resources/views/presets/sizes.php`
- CSS: `resources/css/beartropy-ui.css` (slot chrome stripping, state borders, scrollbar)
- Field help: uses `support/field-help.blade.php` for error and help text below field
- **Alpine JS**: `resources/js/modules/select.js` exports `beartropySelect(cfg)`, registered as `Alpine.data('beartropySelect')` and `window.$beartropy.beartropySelect` in `resources/js/index.js`
- Blade passes a config object to `$beartropy.beartropySelect({...})` containing all server-side values; JS handles all runtime logic including `init()`, watchers, and Livewire/vanilla branching

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| options | untyped | `null` | `:options="$array"` |
| selected | untyped | `null` | `:selected="$val"` |
| icon | `?string` | `null` | `icon="user"` |
| placeholder | untyped | `__('beartropy-ui::ui.select')` | `placeholder="Pick..."` |
| searchable | `bool` | `true` | `:searchable="false"` |
| label | untyped | `null` | `label="Category"` |
| multiple | `bool` | `false` | `:multiple="true"` |
| clearable | `bool` | `true` | `:clearable="false"` |
| remote | `bool` | `false` | `:remote="true"` |
| remoteUrl | `?string` | `null` | `remote-url="/api/opts"` |
| size | untyped | `null` | `size="lg"` or magic: `lg` |
| color | untyped | `null` | `color="blue"` or magic: `blue` |
| initialValue | untyped | `null` | `:initial-value="$val"` |
| perPage | `int` | `15` | `:per-page="25"` |
| customError | untyped | `null` | `:custom-error="$error"` |
| hint | `?string` | `null` | `hint="Help text"` |
| help | `?string` | `null` | `help="Help text"` |
| autosave | `bool` | `false` | `:autosave="true"` |
| autosaveMethod | `?string` | `'savePreference'` | `autosave-method="save"` |
| autosaveKey | `?string` | `null` | `autosave-key="pref"` |
| autosaveDebounce | `int` | `300` | `:autosave-debounce="500"` |
| optionLabel | `?string` | `'label'` | `option-label="name"` |
| optionValue | `?string` | `'value'` | `option-value="id"` |
| optionDescription | `?string` | `'description'` | `option-description="desc"` |
| optionIcon | `?string` | `'icon'` | `option-icon="emoji"` |
| optionAvatar | `?string` | `'avatar'` | `option-avatar="photo"` |
| emptyMessage | `?string` | `__('beartropy-ui::ui.no_options_found')` | `empty-message="None"` |
| spinner | `bool` | `true` | `:spinner="false"` |
| defer | `bool` | `false` | `:defer="true"` |
| fitTrigger | `bool` | `true` | `:fit-trigger="false"` |

Note: `options`, `selected`, `initialValue`, `customError`, `size`, `color`, `label`, `placeholder` are intentionally untyped — they override `InputTriggerBase` which keeps them untyped for child compatibility, or accept mixed types.

## Magic Attributes

### Colors (mutually exclusive, default: `primary` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

### Modes
`fill` — tinted trigger background. `outline` (default) — transparent trigger with colored ring on focus.

## Slots

| Slot | Description |
|------|-------------|
| start | Content before the trigger button (flush, chrome stripped by CSS) |
| beforeOptions | Content at the top of the dropdown, before the options list |
| afterOptions | Content at the bottom when no results; replaces "No results" message |
| dropdown | Full dropdown override (rarely used) |

## Alpine JS Module (`resources/js/modules/select.js`)

The `beartropySelect(cfg)` function receives a config object from Blade and returns an Alpine data object. Blade-dependent values are accessed via `this._cfg.*` for runtime branching.

### Config object passed from Blade

```js
{
    value,           // Initial value: $wire.get() for Livewire, static for vanilla
    options,         // Normalized options object from PHP
    isMulti,         // bool
    perPage,         // int
    remoteUrl,       // string
    autosave,        // bool
    autosaveMethod,  // string
    autosaveKey,     // string
    autosaveDebounce,// int
    hasFieldError,   // bool
    showSpinner,     // bool
    hasWireModel,    // bool — drives syncInput() branching
    name,            // string — wire:model name or input name
    selectId,        // string — unique DOM ID
    defer,           // bool — controls eager fetch in init()
}
```

### Key methods
- `init()` — Alpine lifecycle hook: sets up `$wire` watchers (if Livewire), casts multi values to strings, triggers eager fetch (if `!defer && remoteUrl`), watches `search` and `open`
- `syncInput()` — branches on `this._cfg.hasWireModel`: Livewire path calls `$wire.set()` + `triggerAutosave()`; vanilla path creates hidden `<input>` elements in `$refs.multiInputs`
- `_fillIfNeeded()` — uses `this._cfg.selectId` to find the list element by ID
- `triggerAutosave()` — calls `this.$wire.call(method, value, key)`

## Options Normalization

The constructor calls `normalizeOptions()` which handles:
- **Eloquent Collections**: converted via `->all()`
- **Associative arrays**: key = value, value = label
- **Indexed arrays of strings**: string used as both value and label
- **Arrays of objects/arrays**: mapped using `optionLabel`, `optionValue`, etc. with fallbacks (`id`, `key`, `name`, `text`, `desc`, `subtitle`, `image`, `photo`, `picture`)

Normalized format per option:
```php
[
    '_value'      => mixed,
    'label'       => string|null,
    'icon'        => string|null,  // pre-rendered HTML or emoji
    'avatar'      => string|null,
    'description' => string|null,
]
```

Icons are pre-rendered at construct time: Heroicon names → rendered SVG, emojis → passed through, `<svg>`/`<img>` → passed through.

## Binding Mode Detection

1. **Livewire**: `wire:model` → `_cfg.hasWireModel=true` → value synced via `$wire.get()` / `$wire.set()` in JS module
2. **Vanilla**: no wire:model → `_cfg.hasWireModel=false` → hidden inputs generated dynamically via `syncInput()` into `$refs.multiInputs`

## Empty State Behavior

When `$options` is empty/null AND NOT remote:
- `$isEmpty = true`
- `$searchable` forced to `false`
- `$clearable` forced to `false`
- Shows `$emptyMessage` text

When remote: search and clear remain enabled even with no initial options.

## Preset Structure (select.php)
```
colors → {color} → {
    dropdown_bg, dropdown_shadow, dropdown_border,
    option_text, option_hover, option_active, option_selected, option_icon,
    chip_bg, chip_text, chip_close,
    badge_bg, badge_text,
    desc_text, loading_text
}
```

Also reads `input.php` presets for the trigger styling (border, ring, label, bg).

## Autosave State Machine (Alpine)

States: `idle` → `saving` → `ok` | `error`

The trigger element gets `data-state` attribute bound to the current state. CSS in `beartropy-ui.css` styles the border:
- `saving`: gray border
- `ok`: green border
- `error`: red border

## Remote API Contract

Endpoint receives: `?q=<search>&page=<int>&per_page=<int>`

Must return:
```json
{
    "options": {"id": {"_value": "id", "label": "...", ...}},
    "hasMore": true|false
}
```

Infinite scroll triggers `fetchOptions()` when user scrolls near bottom.

### Lazy Loading (Infinite Scroll)

Remote selects have built-in infinite scroll. The `<ul>` has `max-h-60` and `overflow-y-auto`, making it the scroll container. Two mechanisms load more data:
1. `@scroll` handler: when user scrolls near bottom (`scrollTop + clientHeight >= scrollHeight - 10`) and `hasMore=true`, calls `page++; fetchOptions()`
2. `_fillIfNeeded()`: after each fetch, checks if the list isn't scrollable yet (`scrollHeight <= clientHeight + 10`) and auto-fetches the next page — handles small per-page values where all items fit without scrolling

Control page size with `:per-page`.

### Deferred Fetch

Default (`defer=false`): remote selects fetch immediately via `init()` in the JS module.

With `defer=true`: the `init()` eager fetch is skipped. On first `toggle()`, the `!initDone` guard triggers `fetchOptions()`. Subsequent opens skip the fetch since `initDone=true`. Search and pagination work normally after initial fetch.

```blade
<x-bt-select :remote="true" :defer="true" remote-url="/api/users" />
```

## Slot-Based Options (`<x-bt-option>`)

The `Option` component (`x-bt-option`) is a data-only child of Select. It extends `Illuminate\View\Component` directly (not `BeartropyComponent`) and renders nothing.

### Architecture
- `Option::__construct()` pushes normalized data to `Select::$pendingSlotOptions` (static array)
- Icons are pre-rendered via `Select::renderIcon()` at construct time
- The `select.blade.php` template drains `$pendingSlotOptions` after `$selectId` assignment, merges into `$options`
- If `$isEmpty` was `true` (no `:options` prop), slot options re-enable `$searchable`/`$clearable` using `$userSearchable`/`$userClearable` (which preserve the user's original intent)

### Option Props

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| value | `string` | **required** | `value="AR"` |
| label | `?string` | value | `label="Argentina"` |
| icon | `?string` | `null` | `icon="flag"` |
| avatar | `?string` | `null` | `avatar="https://..."` |
| description | `?string` | `null` | `description="South America"` |

### Merge Behavior
- Slot options are keyed by `(string) $opt['_value']`
- On key collision, slot option overrides prop option
- `$userSearchable`/`$userClearable` track the original constructor args before the empty-options guard

```blade
{{-- Slot-only --}}
<x-bt-select name="country">
    <x-bt-option value="AR" label="Argentina" />
    <x-bt-option value="US" label="United States" icon="flag" />
</x-bt-select>

{{-- Mixed: prop + slot (slot overrides on collision) --}}
<x-bt-select name="mix" :options="['FR' => 'France']">
    <x-bt-option value="ES" label="Spain" />
</x-bt-select>
```

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-select name="status" :options="['active' => 'Active', 'inactive' => 'Inactive']" />

{{-- Livewire --}}
<x-bt-select wire:model="categoryId" :options="$categories" option-value="id" option-label="name" />

{{-- Multiple --}}
<x-bt-select wire:model="tags" :multiple="true" :options="$tags" label="Tags" />

{{-- Remote (eager) --}}
<x-bt-select name="user" :remote="true" remote-url="/api/users" label="User" />

{{-- Remote (deferred — fetch on first open) --}}
<x-bt-select name="user" :remote="true" :defer="true" remote-url="/api/users" label="User" />

{{-- With help text --}}
<x-bt-select name="role" :options="$roles" help="Determines access level" />

{{-- Custom error --}}
<x-bt-select name="type" :options="$types" :custom-error="'Selection required'" />

{{-- Autosave --}}
<x-bt-select wire:model="theme" :options="$themes" :autosave="true" autosave-key="theme" />

{{-- In Input slot --}}
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select :options="['+1', '+44']" placeholder="Code" />
    </x-slot:start>
</x-bt-input>

{{-- In Input slot with wider dropdown --}}
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select :options="$codes" placeholder="Code" :fit-trigger="false" />
    </x-slot:start>
</x-bt-input>

{{-- Sized --}}
<x-bt-select sm name="s" :options="$opts" />

{{-- Colored --}}
<x-bt-select blue name="s" :options="$opts" />
```

## Config Defaults
```php
'select' => [
    'color' => env('BEARTROPY_UI_SELECT_COLOR', 'primary'),
    'size' => env('BEARTROPY_UI_SELECT_SIZE', 'md'),
],
```

## Keyboard Navigation

Full keyboard support via Alpine event handlers on the wrapper `<div>`:

### Alpine State & Methods (in `select.js`)
- `highlightedIndex: -1` — tracks currently highlighted option index (-1 = none)
- `move(delta)` — circular navigation through `filteredOptions()` using modulo wrapping; calls `scrollHighlightedIntoView()`
- `selectHighlighted()` — calls `setValue()` with the highlighted option's ID
- `scrollHighlightedIntoView()` — finds the `<li>` via `[data-select-index="N"]` inside the list `<ul>` and calls `scrollIntoView({ block: 'nearest' })`

### Reset Behavior
- `toggle()` on open → resets `highlightedIndex` to `0` (or `-1` if empty)
- `close()` → resets to `-1`
- `search` watcher → resets to `0` when search changes (filtered list changes)

### Keyboard Bindings (on wrapper div)
- Arrow Down/Up: open dropdown or move highlight
- Enter: select highlighted option (open) or toggle (closed)
- Escape: close dropdown
- Space: toggle open (only when not focused on an `<input>`, so typing in search still works)

### ARIA
- `<ul>`: `role="listbox"`
- `<li>`: `role="option"`, `:aria-selected="isSelected(id)"`

### Highlight Styling
- Options use `:class` binding: `bg-neutral-100 dark:bg-neutral-800` when `idx === highlightedIndex`
- Mouse hover syncs via `@mouseenter="highlightedIndex = idx"` on each `<li>`

## Key Notes
- Alpine JS logic lives in `resources/js/modules/select.js`, not inline in the Blade template; Blade passes a config object with server-side values (`hasWireModel`, `name`, `selectId`, `defer`), and the JS module handles all runtime branching
- `searchable` and `clearable` default `true` but are auto-disabled when options are empty (and not remote)
- `primary` color = neutral gray dropdown + beartropy accents on selected/active states
- `help` and `hint` are aliases — both show text below the field via `field-help`; `help` takes precedence
- `spinner` defaults `true` — shows loading spinner on `wire:loading` when wire:model is present and autosave is off
- Options are normalized at construct time; icons are pre-rendered to HTML
- Multiple mode shows max 3 chips + `+N` badge; single mode shows label with optional icon/avatar
- The end slot uses flex layout (not absolute positioning) — works correctly inside Input slot integration
- The dropdown uses `DropdownBase` with teleport enabled by default
- `defer` only affects remote selects — defers the initial `fetchOptions()` from `init()` to first dropdown open via `toggle()`
- Remote selects have built-in infinite scroll — control page size with `per-page`
- Icons are rendered with `x-html` (not `x-text`) since they may contain pre-rendered SVG HTML
- `fitTrigger` controls dropdown width: `true` = `width` matches trigger exactly, `false` = `min-width` only (dropdown can grow). Passed to `DropdownBase` as `fit-anchor`
- `filter_var($prop, FILTER_VALIDATE_BOOLEAN)` is used for bool props — string `"true"`/`"false"` from Blade attributes work correctly
- `<x-bt-option>` uses a static collector pattern — Blade renders child constructors synchronously before the parent template runs, so `Select::$pendingSlotOptions` is populated by the time `select.blade.php` executes
- `Select::renderIcon()` is a public static method reused by both `normalizeOptions()` and `Option::__construct()`
