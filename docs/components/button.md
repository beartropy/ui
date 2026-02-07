# Button

A versatile button component with multiple variants, colors, sizes, icons, and Livewire integration.

## Basic Usage

```blade
<x-bt-button>Click me</x-bt-button>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Button text (alternative to slot content) |
| `type` | `string\|null` | `null` | HTML button type: `button`, `submit`, `reset` |
| `href` | `string\|null` | `null` | Renders as `<a>` tag instead of `<button>` |
| `disabled` | `bool` | `false` | Disables the button |
| `icon-start` | `string\|null` | `null` | Icon name before the label |
| `icon-end` | `string\|null` | `null` | Icon name after the label |
| `spinner` | `bool` | `true` | Show loading spinner during Livewire actions |
| `icon-set` | `string\|null` | config default | Icon set: `heroicons`, `lucide`, `fontawesome` |
| `icon-variant` | `string\|null` | config default | Icon variant: `outline`, `solid` (Heroicons only) |

## Variants

Set the visual style using a magic attribute:

```blade
<x-bt-button solid>Solid (default)</x-bt-button>
<x-bt-button soft>Soft</x-bt-button>
<x-bt-button outline>Outline</x-bt-button>
<x-bt-button ghost>Ghost</x-bt-button>
<x-bt-button tint>Tint</x-bt-button>
<x-bt-button glass>Glass</x-bt-button>
<x-bt-button gradient>Gradient</x-bt-button>
```

## Colors

Set the color using a magic attribute. Available on all variants:

```blade
<x-bt-button beartropy>Beartropy (default)</x-bt-button>
<x-bt-button red>Red</x-bt-button>
<x-bt-button blue>Blue</x-bt-button>
<x-bt-button green>Green</x-bt-button>
```

All 24 colors: `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

You can also use the `color` prop:

```blade
<x-bt-button :color="$dynamicColor">Dynamic</x-bt-button>
```

## Sizes

```blade
<x-bt-button xs>Extra Small</x-bt-button>
<x-bt-button sm>Small</x-bt-button>
<x-bt-button md>Medium (default)</x-bt-button>
<x-bt-button lg>Large</x-bt-button>
<x-bt-button xl>Extra Large</x-bt-button>
```

## Icons

```blade
{{-- Icon before text --}}
<x-bt-button icon-start="envelope">Send Email</x-bt-button>

{{-- Icon after text --}}
<x-bt-button icon-end="arrow-right">Next</x-bt-button>

{{-- Both icons --}}
<x-bt-button icon-start="cloud-arrow-up" icon-end="check">Upload</x-bt-button>
```

## Link Button

Renders as an `<a>` tag when `href` is provided:

```blade
<x-bt-button href="https://example.com">Visit Site</x-bt-button>
<x-bt-button href="/dashboard" blue>Dashboard</x-bt-button>
```

## Livewire Integration

Buttons automatically detect `wire:click` and show a loading spinner:

```blade
<x-bt-button wire:click="save" green>Save</x-bt-button>
<x-bt-button wire:click="delete" red>Delete</x-bt-button>

{{-- Disable spinner --}}
<x-bt-button wire:click="save" :spinner="false">Save Quietly</x-bt-button>
```

The spinner replaces the button content during loading. The button is also automatically disabled while the action runs.

You can specify a custom loading target:

```blade
<x-bt-button wire:click="save" wire:target="save,validate">Save</x-bt-button>
```

## Slots

```blade
{{-- Default slot: button content --}}
<x-bt-button>Click me</x-bt-button>

{{-- Start/End slots for custom prefix/suffix content --}}
<x-bt-button>
    <x-slot:start>
        <img src="/icon.svg" class="w-4 h-4" />
    </x-slot:start>
    Custom Content
    <x-slot:end>
        <span class="badge">3</span>
    </x-slot:end>
</x-bt-button>
```

## Combining Variant + Color

Mix any variant with any color:

```blade
<x-bt-button soft red>Soft Red</x-bt-button>
<x-bt-button outline blue>Outline Blue</x-bt-button>
<x-bt-button ghost green>Ghost Green</x-bt-button>
<x-bt-button gradient purple>Gradient Purple</x-bt-button>
```

## Disabled State

```blade
<x-bt-button disabled>Disabled</x-bt-button>
<x-bt-button disabled blue>Disabled Blue</x-bt-button>
```

## Configuration

Default color and size can be set in `config/beartropyui.php`:

```php
'component_defaults' => [
    'button' => [
        'color' => env('BEARTROPY_UI_BUTTON_COLOR', 'beartropy'),
        'size' => env('BEARTROPY_UI_BUTTON_SIZE', 'md'),
    ],
],
```

Or via `.env`:

```env
BEARTROPY_UI_BUTTON_COLOR=blue
BEARTROPY_UI_BUTTON_SIZE=sm
```

## Dark Mode

All variants and colors include dark mode styles automatically. No extra configuration needed.
