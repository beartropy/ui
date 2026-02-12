# x-bt-alert — AI Reference

## Component Tag
```blade
<x-bt-alert success>Message</x-bt-alert>
```

## Architecture
- `Alert` → extends `BeartropyComponent`
- Renders through `alert.blade.php` (no base delegation)
- Presets: `resources/views/presets/alert.php` (flat color → classes)
- No size presets (single size only)
- Uses Alpine.js for dismiss (x-data/x-show/x-transition)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| noIcon | `bool` | `false` | `:noIcon="true"` |
| icon | `?string` | `null` | `icon="shield-check"` |
| title | `?string` | `null` | `title="Heading"` |
| dismissible | `bool` | `false` | `:dismissible="true"` |
| class | `string` | `''` | `class="extra"` |
| color | `?string` | `null` | `color="blue"` |

## Magic Attributes (passed as bare attributes)

### Colors (mutually exclusive, default: first in preset = `beartropy`)
Semantic (with preset icon): `beartropy`, `success`, `info`, `warning`, `error`
Named (no preset icon): `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

No size or variant magic attributes.

## Preset Icons
| Color | Icon |
|-------|------|
| success | check-circle |
| info | exclamation-circle |
| warning | exclamation-triangle |
| error | x-circle |
| beartropy | (none) |
| Named colors | (none) |

## Slots

| Slot | Description |
|------|-------------|
| default | Alert body content |

## Preset Structure (alert.php)
```
colors → {color} → {main, content, icon_wrapper, icon_class, title, slot, icon}
```
26 colors, flat (no variants). 5 semantic + 21 named.

## Common Patterns

```blade
{{-- Semantic with preset icon --}}
<x-bt-alert success>Saved.</x-bt-alert>
<x-bt-alert error title="Error">Something went wrong.</x-bt-alert>

{{-- Named color (no icon unless custom) --}}
<x-bt-alert blue>A blue alert.</x-bt-alert>

{{-- Custom icon --}}
<x-bt-alert blue icon="envelope">Email sent.</x-bt-alert>

{{-- No icon --}}
<x-bt-alert :noIcon="true" warning>Plain text.</x-bt-alert>

{{-- Dismissible --}}
<x-bt-alert :dismissible="true" info>Dismiss me.</x-bt-alert>

{{-- Title only --}}
<x-bt-alert success title="Done." />

{{-- Dynamic color --}}
<x-bt-alert :color="$type">{{ $message }}</x-bt-alert>

{{-- Rich content --}}
<x-bt-alert info title="Update">
    <p>New version available.</p>
    <x-bt-button sm blue label="Update" />
</x-bt-alert>
```

## Key Notes
- No `$size` prop — single size, no size preset used
- No variants — flat color map
- Named colors (red, blue, etc.) have empty `icon` string — template correctly skips icon wrapper
- `$dismissible` adds Alpine button with `@click="open = false"` and opacity transition
- `$class` is appended to the preset's `main` classes
