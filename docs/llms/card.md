# x-bt-card — AI Reference

## Component Tag
```blade
<x-bt-card>Content</x-bt-card>
```

## Architecture
- `Card` → extends `BeartropyComponent`
- Renders through `card.blade.php`
- Presets: `resources/views/presets/card.php` (color → classes)
- No size presets defined

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| title | `?string` | `null` | `title="Title"` |
| footer | `?string` | `null` | `footer="Footer"` |
| color | `?string` | `null` | `color="beartropy"` |
| size | `?string` | `null` | `size="sm"` |
| collapsable | `?bool` | `false` | `:collapsable="true"` |
| noBorder | `?bool` | `false` | `:noBorder="true"` |
| defaultOpen | `?bool` | `true` | `:defaultOpen="false"` |

## Colors (3 presets)
`beartropy`, `modal`, `neutral`

Each preset defines classes for: `wrapper`, `slot`, `title`, `footer`.

### Preset Details
- **beartropy**: Padded (`p-1`/`p-2`), medium weight title, right-aligned footer with gap
- **modal**: More padding (`p-3`), semibold title, footer has own border-top and margin
- **neutral**: Padding (`p-3`) on wrapper only, no slot/title/footer styling

## Slots

| Slot | Description |
|------|-------------|
| default | Card body content |
| footer | Card footer (overrides `footer` prop) |

## Collapsible Behavior
- Requires `title` prop for clickable header
- Title gets `cursor-pointer`, `select-none`, click toggles `open` Alpine state
- Content and footer wrapped in `x-show="open"` + `x-collapse` + `x-cloak`
- Chevron icon rotates 180deg when open
- Border-bottom on title toggles with state

## Wire:target Loading
- Detects `wire:target` attribute → renders spinner overlay
- Overlay: semi-transparent white/dark bg + animated SVG spinner
- Uses `wire:loading.flex` to show only during action

## noBorder
- Adds `border-0 shadow-none` to wrapper
- Default: `border border-gray-200 dark:border-gray-700`

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-card>Content</x-bt-card>

{{-- With title --}}
<x-bt-card title="Settings">Content</x-bt-card>

{{-- With footer slot --}}
<x-bt-card title="Form">
    Content
    <x-slot:footer>
        <x-bt-button wire:click="save" beartropy>Save</x-bt-button>
    </x-slot:footer>
</x-bt-card>

{{-- Collapsible --}}
<x-bt-card title="Details" :collapsable="true">Content</x-bt-card>

{{-- Starts collapsed --}}
<x-bt-card title="Advanced" :collapsable="true" :defaultOpen="false">Content</x-bt-card>

{{-- Borderless --}}
<x-bt-card :noBorder="true">Content</x-bt-card>

{{-- Loading overlay --}}
<x-bt-card wire:target="save" title="Form">Content</x-bt-card>

{{-- Modal preset --}}
<x-bt-card color="modal" title="Confirm">Content</x-bt-card>
```

## Key Notes
- Card has NO magic color attributes (unlike Button/Alert) — use `color` prop only
- Only 3 color presets exist; passing unknown colors falls back to default
- `title` is a string prop, NOT a slot — use raw HTML in the string if needed (`{!! $title !!}`)
- `footer` works as both prop and named slot; slot takes precedence
- `x-data="{}"` is always present even when not collapsable (Alpine requirement)
- `relative` class is always applied to wrapper (needed for wire:target spinner overlay)
