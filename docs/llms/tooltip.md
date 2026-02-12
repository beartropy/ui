# x-bt-tooltip — AI Reference

## Component Tag
```blade
<x-bt-tooltip label="Help text">Trigger content</x-bt-tooltip>
```

## Architecture
- `Tooltip` → extends `BeartropyComponent`
- Renders through `tooltip.blade.php`
- No presets — styling is hardcoded in the template
- Uses Alpine.js `x-data` for state + `x-teleport="body"` for portal rendering

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| label | `?string` | `null` | `label="Help"` |
| delay | `?int` | `0` | `:delay="300"` |
| position | `?string` | `'right'` | `position="top"` |

## Slots

| Slot | Description |
|------|-------------|
| default | Trigger content that the user hovers over |

## Alpine State Shape
```js
{
    show: false,       // tooltip visibility
    top: 0,            // computed top position (px)
    left: 0,           // computed left position (px)
    ready: false,      // prevents flash before position is calculated
    timeout: null,     // delay timer reference
}
```

## Positioning Logic
- `calculatePosition()` reads trigger's `getBoundingClientRect()` + scroll offsets
- `transform()` returns CSS transform string based on `$position`:
  - `top`: `translateX(-50%) translateY(-100%)`
  - `bottom`: `translateX(-50%)`
  - `left`: `translateX(-100%) translateY(-50%)`
  - `right`: `translateY(-50%)`
- Panel styled with `position: absolute` via `:style` binding

## Tooltip Panel Classes
```
absolute z-[9999] px-3 py-1.5 text-xs rounded pointer-events-none
whitespace-nowrap overflow-hidden backdrop-blur-sm shadow-lg
text-white bg-black/80
dark:text-slate-800 dark:bg-white/90 dark:font-semibold
```

## Event Handlers
- `@mouseenter="showTooltip()"` — starts delay timer, then shows
- `@mouseleave="hideTooltip()"` — hides immediately, resets `ready` after 300ms
- `@scroll.window="hideTooltip()"` — hides on scroll

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-tooltip label="More info">
    <span>Hover me</span>
</x-bt-tooltip>

{{-- Position --}}
<x-bt-tooltip label="Above" position="top">Top</x-bt-tooltip>

{{-- With delay --}}
<x-bt-tooltip label="Delayed" :delay="500">Trigger</x-bt-tooltip>

{{-- On icon button --}}
<x-bt-tooltip label="Edit">
    <x-bt-button-icon icon="pencil" />
</x-bt-tooltip>
```

## Key Notes
- Default position is `right` (not `top` as in most tooltip libraries)
- No `role="tooltip"` or `aria-describedby` — hover-only interaction
- Panel is portaled to `<body>` via `x-teleport`, z-index `9999`
- `ready` flag prevents a flash of unstyled tooltip before position calculation
- Trigger wrapper has `cursor-help` class
- No preset system — single hardcoded dark/light style
