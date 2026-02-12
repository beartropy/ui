# x-bt-option — AI Reference

## Component Tag
```blade
<x-bt-option value="AR" label="Argentina" />
<x-bt-option value="US" label="United States" icon="flag" avatar="/us.png" description="North America" />
```

## Architecture
- `Option` extends `Illuminate\View\Component` (NOT BeartropyComponent — no presets needed)
- Data-only component — renders nothing (`fn () => ''`)
- Pushes normalized option data to `Select::$pendingSlotOptions[]` during constructor
- Must be used inside `<x-bt-select>` slot — Select reads `$pendingSlotOptions` after slot evaluation

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|--------------------|
| value | `string` | (required) | `value="AR"` |
| label | `?string` | same as value | `label="Argentina"` |
| icon | `?string` | `null` | `icon="flag"` |
| avatar | `?string` | `null` | `avatar="/img.jpg"` |
| description | `?string` | `null` | `description="South America"` |

## Internal Mechanics
```php
// In constructor:
Select::$pendingSlotOptions[] = [
    '_value'      => $this->value,
    'label'       => $this->label ?? $this->value,
    'icon'        => Select::renderIcon($this->icon),
    'avatar'      => $this->avatar,
    'description' => $this->description,
];
```

## Common Patterns

```blade
{{-- Basic options --}}
<x-bt-select name="color">
    <x-bt-option value="red" label="Red" />
    <x-bt-option value="blue" label="Blue" />
</x-bt-select>

{{-- With icons --}}
<x-bt-select name="status">
    <x-bt-option value="active" label="Active" icon="check-circle" />
    <x-bt-option value="inactive" label="Inactive" icon="x-circle" />
</x-bt-select>

{{-- Rich options --}}
<x-bt-select name="user">
    <x-bt-option value="1" label="Ana" avatar="/ana.jpg" description="Admin" />
    <x-bt-option value="2" label="Carlos" avatar="/carlos.jpg" description="Editor" />
</x-bt-select>
```

## Key Notes
- Extends `Component` directly, NOT `BeartropyComponent` (no presets, no traits)
- Renders nothing — `render()` returns a closure that returns empty string
- `Select::renderIcon()` normalizes icon (Heroicon name → rendered SVG, emoji → raw)
- `$pendingSlotOptions` is a static array on Select, cleared before each render
- The `_value` key uses underscore prefix to distinguish from display `label`
