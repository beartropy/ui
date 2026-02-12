# Lookup

An autocomplete/combobox input — type text to filter a dropdown of options, then pick one. Supports simple arrays, object arrays, key-value pairs, Livewire binding, icons, clearable, and slot integration.

## Basic Usage

```blade
<x-bt-lookup
    label="Country"
    placeholder="Search countries..."
    :options="['Argentina', 'Brazil', 'Canada', 'France', 'Germany']"
    wire:model="country" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | `string\|null` | auto-generated | Element ID (`beartropy-lookup-{uniqid}` if omitted) |
| `name` | `string\|null` | same as `id` | Hidden input name for form submission |
| `label` | `string\|null` | `null` | Label text above the input |
| `placeholder` | `string\|null` | `null` | Placeholder text |
| `options` | `array` | `[]` | Options array (see Options Formats below) |
| `option-label` | `string` | `'name'` | Key used for the display label in each option |
| `option-value` | `string` | `'id'` | Key used for the stored value in each option |
| `value` | `mixed` | `null` | Initial input value |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `icon-start` | `string\|null` | `null` | Icon name at the start |
| `icon-end` | `string\|null` | `null` | Icon name at the end |
| `clearable` | `bool` | `true` | Show clear button when input has a value |
| `disabled` | `bool` | `false` | Disables the input |
| `readonly` | `bool` | `false` | Makes the input read-only |
| `help` | `string\|null` | `null` | Help text below the input |
| `hint` | `string\|null` | `null` | Alias for `help` |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |

## Options Formats

The `options` prop accepts three formats. All are normalized into `[{id, name}, ...]` internally.

### Simple Array

Each string is both the value and the label:

```blade
<x-bt-lookup :options="['Apple', 'Banana', 'Cherry']" />
```

### Object Array

Array of objects/arrays — uses `option-label` and `option-value` to extract fields:

```blade
<x-bt-lookup
    :options="[
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Smith'],
    ]"
    option-label="name"
    option-value="id" />
```

### Custom Field Mappings

Map any object shape with `option-label` and `option-value`:

```blade
<x-bt-lookup
    :options="[
        ['code' => 'NYC', 'title' => 'New York City'],
        ['code' => 'LON', 'title' => 'London'],
    ]"
    option-label="title"
    option-value="code" />
```

### Key-Value Pairs

Single key-value pair arrays — key becomes the value, value becomes the label:

```blade
<x-bt-lookup :options="[['en' => 'English'], ['es' => 'Spanish'], ['fr' => 'French']]" />
```

## Colors

Color affects border/ring on focus. Uses the `input` preset colors:

```blade
<x-bt-lookup blue label="Blue" :options="[...]" />
<x-bt-lookup green label="Green" :options="[...]" />
<x-bt-lookup color="red" label="Red" :options="[...]" />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

Dynamic color:

```blade
<x-bt-lookup :color="$hasError ? 'red' : 'primary'" :options="$items" />
```

## Sizes

```blade
<x-bt-lookup xs label="Extra Small" :options="[...]" />
<x-bt-lookup sm label="Small" :options="[...]" />
<x-bt-lookup md label="Medium (default)" :options="[...]" />
<x-bt-lookup lg label="Large" :options="[...]" />
<x-bt-lookup xl label="Extra Large" :options="[...]" />
```

## Icons

```blade
<x-bt-lookup label="Search" icon-start="magnifying-glass" :options="[...]" />
<x-bt-lookup label="Select" icon-end="chevron-down" :options="[...]" />
<x-bt-lookup label="Both" icon-start="magnifying-glass" icon-end="chevron-down" :options="[...]" />
```

## Clearable

Enabled by default. A clear button appears when the input has a value:

```blade
{{-- Default: clearable --}}
<x-bt-lookup label="Country" :options="$countries" />

{{-- Disable clear button --}}
<x-bt-lookup label="Country" :clearable="false" :options="$countries" />
```

## Slot Integration

### Start Slot

```blade
<x-bt-lookup label="Search" :options="$items">
    <x-slot:start>
        <span class="flex items-center px-2 text-gray-400">
            <x-bt-icon name="globe-alt" class="w-5 h-5" />
        </span>
    </x-slot:start>
</x-bt-lookup>
```

### End Slot

```blade
<x-bt-lookup label="Search" :clearable="false" :options="$items">
    <x-slot:end>
        <x-bt-button color="blue">Go</x-bt-button>
    </x-slot:end>
</x-bt-lookup>
```

### Both Slots

```blade
<x-bt-lookup label="Search" :clearable="false" :options="$items">
    <x-slot:start>
        <x-bt-button color="gray" soft>Scope</x-bt-button>
    </x-slot:start>
    <x-slot:end>
        <x-bt-button color="blue">Search</x-bt-button>
    </x-slot:end>
</x-bt-lookup>
```

## Livewire Integration

```blade
{{-- Deferred (default in Livewire 3) --}}
<x-bt-lookup wire:model="country" label="Country" :options="$countries" />

{{-- Real-time --}}
<x-bt-lookup wire:model.live="search" label="Search" :options="$results" />
```

In Livewire mode:
- A hidden `<input>` carries the `wire:model` binding with the selected **value** (e.g., the `id`)
- The visible input shows the **label** (e.g., the `name`)
- When the user picks an option, the hidden input is updated and dispatches an `input` event
- If the typed text exactly matches an option label, the hidden value is set to that option's value; otherwise, the raw text is sent

## Validation Errors

Errors are automatically detected from the Laravel validation error bag using the `wire:model` name:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-lookup wire:model="category" label="Category" :options="$categories" />

{{-- Custom error message --}}
<x-bt-lookup label="Category" custom-error="This field is required." :options="$categories" />
```

When in error state, the border turns red and an error message appears below.

## Help Text

```blade
<x-bt-lookup label="Country" help="Start typing to see matching results." :options="$countries" />
<x-bt-lookup label="Country" hint="Select from the dropdown or type a custom value." :options="$countries" />
```

## Disabled & Readonly

```blade
<x-bt-lookup label="Disabled" :disabled="true" :options="$countries" />
<x-bt-lookup label="Readonly" :readonly="true" value="Pre-filled" :options="$countries" />
```

## Keyboard Navigation

The lookup supports full keyboard navigation:

| Key | Action |
|-----|--------|
| `Down` | Move highlight down |
| `Up` | Move highlight up |
| `Enter` | Select highlighted option |
| `Tab` | Confirm current selection |
| `Escape` | Close dropdown |

Typing filters the options list in real-time (diacritic-insensitive).

## Configuration

```php
'component_defaults' => [
    'lookup' => [
        'color' => env('BEARTROPY_UI_LOOKUP_COLOR', 'beartropy'),
    ],
],
```

## Dark Mode

All colors include dark mode styles automatically. The input uses `bg-white dark:bg-gray-900`, the dropdown uses the `select` preset dark styles, and option highlighting uses `bg-neutral-100 dark:bg-neutral-800`.
