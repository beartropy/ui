# Slider

A slide-over panel that appears from the left or right edge of the viewport. Ideal for forms, detail views, or secondary content that doesn't need a full page.

## Basic Usage

```blade
{{-- Trigger button --}}
<x-bt-button label="Open" x-data @click="$dispatch('open-slider', 'my-slider')" />

{{-- Slider panel --}}
<x-bt-slider name="my-slider" title="Panel Title">
    Panel content here.
</x-bt-slider>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string\|null` | `null` | Unique name for event-based control |
| `title` | `string\|null` | `null` | Header title text |
| `side` | `string` | `'right'` | Slide direction: `left` or `right` |
| `backdrop` | `bool` | `true` | Show semi-transparent backdrop overlay |
| `blur` | `bool` | `true` | Apply blur effect to the backdrop |
| `static` | `bool` | `false` | When true, clicking the backdrop does not close the slider |
| `max-width` | `string` | `'max-w-xl 2xl:max-w-4xl'` | Tailwind max-width classes |
| `header-padding` | `string` | `'px-4 py-3 sm:px-6'` | Header padding classes |
| `body-padding` | `string` | `'p-4'` | Body padding classes |
| `color` | `string\|null` | `null` | Color preset for close button styling |
| `show` | `bool` | `false` | Initial visibility state |

## Event-Based Control

Use Alpine `$dispatch` to open, close, or toggle a slider by name:

```blade
{{-- Open --}}
<x-bt-button label="Open" x-data @click="$dispatch('open-slider', 'settings')" />

{{-- Close (from inside the slider) --}}
<x-bt-button label="Cancel" x-on:click="$dispatch('close-slider', 'settings')" />

{{-- Toggle --}}
<x-bt-button label="Toggle" x-data @click="$dispatch('toggle-slider', 'settings')" />
```

## Livewire Binding

Bind the slider visibility to a Livewire property:

```blade
<x-bt-slider wire:model="showPanel" title="Settings">
    Content
</x-bt-slider>
```

## Side

```blade
<x-bt-slider name="left-panel" side="left" title="Left Slider">
    Slides in from the left edge.
</x-bt-slider>
```

## Width

Override the default max-width:

```blade
<x-bt-slider name="wide" max-width="max-w-3xl" title="Wide Panel">
    More room for content.
</x-bt-slider>
```

## Static Mode

Prevent the backdrop from closing the slider (useful for critical forms):

```blade
<x-bt-slider name="confirm" :static="true" title="Confirm Action">
    You must explicitly close this slider.
    <x-slot:footer>
        <x-bt-button label="Close" x-on:click="$dispatch('close-slider', 'confirm')" />
    </x-slot:footer>
</x-bt-slider>
```

## No Backdrop

```blade
<x-bt-slider name="subtle" :backdrop="false" title="No Backdrop">
    The page content remains fully visible.
</x-bt-slider>
```

## Slots

```blade
<x-bt-slider name="example" title="Title">
    {{-- Default slot: body content (scrollable) --}}
    Main content goes here.

    {{-- Footer slot: sticky bottom bar --}}
    <x-slot:footer>
        <x-bt-button ghost label="Cancel" x-on:click="$dispatch('close-slider', 'example')" />
        <x-bt-button solid primary label="Save" />
    </x-slot:footer>
</x-bt-slider>
```

## Padding

Customize header and body padding independently:

```blade
<x-bt-slider name="padded" header-padding="p-6" body-padding="p-6 sm:p-8" title="Custom Padding">
    More breathing room.
</x-bt-slider>
```

## Color

Set a color preset to tint the close button's hover and focus ring:

```blade
<x-bt-slider name="branded" color="blue" title="Blue Accent">
    Close button uses blue hover/focus styles.
</x-bt-slider>
```

## Dark Mode

All elements include dark mode styles automatically. No extra configuration needed.

## Accessibility

- `role="dialog"` and `aria-modal="true"` on the container
- `aria-labelledby` linked to the header title
- Focus is trapped inside the slider via `x-trap.noscroll`
- ESC key closes the slider
- Close button has a screen-reader-only label
