# Avatar

Displays a user avatar as an image, initials, or a default silhouette SVG.

## Basic Usage

```blade
<x-bt-avatar src="/avatar.jpg" alt="Jane Doe" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `src` | `string\|null` | `null` | Image source URL |
| `alt` | `string` | `''` | Alt text for the image |
| `size` | `string\|null` | `null` | Size preset (or use magic attribute) |
| `color` | `string\|null` | `null` | Color preset (or use magic attribute) |
| `initials` | `string\|null` | `null` | Fallback initials when no image |
| `customSize` | `string\|null` | `null` | Custom Tailwind size classes (e.g. `w-20 h-20`) |

## Sizes

```blade
<x-bt-avatar xs initials="XS" />
<x-bt-avatar sm initials="SM" />
<x-bt-avatar md initials="MD" />  {{-- default --}}
<x-bt-avatar lg initials="LG" />
<x-bt-avatar xl initials="XL" />
```

## Colors

Set the color using a magic attribute or the `color` prop:

```blade
<x-bt-avatar red initials="R" />
<x-bt-avatar blue initials="B" />
<x-bt-avatar green initials="G" />
<x-bt-avatar :color="$dynamicColor" initials="D" />
```

All 22 colors: `beartropy`, `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`, `gray`, `slate`, `stone`, `zinc`, `neutral`.

## Fallback Priority

1. **Image** (`src`) — renders `<img>` tag
2. **Initials** (`initials`) — renders text in a `<span>`
3. **Custom slot** — renders slot content in a `<span>`
4. **Default SVG** — renders a silhouette icon

## Slots

```blade
{{-- Default slot: custom content inside the avatar --}}
<x-bt-avatar lg color="blue">
    <x-bt-icon name="user" class="w-6 h-6" />
</x-bt-avatar>

{{-- Status slot: indicator positioned at bottom-right --}}
<x-bt-avatar lg initials="JD">
    <x-slot:status>
        <span class="block w-3 h-3 rounded-full bg-green-500 ring-2 ring-white"></span>
    </x-slot:status>
</x-bt-avatar>
```

## Custom Size

Override the size preset with arbitrary Tailwind classes:

```blade
<x-bt-avatar customSize="w-20 h-20 text-2xl" initials="XX" />
```

## Dark Mode

Color presets include dark mode styles automatically via `dark:` border classes.
