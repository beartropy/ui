# x-bt-dialog — AI Reference

## Component Tag
```blade
<x-bt-dialog />
<x-bt-dialog size="lg" />
```

## Architecture
- `Dialog` → extends `BeartropyComponent`
- View: `dialog.blade.php` (self-contained, no partials)
- Single instance per page — event-driven, no Blade slots
- Alpine module: `resources/js/modules/bt-dialog.js` (`btDialog()` function)
- JS helper: `resources/js/modules/dialog.js` (`dialog()` function, exported from `beartropy-ui.js`)
- Livewire trait: `src/Traits/HasDialogs.php` (dispatches `bt-dialog` event)

## Props (constructor)

| Prop | PHP Type | Default | Description |
|------|----------|---------|-------------|
| size | `?string` | `'md'` | Default panel width — overridable per dialog via event payload |

## Event Flow
1. Livewire component calls `$this->dialog()->success(...)` (or `confirm`, `delete`, etc.)
2. `HasDialogs` trait calls `$this->dispatch('bt-dialog', $payload)`
3. Blade listens via `x-on:bt-dialog.window="openDialog($event.detail)"`
4. Alpine `btDialog.openDialog()` parses payload, sets state, opens dialog
5. Button clicks call `clickAccept()` / `clickReject()` → find Livewire component via `componentId` → call method

## JS Alternative Flow
1. JS calls `dialog.success('Title')` (from `modules/dialog.js`)
2. Dispatches `CustomEvent('bt-dialog', { detail: payload })` on window
3. Same Alpine handler picks it up

## Size Mapping (intentional offset)
Named sizes map to one step wider for readability:
- sm → `max-w-md`, md → `max-w-lg`, lg → `max-w-xl`, xl → `max-w-2xl`, 2xl → `max-w-3xl`

## Alpine State (`btDialog`)
- `isOpen`, `type`, `title`, `description`, `icon`
- `accept` / `reject` — `{ label, method, params }` or null
- `componentId` — Livewire component ID for method calls
- `acceptBusy` / `rejectBusy` — loading states
- `allowOutsideClick`, `allowEscape` — close behavior
- `panelSizeClass` — resolved Tailwind max-width class
- `globalSize` — default size from Blade prop
- `typeStyles` — PHP-generated icon color map
- `buttonColors` — JS-defined single-button color map

## Type Styles (passed from PHP)
info, success, warning, error, confirm, danger — each with `iconBg` and `iconText` classes.

## HasDialogs Trait Methods
| Method | Signature | Type |
|--------|-----------|------|
| `dialog()` | `(): static` | Fluent accessor |
| `success()` | `(string $title, ?string $description, array $options)` | Alert |
| `info()` | `(string $title, ?string $description, array $options)` | Alert |
| `warning()` | `(string $title, ?string $description, array $options)` | Alert |
| `error()` | `(string $title, ?string $description, array $options)` | Alert |
| `confirm()` | `(array $config)` | Two-button confirm |
| `delete()` | `(string $title, ?string $description, array $options)` | Danger confirm |

## JS Helper API (`dialog`)
```js
dialog.success(title, description?, options?)
dialog.info(title, description?, options?)
dialog.warning(title, description?, options?)
dialog.error(title, description?, options?)
dialog.confirm({ title, description?, accept, reject, ... })
dialog.delete(title, description?, { method, params, componentId, ... })
```

## Localization Keys Used
- `beartropy-ui::ui.ok` — accept button fallback, single-button text
- `beartropy-ui::ui.cancel` — reject button fallback
- `beartropy-ui::ui.confirm` — confirm accept button fallback
- `beartropy-ui::ui.delete` — delete accept button fallback
- `beartropy-ui::ui.are_you_sure` — confirm title fallback

## Key Blade Markers for Testing
- `btDialog(` — Alpine init
- `role="dialog"`, `aria-modal="true"` — accessibility
- `x-cloak`, `x-show="isOpen"` — visibility
- `globalSize: "..."` — size prop passthrough
- `x-trap.noscroll.inert="isOpen"` — focus trap
- `x-on:bt-dialog.window` — event listener
- `z-[9999]` — stacking
- `md:pt-[18vh]` — desktop offset
- `clickAccept()`, `clickReject()` — button handlers
- `isSingleButton`, `buttonColors` — single-button mode
