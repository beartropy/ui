# Input

A text input component with label, help text, icons, clear button, copy-to-clipboard, password toggle, and slot integration for buttons and selects.

## Basic Usage

```blade
<x-bt-input label="Name" placeholder="Enter your name..." />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Label text above the input |
| `placeholder` | `string\|null` | `null` | Placeholder text |
| `type` | `string` | `'text'` | HTML input type: `text`, `email`, `password`, `number`, `url`, `tel`, `search`, etc. |
| `value` | `mixed` | `null` | Initial input value |
| `color` | `string\|null` | config default | Color preset name |
| `size` | `string\|null` | `'md'` | Size preset name |
| `icon-start` | `string\|null` | `null` | Icon name at the start |
| `icon-end` | `string\|null` | `null` | Icon name at the end |
| `clearable` | `bool` | `true` | Show clear button when input has value |
| `copy-button` | `bool` | `false` | Show copy-to-clipboard button |
| `help` | `string\|null` | `null` | Help text below the input |
| `hint` | `string\|null` | `null` | Alias for `help` |
| `spinner` | `bool` | `true` | Show loading spinner during Livewire actions |
| `custom-error` | `mixed` | `null` | Custom error message (bypasses validation bag) |
| `disabled` | `bool` | `false` | Disables the input |
| `readonly` | — | — | HTML attribute, pass directly |

## Colors

Two modes — **outline** (default) and **fill**:

```blade
{{-- Outline mode: transparent bg, colored ring on focus --}}
<x-bt-input label="Default" placeholder="Type..." />
<x-bt-input label="Blue" blue placeholder="Type..." />

{{-- Fill mode: tinted background matching the color --}}
<x-bt-input fill label="Default Fill" placeholder="Type..." />
<x-bt-input fill blue label="Blue Fill" placeholder="Type..." />
```

All 24 colors + `primary`: `primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

The `primary` color is a neutral gray with a beartropy focus ring — ideal for most forms.

You can also use the `color` prop dynamically:

```blade
<x-bt-input :color="$hasError ? 'red' : 'primary'" label="Email" />
```

## Sizes

```blade
<x-bt-input xs label="Extra Small" />
<x-bt-input sm label="Small" />
<x-bt-input md label="Medium (default)" />
<x-bt-input lg label="Large" />
<x-bt-input xl label="Extra Large" />
```

## Icons

```blade
<x-bt-input label="Email" icon-start="envelope" placeholder="you@example.com" />
<x-bt-input label="Search" icon-end="magnifying-glass" placeholder="Search..." />
```

## Built-in Controls

### Clear Button

Shown automatically when the input has a value (enabled by default):

```blade
<x-bt-input label="Clearable" value="Clear me" />

{{-- Disable --}}
<x-bt-input label="No Clear" :clearable="false" />
```

### Copy to Clipboard

```blade
<x-bt-input label="API Key" :copy-button="true" :clearable="false" value="sk-1234abcd" />
```

### Password Toggle

Automatically shown when `type="password"`:

```blade
<x-bt-input label="Password" type="password" placeholder="••••••••" />
```

## Slot Integration

Place buttons, selects, or other components inside the input as seamless addons. Chrome (borders, shadows, rounded corners) is automatically stripped.

### End Slot — Button

```blade
<x-bt-input label="Message" placeholder="Type a message...">
    <x-slot:end>
        <x-bt-button color="beartropy">Send</x-bt-button>
    </x-slot:end>
</x-bt-input>
```

### Start Slot — Button

```blade
<x-bt-input label="URL" placeholder="example.com">
    <x-slot:start>
        <x-bt-button color="gray" soft>https://</x-bt-button>
    </x-slot:start>
</x-bt-input>
```

### Both Slots

```blade
<x-bt-input label="URL Builder" placeholder="my-page">
    <x-slot:start>
        <x-bt-button color="gray" soft>https://</x-bt-button>
    </x-slot:start>
    <x-slot:end>
        <x-bt-button color="green">Go</x-bt-button>
    </x-slot:end>
</x-bt-input>
```

### Select in Slot

```blade
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select :options="['+1 US', '+44 UK', '+34 ES']" placeholder="Code" />
    </x-slot:start>
</x-bt-input>
```

### Slots + Built-in Controls

Custom slots combine with icons, clearable, copy, etc.:

```blade
<x-bt-input label="Email" icon-start="envelope" :clearable="true" value="test@example.com">
    <x-slot:end>
        <x-bt-button color="beartropy">Send</x-bt-button>
    </x-slot:end>
</x-bt-input>
```

## Livewire Integration

```blade
{{-- Deferred (default in Livewire 3) --}}
<x-bt-input wire:model="name" label="Name" />

{{-- Real-time --}}
<x-bt-input wire:model.live="search" label="Search" icon-start="magnifying-glass" />

{{-- Debounced --}}
<x-bt-input wire:model.live.debounce.300ms="query" label="Query" />
```

The spinner shows automatically when Livewire targets are detected.

## Alpine.js Integration

```blade
{{-- External Alpine model --}}
<x-bt-input x-model="formData.name" label="Name" />

{{-- Standalone (local Alpine state) --}}
<x-bt-input label="Local" value="initial" />
```

## Validation Errors

Errors are automatically detected from the Laravel validation error bag using the `wire:model` name:

```blade
{{-- Auto error from $errors bag --}}
<x-bt-input wire:model="email" label="Email" />

{{-- Custom error message --}}
<x-bt-input label="Code" :custom-error="$codeError" />
```

When in error state, the border turns red and an error message appears below.

## Help Text

```blade
<x-bt-input label="Username" help="Choose a unique username" />
<x-bt-input label="Password" hint="Must be at least 8 characters" type="password" />
```

## Disabled & Readonly

```blade
<x-bt-input label="Disabled" disabled placeholder="Cannot edit" />
<x-bt-input label="Readonly" readonly value="Read only value" />
```

## Fill Mode + Slots

```blade
<x-bt-input fill color="blue" label="Blue Search" placeholder="Search...">
    <x-slot:end>
        <x-bt-button color="blue">Go</x-bt-button>
    </x-slot:end>
</x-bt-input>
```

## Configuration

```php
'component_defaults' => [
    'input' => [
        'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
        'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
        'outline' => env('BEARTROPY_UI_INPUT_OUTLINE', true),
    ],
],
```

## Dark Mode

All colors and modes include dark mode styles automatically. Outline mode uses `bg-white dark:bg-gray-900`, fill mode uses color-tinted backgrounds with dark variants.
