# Icon Component

The `<x-bt-icon />` component renders icons from multiple icon sets: Heroicons (default), Beartropy SVG, FontAwesome, and Lucide.

## Basic Usage

```blade
<x-bt-icon name="home" />
<x-bt-icon name="bell" class="w-6 h-6 text-blue-500" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | *required* | Icon name (e.g. `home`, `heroicon-s-home`). |
| `size` | `string\|null` | `null` | Size preset key (`xs`, `sm`, `md`, `lg`, `xl`). |
| `class` | `string` | `''` | Additional CSS classes (Tailwind colors, sizing, etc.). |
| `solid` | `bool` | `false` | Force solid variant. |
| `outline` | `bool` | `false` | Force outline variant. |
| `set` | `string\|null` | config default | Icon set: `heroicons`, `lucide`, `fontawesome`, `beartropy`. |
| `variant` | `string\|null` | config default | Variant: `solid` or `outline`. |

## Variants (Solid / Outline)

By default, icons render in the **outline** variant. Switch to solid using any of these approaches:

```blade
{{-- Boolean prop --}}
<x-bt-icon name="heart" :solid="true" />

{{-- Variant prop --}}
<x-bt-icon name="heart" variant="solid" />

{{-- Heroicon prefix --}}
<x-bt-icon name="heroicon-s-heart" />
<x-bt-icon name="heroicon-o-heart" />
```

## Size Presets

Use magic attributes to apply size presets from the sizes configuration:

```blade
<x-bt-icon name="star" xs />  {{-- w-3 h-3 --}}
<x-bt-icon name="star" sm />  {{-- w-4 h-4 --}}
<x-bt-icon name="star" />     {{-- w-5 h-5 (md default) --}}
<x-bt-icon name="star" lg />  {{-- w-6 h-6 --}}
<x-bt-icon name="star" xl />  {{-- w-7 h-7 --}}
```

Or use Tailwind classes directly:

```blade
<x-bt-icon name="star" class="w-10 h-10" />
```

## Colors

Colors are applied via Tailwind classes on the `class` prop (no color presets):

```blade
<x-bt-icon name="heart" class="w-6 h-6 text-red-500" />
<x-bt-icon name="check-circle" class="w-6 h-6 text-green-500" />
<x-bt-icon name="exclamation-triangle" class="w-6 h-6 text-yellow-500" />
```

## Icon Sets

### Heroicons (default)

```blade
<x-bt-icon name="home" />
<x-bt-icon name="home" :solid="true" />
```

### Beartropy SVG

Custom SVG icons: `search`, `check`, `x-mark`, `edit`, `trash`, `eye`, `eye-slash`, `calendar`, `clock`, `clipboard`, `upload`, `spinner`, `paper-airplane-right`, `floppy-disk`, `sync`.

```blade
<x-bt-icon name="search" set="beartropy" />
<x-bt-icon name="calendar" set="beartropy" />
```

### FontAwesome

Renders an `<i>` tag with FontAwesome classes:

```blade
<x-bt-icon name="fa-solid fa-house" set="fontawesome" />
```

### Lucide

```blade
<x-bt-icon name="home" set="lucide" />
```

## Fallback

If the icon set is not recognized, a red `?` is rendered:

```blade
<x-bt-icon name="foo" set="unknown" />
{{-- Renders: <span class="text-red-600">?</span> --}}
```

## Configuration

Default icon set and variant are configured in `config/beartropyui.php`:

```php
'icons' => [
    'set' => 'heroicons',
    'variant' => 'outline',
],
```
