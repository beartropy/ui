# Select

A powerful select input supporting single and multiple selection, search filtering, remote data fetching, Eloquent collections, custom option mappings, autosave, and rich options with icons, avatars, and descriptions.

## Basic Usage

```blade
<x-bt-select name="fruit" :options="['Apple', 'Banana', 'Orange']" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `options` | `array\|Collection\|null` | `null` | Options data (simple array, associative, or array of objects) |
| `selected` | `mixed` | `null` | Initially selected value |
| `label` | `string\|null` | `null` | Label text above the select |
| `placeholder` | `string\|null` | `'Select...'` | Placeholder text when nothing is selected |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `searchable` | `bool` | `true` | Show search input in the dropdown |
| `clearable` | `bool` | `true` | Show clear button when a value is selected |
| `multiple` | `bool` | `false` | Enable multiple selection with chips |
| `icon` | `string\|null` | `null` | Trigger icon name |
| `initial-value` | `mixed` | `null` | Initial value (for non-Livewire usage) |
| `help` | `string\|null` | `null` | Help text below the field |
| `hint` | `string\|null` | `null` | Alias for `help` |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `spinner` | `bool` | `true` | Show loading spinner during Livewire actions |
| `empty-message` | `string\|null` | `'No options found'` | Text when options list is empty |
| `per-page` | `int` | `15` | Results per page (for remote/pagination) |
| `fit-trigger` | `bool` | `true` | Match dropdown width to trigger (`false` = dropdown can be wider) |

### Remote Data Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `remote` | `bool` | `false` | Enable remote data fetching |
| `remote-url` | `string\|null` | `null` | API endpoint for remote options |
| `defer` | `bool` | `false` | Defer remote fetch until dropdown opens for the first time |

### Option Mapping Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `option-label` | `string` | `'label'` | Key for option label text |
| `option-value` | `string` | `'value'` | Key for option value |
| `option-description` | `string` | `'description'` | Key for option description |
| `option-icon` | `string` | `'icon'` | Key for option icon |
| `option-avatar` | `string` | `'avatar'` | Key for option avatar |

### Autosave Props (Livewire)

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `autosave` | `bool` | `false` | Auto-save selection on change via Livewire |
| `autosave-method` | `string` | `'savePreference'` | Livewire method to call |
| `autosave-key` | `string\|null` | `null` | Key passed to the autosave method |
| `autosave-debounce` | `int` | `300` | Debounce delay in milliseconds |

## Slot-Based Options (`<x-bt-option>`)

Instead of passing a PHP array, you can declare options directly in Blade using `<x-bt-option>` child components:

```blade
<x-bt-select name="country">
    <x-bt-option value="AR" label="Argentina" />
    <x-bt-option value="US" label="United States" />
    <x-bt-option value="BR" label="Brazil" icon="flag" />
</x-bt-select>
```

### Option Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `value` | `string` | **required** | Option value |
| `label` | `string\|null` | value | Display label (defaults to `value` if omitted) |
| `icon` | `string\|null` | `null` | Icon name, emoji, or raw SVG |
| `avatar` | `string\|null` | `null` | Avatar URL or emoji |
| `description` | `string\|null` | `null` | Secondary description text |

### Mixing Prop + Slot Options

Slot options are merged after prop options. If a slot option has the same `value` as a prop option, the slot option wins:

```blade
<x-bt-select name="mix" :options="['FR' => 'France', 'DE' => 'Germany']">
    <x-bt-option value="ES" label="Spain" />
    <x-bt-option value="DE" label="Deutschland" />  {{-- overrides "Germany" --}}
</x-bt-select>
```

When only slot options are provided (no `:options` prop), `searchable` and `clearable` default to their normal values (`true`). If you explicitly pass `:searchable="false"` or `:clearable="false"`, those are respected.

## Options Formats

### Simple Array

```blade
<x-bt-select name="fruit" :options="['Apple', 'Banana', 'Orange']" />
```

### Associative Array

```blade
<x-bt-select name="status" :options="['active' => 'Active', 'inactive' => 'Inactive']" />
```

### Array of Objects

```blade
@php
$users = [
    ['value' => 1, 'label' => 'Alice', 'description' => 'Admin'],
    ['value' => 2, 'label' => 'Bob', 'description' => 'Editor'],
];
@endphp
<x-bt-select name="user" :options="$users" />
```

### Eloquent Collection with Custom Mappings

```blade
<x-bt-select
    name="category"
    :options="$categories"
    option-value="id"
    option-label="name"
    option-description="slug"
/>
```

### Rich Options with Icons and Avatars

```blade
@php
$options = [
    ['value' => 'us', 'label' => 'United States', 'icon' => 'flag', 'description' => '+1'],
    ['value' => 'uk', 'label' => 'United Kingdom', 'avatar' => 'https://example.com/uk.png'],
];
@endphp
<x-bt-select name="country" :options="$options" />
```

Icons support Heroicons names, emojis, raw SVG, and `<img>` tags. Avatars support URLs (rendered as `<img>`) and text/emoji (rendered as text).

## Colors

```blade
<x-bt-select name="s" :options="$opts" />                  {{-- default: primary --}}
<x-bt-select name="s" :options="$opts" color="beartropy" />
<x-bt-select name="s" :options="$opts" blue />
<x-bt-select name="s" :options="$opts" red />
```

All 25 colors: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

The `primary` color uses neutral gray dropdown styling with beartropy accents for selected/active states.

Colors affect the dropdown background, option hover/active/selected states, chip styling, badge colors, description text, and border.

## Sizes

```blade
<x-bt-select xs name="s" :options="$opts" />
<x-bt-select sm name="s" :options="$opts" />
<x-bt-select    name="s" :options="$opts" />  {{-- md default --}}
<x-bt-select lg name="s" :options="$opts" />
<x-bt-select xl name="s" :options="$opts" />
```

## Multiple Selection

```blade
<x-bt-select name="tags" :multiple="true" :options="$tags" />
```

Selected values display as chips (max 3 visible, with a `+N` badge for overflow). Each chip has a remove button. The dropdown shows checkboxes next to each option.

## Slots

### Start Slot

```blade
<x-bt-select name="s" :options="$opts">
    <x-slot:start>
        <x-bt-button color="gray" soft>Prefix</x-bt-button>
    </x-slot:start>
</x-bt-select>
```

### Before/After Options

```blade
<x-bt-select name="s" :options="$opts">
    <x-slot:beforeOptions class="p-2 text-sm text-gray-500">
        Recently used
    </x-slot:beforeOptions>
    <x-slot:afterOptions class="p-2">
        <a href="/create">Create new...</a>
    </x-slot:afterOptions>
</x-bt-select>
```

The `afterOptions` slot replaces the "No results" message when the filtered list is empty.

## Remote Data

```blade
<x-bt-select
    name="user"
    :remote="true"
    remote-url="/api/users"
    :per-page="20"
    label="Select User"
/>
```

The endpoint receives `?q=search&page=1&per_page=20` and must return:

```json
{
    "options": {
        "1": {"_value": 1, "label": "Alice"},
        "2": {"_value": 2, "label": "Bob"}
    },
    "hasMore": true
}
```

Infinite scroll is built-in: when the user scrolls near the bottom, the next page is fetched automatically.

### Lazy Loading (Infinite Scroll)

Remote selects have built-in infinite scroll. Set a small `per-page` value and when the user scrolls near the bottom of the dropdown, the next page is fetched automatically:

```blade
<x-bt-select
    name="user"
    :remote="true"
    remote-url="/api/users"
    :per-page="5"
    label="Scroll to load more"
/>
```

A "Loading..." indicator appears while the next page is being fetched.

### Deferred Fetch

By default, remote selects fetch data immediately on page load. Use `:defer="true"` to delay the first fetch until the user opens the dropdown. This improves page performance when you have many remote selects.

```blade
<x-bt-select
    name="user"
    :remote="true"
    :defer="true"
    remote-url="/api/users"
    label="Click to load users"
/>
```

On first open, options are fetched and cached. Subsequent opens reuse the cached data. Search and pagination work normally after the initial fetch.

## Livewire Integration

```blade
{{-- Basic binding --}}
<x-bt-select wire:model="selectedId" :options="$options" label="Choose" />

{{-- Live binding --}}
<x-bt-select wire:model.live="category" :options="$categories" />

{{-- Multiple --}}
<x-bt-select wire:model="selectedTags" :multiple="true" :options="$tags" />
```

The spinner shows automatically when Livewire actions target the wire:model property.

### Autosave

```blade
<x-bt-select
    wire:model="preference"
    :options="$options"
    :autosave="true"
    autosave-method="savePreference"
    autosave-key="theme"
/>
```

Autosave calls `$wire.call(method, value, key)` on selection change. The trigger border shows state feedback: gray (saving), green (ok), red (error).

## Validation Errors

Errors are automatically detected from the Laravel validation error bag:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-select wire:model="category" :options="$opts" label="Category" />

{{-- Custom error --}}
<x-bt-select name="role" :options="$opts" :custom-error="'Please select a role'" />
```

## Help Text

```blade
<x-bt-select name="s" :options="$opts" help="Choose your preferred option" />
<x-bt-select name="s" :options="$opts" hint="Required field" />
```

## Slot Integration

The Select can be placed inside an Input's start or end slot. Chrome (borders, shadows) is automatically stripped:

```blade
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select :options="['+1 US', '+44 UK', '+34 ES']" placeholder="Code" />
    </x-slot:start>
</x-bt-input>
```

### Wider Dropdown

When the Select is inside a narrow slot, the dropdown may need to be wider than the trigger. Use `:fit-trigger="false"` to allow the dropdown to expand beyond the trigger width:

```blade
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select
            :options="$countryCodes"
            placeholder="Code"
            :fit-trigger="false"
        />
    </x-slot:start>
</x-bt-input>
```

With `fit-trigger="true"` (default), the dropdown width matches the trigger exactly. With `fit-trigger="false"`, the dropdown uses `min-width` instead, allowing it to grow to fit its content.

## Configuration

```php
'component_defaults' => [
    'select' => [
        'color' => env('BEARTROPY_UI_SELECT_COLOR', 'primary'),
        'size' => env('BEARTROPY_UI_SELECT_SIZE', 'md'),
    ],
],
```

## Keyboard Navigation

The Select component supports full keyboard navigation:

| Key | Dropdown Closed | Dropdown Open |
|-----|----------------|---------------|
| Arrow Down | Open dropdown | Move highlight down |
| Arrow Up | Open dropdown | Move highlight up |
| Enter | Open dropdown | Select highlighted option |
| Space | Open dropdown | Type in search (if searchable) |
| Escape | — | Close dropdown |
| Tab | Normal tab behavior | Normal tab behavior |

Navigation wraps circularly (last → first, first → last). The highlighted option scrolls into view automatically. Mouse hover syncs with keyboard highlight.

The trigger is focusable via Tab (`tabindex="0"`). When `searchable` is enabled, focus moves to the search input on open. ARIA attributes (`role="listbox"`, `role="option"`, `aria-selected`) are included for screen reader support.

## Dark Mode

All colors include dark mode styles automatically. Dropdown backgrounds use `bg-white dark:bg-gray-900/95`, text and hover states adapt for dark themes.
