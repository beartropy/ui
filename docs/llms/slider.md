# x-bt-slider — AI Reference

## Component Tag
```blade
<x-bt-slider name="my-slider" title="Title">Body</x-bt-slider>
```

## Architecture
- `Slider` → extends `BeartropyComponent`
- Renders through `slider.blade.php`
- Presets: `resources/views/presets/slider.php` (colors only — ring + hover for close button)
- Uses Alpine.js `x-data` for state, `x-trap.noscroll` for focus trapping
- Event-based control via `$dispatch('open-slider', 'name')`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| show | `bool` | `false` | `:show="true"` |
| name | `?string` | `null` | `name="settings"` |
| color | `?string` | `null` | `color="blue"` |
| title | `?string` | `null` | `title="Settings"` |
| side | `string` | `'right'` | `side="left"` |
| backdrop | `bool` | `true` | `:backdrop="false"` |
| blur | `bool` | `true` | `:blur="false"` |
| maxWidth | `string` | `'max-w-xl 2xl:max-w-4xl'` | `max-width="max-w-3xl"` |
| headerPadding | `string` | `'px-4 py-3 sm:px-6'` | `header-padding="p-6"` |
| bodyPadding | `string` | `'p-4'` | `body-padding="p-6"` |
| static | `bool` | `false` | `:static="true"` |

## Slots

| Slot | Description |
|------|-------------|
| default | Scrollable body content |
| footer | Sticky bottom bar (rendered only when provided) |

## Alpine State Shape
```js
{
    show: false,          // visibility (entangled with wire:model if set)
    sliderName: 'name',  // name prop for event matching
    _handlers: {},        // stored event listener refs (for cleanup)
}
```

## Event System
Three window-level custom events, dispatched with the slider's `name` as detail:
- `open-slider` → sets `show = true`
- `close-slider` → sets `show = false`
- `toggle-slider` → toggles `show`

Listeners are registered in `init()` and cleaned up in `destroy()`.

## Livewire Binding
```blade
<x-bt-slider wire:model="showSlider" title="Title">...</x-bt-slider>
```
Uses `@entangle()` when `wire:model` is present.

## Positioning & Animation
- `$side` determines: position class (`left-0`/`right-0`), padding, and translate direction
- Enter: `translate-x-full` → `translate-x-0` (right) or `-translate-x-full` → `translate-x-0` (left)
- Duration: 500ms mobile, 700ms desktop (`sm:duration-700`)
- Backdrop: 500ms opacity fade

## ARIA & Focus
- Root: `role="dialog" aria-modal="true" aria-labelledby="{sliderId}-title"`
- Title: `id="{sliderId}-title"` (unique per slider via name or uniqid)
- Focus trap: `x-trap.noscroll="show"` (Alpine focus plugin, bundled with Livewire 3)
- Close button: `<span class="sr-only">{{ __('beartropy-ui::ui.close') }}</span>`
- ESC: `x-on:keydown.escape.window="show = false"`

## Preset Structure (slider.php)
```
colors → {color} → {ring, hover}
```
- `ring`: focus ring color for close button
- `hover`: hover color for close button icon
- 18 colors available (full Tailwind palette)

## Template Structure
```
div[x-data][role="dialog"]          ← root, z-50, focus trap
├── div.backdrop                    ← fixed overlay, optional
└── div.fixed.inset-0              ← positioning wrapper
    └── div.panel[x-show]          ← slide transition
        └── div.flex.flex-col      ← layout
            ├── div.header         ← title + close button
            ├── div.body           ← scrollable slot content
            └── div.footer?        ← optional sticky footer
```

## Common Patterns

```blade
{{-- Event-based with footer --}}
<x-bt-button label="Open" x-data @click="$dispatch('open-slider', 'form')" />
<x-bt-slider name="form" title="Edit User">
    <x-bt-input label="Name" />
    <x-slot:footer>
        <x-bt-button ghost label="Cancel" x-on:click="$dispatch('close-slider', 'form')" />
        <x-bt-button solid primary label="Save" />
    </x-slot:footer>
</x-bt-slider>

{{-- Left side, static --}}
<x-bt-slider name="nav" side="left" :static="true" title="Navigation">
    Nav links
</x-bt-slider>

{{-- Livewire --}}
<x-bt-slider wire:model="showSettings" title="Settings">
    Settings form
</x-bt-slider>

{{-- Custom widths and padding --}}
<x-bt-slider name="wide" max-width="max-w-3xl" body-padding="p-6" title="Wide">
    Content
</x-bt-slider>
```

## Key Notes
- Event listeners are cleaned up in `destroy()` to prevent memory leaks in Livewire
- `static` mode only prevents backdrop click — ESC key still closes
- Footer has upward shadow (`shadow-[0_-4px...]`) to indicate scrollable content above
- `x-ref="scrollContainer"` exists on body div for potential JS access
- `x-cloak` hides the component until Alpine initializes
- `x-modelable="show"` allows external Alpine `x-model` binding
