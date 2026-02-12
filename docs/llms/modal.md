# x-bt-modal — AI Reference

## Component Tag
```blade
<x-bt-modal id="my-modal">Content</x-bt-modal>
```

## Architecture
- `Modal` → extends `BeartropyComponent`
- View: `modal.blade.php` → includes `partials/modal-root.blade.php`
- No presets (all config via props)
- Uses Alpine.js for state (x-data, x-show, x-cloak, x-teleport, x-transition)
- Optional Livewire `wire:model` bidirectional sync
- JS module: `resources/js/modules/modal.js` (openModal/closeModal helpers that lowercase IDs)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | `null` | `id="my-modal"` |
| maxWidth | `string` | `'3xl'` | `maxWidth="lg"` |
| zIndex | `string` | `'30'` | `zIndex="50"` |
| blur | `string` | `'none'` | `blur="lg"` |
| bgColor | `string` | `'bg-white dark:bg-gray-900'` | `bgColor="bg-gray-800"` |
| closeOnClickOutside | `bool` | `true` | `:closeOnClickOutside="false"` |
| styled | `bool` | `false` | `:styled="true"` |
| showCloseButton | `bool` | `true` | `:showCloseButton="false"` |
| centered | `bool` | `false` | `:centered="true"` |
| teleport | `bool` | `true` | `:teleport="false"` |
| teleportTarget | `string` | `'body'` | `teleportTarget="#app"` |

## Slots

| Slot | Description |
|------|-------------|
| default | Modal body content |
| title | Title (styled mode: `text-xl font-semibold border-b`) |
| footer | Footer (styled mode: `flex justify-end items-center border-t`) |

## Max Width Map
sm → `max-w-sm`, md → `max-w-md`, lg → `max-w-lg`, xl → `max-w-xl`, 2xl → `max-w-2xl`, 3xl → `max-w-3xl`, 4xl → `max-w-4xl`, 5xl → `max-w-5xl`, 6xl → `max-w-6xl`, 7xl → `max-w-7xl`, full → `max-w-full`

## Blur Map
none → `backdrop-blur-none`, sm → `backdrop-blur-sm`, md → `backdrop-blur-md`, lg → `backdrop-blur-lg`, xl → `backdrop-blur-xl`, 2xl → `backdrop-blur-2xl`, 3xl → `backdrop-blur-3xl`

## Events
- `open-modal-{id}` — dispatched on window to open
- `close-modal-{id}` — dispatched on window to close
- Escape key always closes (`x-on:keydown.escape.window`)

## Modal ID Resolution (priority order)
1. `wire:model` value (if present)
2. `id` prop (if provided)
3. Auto-generated `modal-{uniqid()}`

## wire:model Behavior
- Modal ID = wire:model value
- One-way `$watch` from Livewire → Alpine on init
- `close()` and `openModal()` call `$wire.set()` to sync back
- `wire:model` attribute is excluded from root element via `whereDoesntStartWith`

## Close Button Visibility
Shows when `$styled || $showCloseButton`. Hidden only when both are false.

## Overlay Click
`@click="close()"` on overlay div only when `$closeOnClickOutside` is true.

## Centered vs Default
- Default: `items-start` + `mt-24 sm:mt-32` (top-aligned with margin)
- Centered: `items-center` (no margin)

## Styled Mode Classes
- Wrapper: `p-6`
- Title: `text-xl font-semibold border-b border-gray-200 dark:border-gray-700 pb-3 text-gray-800 dark:text-gray-200`
- Slot: `my-4 text-gray-800 dark:text-gray-200`
- Footer: `flex justify-end items-center border-t border-gray-200 dark:border-gray-700 pt-3`

## JS Module (modal.js)
```js
openModal(id)  // lowercases id, dispatches open-modal-{id}
closeModal(id) // lowercases id, dispatches close-modal-{id}
```
**Important:** JS helpers lowercase the ID before dispatching. Use lowercase IDs to avoid mismatches with Blade-rendered event listeners.

## Common Patterns

```blade
{{-- Basic with Alpine event trigger --}}
<x-bt-button @click="$dispatch('open-modal-confirm')">Open</x-bt-button>
<x-bt-modal id="confirm">Are you sure?</x-bt-modal>

{{-- Livewire sync --}}
<x-bt-modal wire:model="showModal">Livewire-controlled modal</x-bt-modal>

{{-- Styled with title and footer --}}
<x-bt-modal :styled="true" id="settings">
    <x-slot:title>Settings</x-slot:title>
    Form content here.
    <x-slot:footer>
        <x-bt-button @click="$dispatch('close-modal-settings')">Cancel</x-bt-button>
        <x-bt-button solid blue>Save</x-bt-button>
    </x-slot:footer>
</x-bt-modal>

{{-- Persistent (no outside click, no close button, no escape) --}}
<x-bt-modal :closeOnClickOutside="false" :showCloseButton="false" id="loading">
    <x-bt-loading /> Processing...
</x-bt-modal>

{{-- Centered, blurred, custom size --}}
<x-bt-modal :centered="true" blur="lg" maxWidth="xl" id="alert">
    Important message
</x-bt-modal>

{{-- No teleport --}}
<x-bt-modal :teleport="false" id="inline">Inline modal</x-bt-modal>

{{-- Custom background --}}
<x-bt-modal bgColor="bg-gray-800 text-white" id="dark">Dark modal</x-bt-modal>
```

## Key Notes
- No size/color presets — all styling via direct props
- No magic attributes — sizes and blur are string props, not bare attributes
- `${'close-on-click-outside'}` in Blade supports kebab-case attribute alternative
- Teleport default is `body` to avoid z-index stacking issues
- `x-cloak` hides modal until Alpine initializes
- `x-effect` toggles `overflow-hidden` on `<html>` when modal is open
- Modal container has `max-h-[80vh] overflow-y-auto` for long content
