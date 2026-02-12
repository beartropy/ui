# x-bt-avatar — AI Reference

## Component Tag
```blade
<x-bt-avatar src="/photo.jpg" alt="User" />
```

## Architecture
- `Avatar` -> extends `BeartropyComponent`
- Renders through `avatar.blade.php` (single template, no base delegation)
- Color presets: `resources/views/presets/avatar.php` (flat color -> classes, no variants)
- Sizes: global `resources/views/presets/sizes.php` (uses `avatar` key)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| src | `?string` | `null` | `src="/photo.jpg"` |
| alt | `string` | `''` | `alt="Name"` |
| size | `?string` | `null` | `size="lg"` |
| color | `?string` | `null` | `color="red"` |
| initials | `?string` | `null` | `initials="JD"` |
| customSize | `?string` | `null` | `customSize="w-20 h-20"` |

## Magic Attributes (passed as bare attributes)

### Colors (mutually exclusive, default: `beartropy`)
`beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `gray`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

## Slots

| Slot | Description |
|------|-------------|
| default | Custom content inside avatar (replaces default SVG) |
| status | Status indicator positioned at bottom-right corner |

## Fallback Priority
1. `$src` set -> `<img>` with `object-cover` + all preset classes
2. `$initials` set -> `<span>` with initials text
3. Slot has content -> `<span>` with slot content
4. None -> `<span>` with default silhouette SVG

## Preset Structure (avatar.php)
```
colors -> {color} -> {bg, text, border, ring, font}
```
22 flat colors (no variants). Each provides: `bg-{color}-600`, `text-white`, border classes, optional ring, `font-bold`.

## Size Preset (sizes.php -> `avatar` key)
| Size | Classes |
|------|---------|
| xs | `w-6 h-6 text-xs` |
| sm | `w-8 h-8 text-sm` |
| md | `w-10 h-10 text-base` |
| lg | `w-12 h-12 text-lg` |
| xl | `w-16 h-16 text-xl` |

## Template Structure
```
div.flex.items-center.h-full
  div.inline-block.relative
    <img> | <span>initials</span> | <span>slot/svg</span>
    [status slot: span.absolute.bottom-0.right-0]
```

## Common Patterns

```blade
{{-- Image avatar --}}
<x-bt-avatar src="/user.jpg" alt="Jane" />

{{-- Initials with color --}}
<x-bt-avatar red initials="JD" />

{{-- Sized --}}
<x-bt-avatar lg initials="AB" />

{{-- With status indicator --}}
<x-bt-avatar lg src="/user.jpg">
    <x-slot:status>
        <span class="block w-3 h-3 rounded-full bg-green-500 ring-2 ring-white"></span>
    </x-slot:status>
</x-bt-avatar>

{{-- Custom slot content --}}
<x-bt-avatar lg color="blue">
    <x-bt-icon name="user" class="w-6 h-6" />
</x-bt-avatar>

{{-- Custom size --}}
<x-bt-avatar customSize="w-20 h-20 text-2xl" initials="XX" />

{{-- Dynamic color --}}
<x-bt-avatar :color="$user->avatar_color" :initials="$user->initials" />
```

## Key Notes
- No Alpine/JS — purely server-rendered
- All three render branches (img, initials, default) use the same `$classes` string built from presets
- `$attributes->merge()` is used on all branches so custom classes/attributes propagate
- `customSize` overrides the size preset's `avatar` key but preserves color classes
- No `label` prop — use `initials` for text content
- No config defaults in `component_defaults` — color falls back to first preset key (`beartropy`), size falls back to `md`
