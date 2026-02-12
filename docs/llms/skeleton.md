# x-bt-skeleton — AI Reference

## Component Tag
```blade
<x-bt-skeleton init="loadData">Loaded content</x-bt-skeleton>
```

## Architecture
- `Skeleton` → extends `BeartropyComponent`
- Renders through `skeleton.blade.php`
- No presets file — shapes are handled inline in the Blade template
- Uses `wire:loading` / `wire:loading.remove` for visibility toggling

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| init | `?string` | `null` | `init="loadData"` |
| lines | `int` | `1` | `:lines="3"` |
| rounded | `string` | `'lg'` | `rounded="full"` |
| tag | `string` | `'div'` | `tag="span"` |
| shape | `?string` | `'card'` | `shape="table"` |
| rows | `?int` | `null` | `:rows="5"` |
| cols | `?int` | `null` | `:cols="4"` |

## Shapes (5 modes)

### `card` (default)
- Outer div with `p-3`, `animate-pulse`, `bg-slate-200/90`
- `<h3>` title bar (h-4, w-1/2)
- Body `<p>` lines with varying widths (w-full, w-4/5, w-3/5)
- If `lines=1`, renders 3 default body lines (w-3/4, w-4/5, w-full)
- If `lines>1`, renders exactly that many lines

### `rectangle`
- Single solid block: `w-full h-full animate-pulse bg-slate-200/90`
- No internal structure

### `image`
- Outer block + inner `aspect-[4/3]` container
- SVG icon (landscape image placeholder) centered inside

### `table`
- Header row: `$cols` cells with `flex-1`, `mb-1`
- Data rows: `$rows` rows x `$cols` cells
- All cells are `h-3 flex-1 rounded`

### `none`
- If `lines > 1`: stacked lines with varying widths in `space-y-2` flex-col
- If `lines <= 1`: single block with `min-h-[0.75rem]` fallback (suppressed when wrapper has `h-*`/`min-h-*`/`max-h-*` class)

## Rounded Variants
`none` → `rounded-none`, `sm` → `rounded-sm`, `md` → `rounded-md`, `lg` → `rounded-lg` (default), `xl` → `rounded-xl`, `full` → `rounded-full`

## wire:init Behavior
- Only rendered when `init` prop is provided (non-null, non-empty)
- Template checks `@if($method)` where `$method = $init`

## Slot
- Default slot renders inside `<div wire:loading.remove class="w-full h-full">`
- Visible only after loading completes

## Height Fallback Logic
- Template checks wrapper `class` attribute for `h-*`, `min-h-*`, `max-h-*` via regex
- If no height class found AND `lines === 1` → applies `min-h-[0.75rem]`
- Prevents the skeleton from collapsing to zero height

## Common Patterns

```blade
{{-- Card skeleton with wire:init --}}
<x-bt-skeleton init="loadData" :lines="3">
    <x-bt-card>Real content</x-bt-card>
</x-bt-skeleton>

{{-- Without init (triggered by other actions) --}}
<x-bt-skeleton shape="rectangle" class="w-full h-24">
    <p>Loaded</p>
</x-bt-skeleton>

{{-- Table skeleton --}}
<x-bt-skeleton shape="table" :rows="5" :cols="4" init="loadTable">
    <table>...</table>
</x-bt-skeleton>

{{-- Image placeholder --}}
<x-bt-skeleton shape="image" class="w-full h-48" init="loadImage">
    <img src="photo.jpg" />
</x-bt-skeleton>

{{-- Text lines --}}
<x-bt-skeleton shape="none" :lines="3" init="loadText">
    <p>Loaded text</p>
</x-bt-skeleton>

{{-- Custom tag --}}
<x-bt-skeleton tag="span" init="load">Loaded</x-bt-skeleton>

{{-- Custom rounded --}}
<x-bt-skeleton rounded="full" shape="rectangle" class="w-12 h-12" />
```

## Key Notes
- `init` is optional — skeleton works without it for non-`wire:init` loading scenarios
- `rows` and `cols` only matter for `shape="table"`
- `relative` class is always applied to wrapper via `$attributes->class(['relative'])`
- All shapes include dark mode styles (`dark:bg-slate-*`)
- The `$skeletonClass` prop was removed (unused)
