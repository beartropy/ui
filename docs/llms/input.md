# x-bt-input — AI Reference

## Component Tag
```blade
<x-bt-input />
```

## Architecture
- `Input` → extends `InputBase` → extends `BeartropyComponent`
- Renders: `input.blade.php` → delegates to `base/input-base.blade.php`
- Presets: `resources/views/presets/input.php` (flat color → classes)
- Sizes: global `resources/views/presets/sizes.php`
- CSS: `resources/css/beartropy-ui.css` (slot chrome stripping, autofill fixes, focus resets)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| iconStart | `?string` | `null` | `icon-start="envelope"` |
| iconStartSvg | `?string` | `null` | `icon-start-svg="<svg>..."` |
| iconEnd | `?string` | `null` | `icon-end="magnifying-glass"` |
| iconEndSvg | `?string` | `null` | `icon-end-svg="<svg>..."` |
| copyButton | `bool` | `false` | `:copy-button="true"` |
| clearable | `bool` | `true` | `:clearable="false"` |
| help | `?string` | `null` | `help="Help text"` |
| showPasswordToggle | `bool` | `false` | `:show-password-toggle="true"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |
| value | `mixed` | `null` | `value="text"` |
| hint | `?string` | `null` | `hint="Hint text"` |
| type | untyped | `'text'` | `type="password"` |
| size | untyped | `null` | `size="lg"` or magic: `lg` |
| color | untyped | `null` | `color="blue"` or magic: `blue` |
| label | untyped | `null` | `label="Email"` |
| placeholder | untyped | `null` | `placeholder="Type..."` |
| spinner | `bool` | `true` | `:spinner="false"` |

Note: `type`, `size`, `color`, `label`, `placeholder` are intentionally untyped because `InputBase` keeps them untyped for child class compatibility.

## Magic Attributes

### Colors (mutually exclusive, default: `primary` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

### Fill Mode
`fill` — applies `$colorPreset['bg']` tinted background. Without `fill`, the input gets `bg-white dark:bg-gray-900`.

## Slots

| Slot | Description |
|------|-------------|
| start | Content before the input field (flush, chrome stripped by CSS) |
| end | Content after built-in controls (flush, chrome stripped by CSS) |

### Slot Chrome Stripping
CSS class `.beartropy-inputbase-start-slot > *` and `.beartropy-inputbase-end-slot > *` strip:
- `border-width: 0`
- `border-radius: 0`
- `box-shadow: none`
- `height: 100%`
- Ring shadow variables reset

This means `<x-bt-button>` and `<x-bt-select>` placed in slots automatically integrate without extra styling.

## Input Mode Detection
The template auto-detects the binding mode:

1. **Livewire**: `wire:model` present → passes through to `<input>`
2. **Alpine external**: `x-model` present → binds to that Alpine property
3. **Alpine local** (default): creates local `x-data` with `value`, `showPassword`, `copySuccess`, `clear()`, `copyToClipboard()`

## Error State Detection
- Auto-reads from Laravel `$errors` bag using `wire:model` name
- Override with `:custom-error="$message"` prop
- Error state: red border, red label, error message below field

## Preset Structure (input.php)
```
colors → {color} → {bg, border, border_error, ring, ring_error, text, placeholder, label, label_error, chip_bg, chip_text, chip_close}
```
No variants — flat color map. Fill mode uses `bg` key, outline mode uses `bg-white dark:bg-gray-900`.

## Livewire Loading
- Auto-detects `wire:*` attributes (except `wire:model`)
- Shows spinner in the end controls area
- Targets inferred from wire attributes or explicit `wire:target`

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-input label="Name" placeholder="Enter name..." />

{{-- Livewire --}}
<x-bt-input wire:model.live="search" label="Search" icon-start="magnifying-glass" />

{{-- Password --}}
<x-bt-input type="password" label="Password" :clearable="false" />

{{-- With copy --}}
<x-bt-input label="API Key" :copy-button="true" :clearable="false" value="sk-123" />

{{-- Color + Fill --}}
<x-bt-input fill blue label="Blue Input" />

{{-- Size --}}
<x-bt-input sm label="Small" />

{{-- Button in end slot --}}
<x-bt-input label="Message" placeholder="Type...">
    <x-slot:end>
        <x-bt-button color="beartropy">Send</x-bt-button>
    </x-slot:end>
</x-bt-input>

{{-- Select in start slot --}}
<x-bt-input label="Phone" placeholder="123-456-7890">
    <x-slot:start>
        <x-bt-select :options="['+1 US', '+44 UK']" placeholder="Code" />
    </x-slot:start>
</x-bt-input>

{{-- Validation error --}}
<x-bt-input wire:model="email" label="Email" />
{{-- Error auto-detected from $errors->first('email') --}}

{{-- Custom error --}}
<x-bt-input label="Code" :custom-error="'Invalid code'" />

{{-- Disabled --}}
<x-bt-input label="Locked" disabled value="Cannot change" />
```

## Config Defaults
```php
'input' => [
    'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
    'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
    'outline' => env('BEARTROPY_UI_INPUT_OUTLINE', true),
],
```

## Key Notes
- `clearable` defaults to `true` — explicitly disable with `:clearable="false"` when using `copy-button` or `type="password"`
- Password type auto-shows eye toggle, no extra prop needed
- `primary` color = neutral gray border + beartropy focus ring (best for general forms)
- Slot components get chrome stripped automatically via CSS — no need for extra classes
- Built-in end controls (clear, copy, password toggle, spinner, icon-end) render in a padded container; custom `$end` slot renders flush outside that container
- `help` and `hint` are aliases — both show text below the field; `help` takes precedence
