# x-bt-chat-input — AI Reference

## Component Tag
```blade
<x-bt-chat-input wire:model="message" />
<x-bt-chat-input wire:model="message" action="sendMessage" :stacked="true" />
```

## Architecture
- `ChatInput` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`, `HasWireModel`)
- Renders `chat-input.blade.php` — Alpine.js `beartropyChatInput()` component
- Preset file: `presets/chat-input.php` (colors: wrapper, input, label, footer, border, error states)
- Auto-resizing textarea with `field-sizing: content` + JS fallback
- Supports Livewire `wire:model` and `@entangle` for two-way binding

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|--------------------|
| id | `?string` | auto-generated | `id="my-chat"` |
| name | `?string` | same as id | `name="message"` |
| label | `?string` | `null` | `label="Message"` |
| color | `?string` | `null` | `color="blue"` |
| placeholder | `string` | `__('beartropy-ui::ui.type_message')` | `placeholder="Ask..."` |
| disabled | `bool` | `false` | `:disabled="true"` |
| readonly | `bool` | `false` | `:readonly="true"` |
| required | `bool` | `false` | `:required="true"` |
| help | `?string` | `null` | `help="Helper text"` |
| hint | `?string` | `null` | `hint="Hint"` |
| customError | `mixed` | `null` | `:customError="$error"` |
| maxLength | `?int` | `null` | `:maxLength="500"` |
| stacked | `bool` | `false` | `:stacked="true"` |
| submitOnEnter | `bool` | `true` | `:submitOnEnter="false"` |
| action | `?string` | `null` | `action="sendMessage"` |
| border | `bool` | `false` | `:border="true"` |

## Slots
| Slot | Description |
|------|-------------|
| tools | Left-side tool buttons (e.g., attach file) |
| footer / actions | Action buttons area (submit button, etc.) — aliases |

## Alpine.js State (`beartropyChatInput`)
```js
{
    val: string,        // Textarea value (entangled with wire:model)
    isSingleLine: bool, // Auto-switches based on content
    stacked: bool,      // Stacked layout mode
    action: ?string,    // Livewire action name
    submitOnEnter: bool,

    resize(),           // Auto-resize textarea
    handleEnter(event), // Submit on Enter, newline on Shift+Enter
}
```

## Template Structure
```
div
├── label[for] (conditional)
├── div[x-data="beartropyChatInput(...)"][x-cloak]
│   ├── div.tools (slot, conditional)
│   ├── textarea[x-model="val"][x-ref="textarea"]
│   └── div.footer/actions (slot, conditional)
└── x-beartropy-ui::support.field-help
```

## Layout Modes
- **Single-line** (default): `grid-cols-[auto_1fr_auto]` — tools | textarea | actions in one row
- **Stacked**: `grid-cols-2` — textarea spans full width, tools/actions wrap below
- Auto-switches from single to stacked when textarea grows beyond one line

## Common Patterns

```blade
{{-- Basic with Livewire --}}
<x-bt-chat-input wire:model="message" action="send" />

{{-- With tools and actions --}}
<x-bt-chat-input wire:model="message">
    <x-slot:tools>
        <x-bt-button-icon icon="paper-clip" ghost />
    </x-slot:tools>
    <x-slot:actions>
        <x-bt-button-icon icon="paper-airplane" solid beartropy />
    </x-slot:actions>
</x-bt-chat-input>

{{-- Stacked with max length --}}
<x-bt-chat-input wire:model="message" :stacked="true" :maxLength="1000" />
```

## Key Notes
- Textarea uses `field-sizing: content` CSS for native auto-resize
- `beartropyChatInput()` is registered via Alpine.data in the JS bundle
- Enter submits by default; Shift+Enter adds newline
- `action` prop fires `$wire.call(action)` instead of native form submit
- Error state integrates with Laravel validation via `HasErrorBag` trait
