# Tooltip

A floating tooltip that appears on hover, rendered via Alpine.js and portaled to `<body>` using `x-teleport`.

## Basic Usage

```blade
<x-bt-tooltip label="More info">
    <span>Hover me</span>
</x-bt-tooltip>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string\|null` | `null` | Text displayed inside the tooltip |
| `delay` | `int\|null` | `0` | Delay in milliseconds before showing |
| `position` | `string\|null` | `'right'` | Position relative to trigger: `top`, `bottom`, `left`, `right` |

## Positions

```blade
<x-bt-tooltip label="Above" position="top">Top</x-bt-tooltip>
<x-bt-tooltip label="Below" position="bottom">Bottom</x-bt-tooltip>
<x-bt-tooltip label="Left side" position="left">Left</x-bt-tooltip>
<x-bt-tooltip label="Right side" position="right">Right (default)</x-bt-tooltip>
```

## Delay

Add a delay before the tooltip appears to prevent accidental triggers:

```blade
<x-bt-tooltip label="Delayed tooltip" :delay="300">
    <x-bt-button label="Hover me" />
</x-bt-tooltip>
```

## Slot Content

The default slot wraps the trigger element — anything the user hovers over:

```blade
<x-bt-tooltip label="Edit this item">
    <x-bt-button-icon icon="pencil" />
</x-bt-tooltip>

<x-bt-tooltip label="What's this?">
    <span class="underline decoration-dashed cursor-help">Help text</span>
</x-bt-tooltip>
```

## Dark Mode

The tooltip automatically adapts to dark mode:
- **Light:** dark background (`bg-black/80`) with white text
- **Dark:** light background (`bg-white/90`) with dark text

## Accessibility

The tooltip is triggered by `mouseenter`/`mouseleave` and hides on window scroll. It uses `x-teleport` to portal the tooltip to `<body>` for proper z-index stacking.

Note: The component does not currently add `role="tooltip"` or `aria-describedby` attributes.
