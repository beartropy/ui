# x-bt-lookup — AI Reference

## Component Tag
```blade
<x-bt-lookup />
```

## Architecture
- `Lookup` → extends `BeartropyComponent` (NOT Input/InputBase)
- Renders: `lookup.blade.php` → delegates to `base/input-base.blade.php` for the text field + `base/dropdown-base.blade.php` (preset-for="select") for the dropdown
- Alpine module: `resources/js/modules/lookup.js` → registered as `beartropyLookup` via `Alpine.data()`
- Presets: uses `input` preset for colors/sizes (no separate `lookup` preset)
- Sizes: global `resources/views/presets/sizes.php`
- Config defaults: `component_defaults.lookup` (color only)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | `'beartropy-lookup-' . uniqid()` | `id="my-lookup"` |
| name | `?string` | same as `$id` | `name="country"` |
| label | `?string` | `null` | `label="Country"` |
| color | `?string` | config `lookup.color` (`'beartropy'`) | `color="blue"` or magic: `blue` |
| size | `?string` | `null` (resolves to `'md'`) | `size="lg"` or magic: `lg` |
| placeholder | `?string` | `null` | `placeholder="Search..."` |
| options | `array` | `[]` | `:options="$items"` |
| optionLabel | `string` | `'name'` | `option-label="title"` |
| optionValue | `string` | `'id'` | `option-value="code"` |
| value | `mixed` | `null` | `value="pre-filled"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| readonly | `bool` | `false` | `:readonly="true"` |
| clearable | `bool` | `true` | `:clearable="false"` |
| iconStart | `?string` | `null` | `icon-start="magnifying-glass"` |
| iconEnd | `?string` | `null` | `icon-end="chevron-down"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |
| customError | `mixed` | `null` | `custom-error="Required"` or `:custom-error="$error"` |

## Magic Attributes

### Colors (mutually exclusive, default: `beartropy` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| start | Content before the input field (chrome stripped by CSS) |
| end | Content after built-in controls (chrome stripped by CSS) |

The `dropdown` slot is used internally for the options list — not user-facing.

## Options Normalization

The constructor calls `normalizeOptions()` which converts all formats to `[{id: string, name: string}, ...]`:

| Input Format | Example | Normalized |
|-------------|---------|------------|
| Simple array | `['Apple', 'Banana']` | `[{id:'Apple', name:'Apple'}, ...]` |
| Object array | `[['id'=>1, 'name'=>'Foo']]` | `[{id:'1', name:'Foo'}]` |
| Custom keys | `[['code'=>'X', 'title'=>'Y']]` with `option-value="code"` `option-label="title"` | `[{id:'X', name:'Y'}]` |
| Key-value pair | `[['ar'=>'Argentina']]` | `[{id:'ar', name:'Argentina'}]` |
| Unrecognizable | `[['foo'=>'bar','baz'=>'qux']]` with default keys | discarded (null) |

All `id` values are cast to string. Options are passed to the Alpine module via `data-options` JSON attribute.

## Alpine Module: `beartropyLookup(cfg)`

### Config (from Blade)
```js
{ inputId, isLivewire, labelKey, valueKey, wireModelName }
```

### State
- `open` — dropdown visibility
- `highlighted` — index of highlighted option (-1 = none)
- `options` — full options array (synced from `data-options` attribute)
- `filtered` — currently filtered subset

### Key Methods
- `onInput(e)` — filters options, opens dropdown, syncs hidden input
- `move(delta)` — keyboard highlight navigation (wraps around)
- `choose(idx)` — selects option, sets visible value to label, sets hidden input to value
- `confirm()` — selects highlighted or syncs raw text
- `close()` — closes dropdown
- `clearBoth()` — clears visible input + hidden Livewire input
- `setVisibleValue(v)` — sets visible input by ID lookup (cross-scope)

### Livewire Sync
- Uses `$watch('$wire.' + wireModelName)` (Livewire 3 pattern)
- MutationObserver watches `data-options` attribute for Livewire morphdom updates
- Hidden `<input x-ref="livewireValue">` carries the `wire:model` binding

### Filtering
- Diacritic-insensitive: `normalize()` strips combining marks via `NFD` + regex
- Case-insensitive substring match on the label key
- Exact match → hidden value set to option's value; no match → raw text sent

## Mode Detection

The template detects Livewire mode from `wire:model`:

1. **Livewire** (`wire:model` present): hidden input with `wire:model`, visible input shows label, Alpine syncs via `$wire`
2. **Plain** (no `wire:model`): visible input only, value passed via HTML `value` attribute, input-base manages local Alpine state

No `x-model` detection (unlike Input) — Lookup always uses its own `beartropyLookup` Alpine module.

## Error State Detection
- Auto-reads from Laravel `$errors` bag using `wire:model` name
- Override with `:custom-error="$message"` prop
- Error state: red border, red label, error message below field via `field-help` component

## Livewire Loading Spinner
- Auto-detects `wire:*` attributes (except `wire:model*`)
- Shows spinner in the end controls area during Livewire requests
- Uses `wire:loading` + `wire:target` with auto-detected targets

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-lookup label="Country" placeholder="Search..." :options="['Argentina', 'Brazil', 'Canada']" />

{{-- Livewire --}}
<x-bt-lookup wire:model="country" label="Country" :options="$countries" />

{{-- Object options --}}
<x-bt-lookup
    label="User"
    :options="[['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']]"
    wire:model="userId" />

{{-- Custom field mapping --}}
<x-bt-lookup
    :options="$cities"
    option-label="title"
    option-value="code"
    wire:model="cityCode" />

{{-- With icon --}}
<x-bt-lookup icon-start="magnifying-glass" label="Search" :options="$items" />

{{-- Color + Size --}}
<x-bt-lookup blue sm label="Small Blue" :options="$items" />

{{-- Not clearable --}}
<x-bt-lookup :clearable="false" :options="$items" />

{{-- Disabled --}}
<x-bt-lookup :disabled="true" label="Locked" :options="$items" />

{{-- Readonly --}}
<x-bt-lookup :readonly="true" value="Fixed value" :options="$items" />

{{-- Validation error --}}
<x-bt-lookup wire:model="field" label="Required" :options="$items" />

{{-- Custom error --}}
<x-bt-lookup label="Field" custom-error="Selection required." :options="$items" />

{{-- End slot with button --}}
<x-bt-lookup label="Search" :clearable="false" :options="$items">
    <x-slot:end>
        <x-bt-button color="blue">Go</x-bt-button>
    </x-slot:end>
</x-bt-lookup>

{{-- Start slot with icon --}}
<x-bt-lookup label="Search" :options="$items">
    <x-slot:start>
        <span class="flex items-center px-2 text-gray-400">
            <x-bt-icon name="globe-alt" class="w-5 h-5" />
        </span>
    </x-slot:start>
</x-bt-lookup>
```

## Config Defaults
```php
'lookup' => [
    'color' => env('BEARTROPY_UI_LOOKUP_COLOR', 'beartropy'),
],
```

## Key Notes
- Lookup extends `BeartropyComponent` directly — NOT `Input` or `InputBase`. It has its own clean constructor with all props typed.
- Uses `input` preset for color/size resolution (calls `$getComponentPresets('input')`), but default color comes from `component_defaults.lookup` (resolved in constructor).
- No separate `lookup` preset file exists. The dropdown uses `select` preset via `preset-for="select"`.
- `id` is auto-generated as `beartropy-lookup-{uniqid}` if not provided. `name` defaults to `id`.
- `clearable` defaults to `true` — disable with `:clearable="false"` when using an end slot button.
- `help` and `hint` are independent — both show text below the field; `help` takes precedence via `$help ?? $hint`.
- Options are normalized server-side in PHP and passed to Alpine via `data-options` JSON attribute. The Alpine module reads them with a MutationObserver for Livewire reactivity.
- The visible input and hidden input are separate elements. The visible input shows the label, the hidden input (Livewire only) stores the value.
- Keyboard: Down/Up move highlight, Enter/Tab confirm, Escape closes. Filtering is diacritic-insensitive.
- The Alpine module uses `document.getElementById(inputId)` to access the visible input cross-scope (since input-base has its own `x-data`).
