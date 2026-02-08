# x-bt-tag — AI Reference

## Component Tag
```blade
<x-bt-tag />
```

## Architecture
- `Tag` → extends `BeartropyComponent` (no intermediate base class)
- Renders: `tag.blade.php`
- Presets: shares `resources/views/presets/input.php` (calls `$getComponentPresets('input')`)
- Sizes: global `resources/views/presets/sizes.php`
- JS: `beartropyTagInput` Alpine component (`resources/js/modules/tag-input.js`)
- i18n: `lang/en/ui.php` key `add_tag`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| id | `?string` | auto `'beartropy-tag-'.uniqid()` | `id="my-tags"` |
| name | `?string` | falls back to `$id` | `name="tags"` |
| label | `?string` | `null` | `label="Tags"` |
| color | `?string` | `null` | `color="blue"` or magic: `blue` |
| size | `?string` | `null` | `size="lg"` or magic: `lg` |
| placeholder | `string` | `__('beartropy-ui::ui.add_tag')` | `placeholder="Type..."` |
| value | `array` | `[]` | `:value="['a','b']"` |
| separator | `array\|string` | `','` | `separator=";"` or `:separator="[',',';']"` |
| disabled | `bool` | `false` | `:disabled="true"` |
| unique | `bool` | `true` | `:unique="false"` |
| maxTags | `?int` | `null` | `:max-tags="5"` |
| help | `?string` | `null` | `help="Help text"` |
| hint | `?string` | `null` | `hint="Hint text"` |
| customError | `mixed` | `null` | `:custom-error="$error"` |

## Magic Attributes

### Colors (mutually exclusive, default: `primary` via config)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

### Sizes (mutually exclusive, default: `md`)
`xs`, `sm`, `md`, `lg`, `xl`

### Fill Mode
`fill` — applies `$colorPreset['bg']` tinted background. Without `fill`, uses `bg-white dark:bg-gray-900`.

## Slots

| Slot | Description |
|------|-------------|
| start | Content before the chips/input area (chrome stripped, vertically stretched) |
| end | Content after the chips/input area (chrome stripped, vertically stretched) |

## Mode Detection
The template auto-detects binding mode:

1. **Livewire**: `wire:model` present → uses `@entangle`, no hidden inputs rendered
2. **Plain form** (default): renders hidden `<input type="hidden" name="name[]">` per tag via Alpine `x-for`

## Alpine Component: `beartropyTagInput`

### Parameters
```js
beartropyTagInput({
    initialTags: [],   // array — initial tags
    unique: true,      // bool — prevent duplicates
    maxTags: null,     // int|null — limit
    disabled: false,   // bool
    separator: ','     // string|array — split chars
})
```

### Reactive Properties
- `tags` — array of current tags
- `input` — current text input value

### Methods
- `focusInput()` — focuses the text input
- `addTag()` — adds current input value (splits on separator)
- `removeTag(i)` — removes tag at index
- `clearAll()` — removes all tags and clears input
- `removeOnBackspace()` — removes last tag when input is empty
- `addTagOnTab(e)` — adds tag on Tab key
- `handlePaste(e)` — splits pasted text on separator
- `_tryAddTag(tag)` — internal: adds tag with unique/max checks

## Error State Detection
- Auto-reads from Laravel `$errors` bag using `wire:model` name
- Override with `:custom-error="$message"` prop
- Error state: red border, red label, error message below field

## Preset Structure (input.php)
```
colors → {color} → {bg, border, border_error, ring, ring_error, text, placeholder, label, label_error, chip_bg, chip_text, chip_close}
```
Tag-specific keys: `chip_bg`, `chip_text`, `chip_close` — used for tag chip styling.

## Hidden Input Form Submission
Without `wire:model`, the Blade template renders:
```blade
<template x-for="(tag, i) in tags" :key="'hidden-'+tag+i">
    <input type="hidden" :name="`{{ $inputName }}[]`" :value="tag">
</template>
```
Server receives `$request->input('name')` as an array.

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-tag label="Tags" name="tags" />

{{-- With initial values --}}
<x-bt-tag label="Skills" name="skills" :value="['PHP', 'Laravel']" />

{{-- Livewire --}}
<x-bt-tag wire:model.live="tags" label="Tags" />

{{-- Limited + unique --}}
<x-bt-tag label="Tags" :max-tags="5" :unique="true" />

{{-- Custom separator --}}
<x-bt-tag label="Tags" separator=";" />

{{-- Color + Size --}}
<x-bt-tag blue sm label="Small Blue Tags" />

{{-- Fill mode --}}
<x-bt-tag fill emerald label="Emerald Tags" />

{{-- Disabled --}}
<x-bt-tag label="Locked" :disabled="true" :value="['readonly']" />

{{-- With help --}}
<x-bt-tag label="Tags" help="Separate with commas" />

{{-- Custom error --}}
<x-bt-tag label="Tags" :custom-error="'At least one tag required'" />

{{-- With slots --}}
<x-bt-tag label="Tags">
    <x-slot:start><x-bt-icon name="tag" class="w-5 h-5 ml-2" /></x-slot:start>
</x-bt-tag>

{{-- Clear all button in end slot --}}
<x-bt-tag label="Tags" :value="['one', 'two']">
    <x-slot:end>
        <x-bt-button color="gray" soft @click.stop="clearAll()">Clear All</x-bt-button>
    </x-slot:end>
</x-bt-tag>

{{-- Form submission --}}
<form method="POST">
    @csrf
    <x-bt-tag name="tags" label="Tags" :value="$existingTags" />
    <x-bt-button type="submit">Save</x-bt-button>
</form>
```

## Config Defaults
Tag shares the `input` config defaults — no separate `component_defaults.tag` entry:
```php
'input' => [
    'color' => env('BEARTROPY_UI_INPUT_COLOR', 'primary'),
    'size' => env('BEARTROPY_UI_INPUT_SIZE', 'md'),
    'outline' => env('BEARTROPY_UI_INPUT_OUTLINE', true),
],
```

## Key Notes
- Tag has no base class — extends `BeartropyComponent` directly (unlike Input which uses InputBase)
- Shares the `input` preset — `$getComponentPresets('input')` — so color/size config from `component_defaults.input` applies
- `id` auto-generates as `'beartropy-tag-' . uniqid()` when not provided; `name` falls back to `id`
- Default placeholder uses i18n: `__('beartropy-ui::ui.add_tag')` → `'Add tag...'`
- `hint` takes precedence over `help` in the field-help component
- Label is safely escaped with `{{ }}` (not `{!! !!}`)
- Hidden inputs only render when `wire:model` is NOT present
- The Alpine component is registered as `beartropyTagInput` (not `tagInput`)
- Chip size scales with the input size preset (`text-xs` for xs/sm, `text-sm` for md/lg, `text-base` for xl)
- Bordered container uses `items-stretch` so start/end slots fill the full height (unlike Input which uses fixed `height` + `items-center`)
- Chips area has `max-h-32 overflow-y-auto` with thin scrollbar for many tags
- `wire:ignore` on the chips container prevents Livewire from interfering with Alpine DOM
- `clearAll()` is available in Alpine scope for slot buttons (e.g., `@click.stop="clearAll()"`)
