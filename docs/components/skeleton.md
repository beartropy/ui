# Skeleton

A loading placeholder that shows animated shapes while content is being fetched. Designed for Livewire components with `wire:init` or `wire:loading` states.

## Basic Usage

```blade
<x-bt-skeleton init="loadData">
    <p>Content loaded!</p>
</x-bt-skeleton>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `init` | `string\|null` | `null` | Livewire `wire:init` method name |
| `lines` | `int` | `1` | Number of placeholder lines |
| `rounded` | `string` | `lg` | Border radius: `none`, `sm`, `md`, `lg`, `xl`, `full` |
| `tag` | `string` | `div` | HTML wrapper element |
| `shape` | `string\|null` | `card` | Shape: `card`, `rectangle`, `image`, `table`, `none` |
| `rows` | `int\|null` | `null` | Number of rows (table shape) |
| `cols` | `int\|null` | `null` | Number of columns (table shape) |

## Shapes

### Card (default)

Shows a title bar and body lines mimicking a card layout:

```blade
<x-bt-skeleton shape="card" :lines="3" init="loadCard">
    <x-bt-card title="Loaded">Real content</x-bt-card>
</x-bt-skeleton>
```

### Rectangle

A simple solid block — good for images or generic placeholders:

```blade
<x-bt-skeleton shape="rectangle" class="w-64 h-32" init="loadBlock">
    <img src="photo.jpg" />
</x-bt-skeleton>
```

### Image

A block with an image icon placeholder inside:

```blade
<x-bt-skeleton shape="image" class="w-full h-48" init="loadImage">
    <img src="photo.jpg" />
</x-bt-skeleton>
```

### Table

A grid with a header row and data rows:

```blade
<x-bt-skeleton shape="table" :rows="5" :cols="4" init="loadTable">
    <table>...</table>
</x-bt-skeleton>
```

### None

Plain text-style lines with varying widths, or a single minimal block:

```blade
{{-- Multiple lines --}}
<x-bt-skeleton shape="none" :lines="3" init="loadText">
    <p>Loaded text</p>
</x-bt-skeleton>

{{-- Single line fallback --}}
<x-bt-skeleton shape="none" :lines="1" init="loadLine">
    <span>Done</span>
</x-bt-skeleton>
```

## Without wire:init

The skeleton can be used without `init` when loading is triggered by other Livewire actions:

```blade
<x-bt-skeleton>
    <p>Content shown when not loading</p>
</x-bt-skeleton>
```

## Rounded Variants

```blade
<x-bt-skeleton rounded="none" />
<x-bt-skeleton rounded="sm" />
<x-bt-skeleton rounded="md" />
<x-bt-skeleton rounded="lg" />  {{-- default --}}
<x-bt-skeleton rounded="xl" />
<x-bt-skeleton rounded="full" />
```

## Custom Tag

```blade
<x-bt-skeleton tag="span" init="load">
    <span>Loaded</span>
</x-bt-skeleton>
```

## Height Detection

When no height class (`h-*`, `min-h-*`, `max-h-*`) is provided and `lines=1`, a fallback `min-h-[0.75rem]` is applied so the skeleton remains visible. Providing your own height class suppresses this fallback.

## Slots

| Slot | Description |
|------|-------------|
| default | Content shown after loading completes (`wire:loading.remove`) |

## How It Works

1. The wrapper element shows `wire:loading` content (the animated skeleton shapes) while Livewire is loading.
2. Once loading finishes, the skeleton hides and the `wire:loading.remove` div reveals the slot content.
3. If `init` is provided, `wire:init` triggers the initial load automatically.

## Dark Mode

All shapes include dark mode styles automatically using `dark:bg-slate-*` variants.
