# x-bt-toast — AI Reference

## Component Tag
```blade
<x-bt-toast />
<x-bt-toast position="bottom-left" bottom-offset="64px" :max-visible="8" />
```

## Architecture
- `Toast` → extends `BeartropyComponent`
- Renders through `toast.blade.php` with `partials/toast-item.blade.php` (shared between mobile/desktop)
- No presets — type colors are hardcoded (green/red/yellow/blue)
- Alpine.js store (`$store.toasts`) manages all toast state
- Livewire integration via `HasToasts` trait
- JS integration via `window.$beartropy.toast` (from `resources/js/modules/toast.js`)

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| position | `string` | `'top-right'` | `position="bottom-left"` |
| bottomOffset | `string` | `'1rem'` | `bottom-offset="64px"` |
| maxVisible | `int` | `5` | `:max-visible="8"` |

## Alpine Store Shape (`$store.toasts`)
```js
{
    items: [],                    // array of toast objects
    add(toast) {},                // add toast, assign ID + defaults
    remove(id) {},                // remove by ID
    grouped() {},                 // group items by position → { pos: [toasts] }
    get(id) {},                   // find by ID
}
```

## Toast Object Shape
```js
{
    id: 'uuid',                   // auto-generated UUID
    type: 'success',              // success | error | warning | info
    title: 'Title',               // required
    message: 'Body text',         // optional (empty = single-line mode)
    single: true,                 // true when message is empty
    duration: 4000,               // ms, 0 = sticky
    position: 'top-right',        // override per-toast
    action: 'Undo',               // optional action button label
    actionUrl: '/restore/123',    // optional URL to navigate on action click
}
```

## HasToasts Trait (Livewire)
```php
use Beartropy\Ui\Traits\HasToasts;

// Basic
$this->toast()->success('Title', 'Message', $duration, $position);
$this->toast()->error('Title');

// With action button
$this->toast()->success('Deleted', 'Item removed.', 5000, null, 'Undo', '/restore/123');
```
Method signature: `success(title, message='', duration=4000, position=null, action=null, actionUrl=null)`
Dispatches `beartropy-add-toast` Livewire event with toast payload.

## JS Module (`window.$beartropy.toast`)
```js
window.$beartropy.toast.success('Title', 'Message', duration, position, action, actionUrl);
window.$beartropy.toast('type', 'Title', 'Message', duration, position, action, actionUrl);
```
Tries Alpine store first, falls back to Livewire dispatch.

## Template Structure
```
section[x-data][role="status"][aria-live="polite"]
├── template x-for (mobile)
│   └── div.md:hidden.fixed.z-[100]        ← centered snackbar
│       └── template x-for toast.slice(-3)
│           └── @include toast-item (variant=mobile)
└── @foreach 4 positions (desktop)
    └── div.hidden.md:flex.fixed.z-[100]    ← corner-positioned
        └── template x-for toast.slice(-maxVisible)
            └── @include toast-item (variant=desktop-left|desktop-right)
```

## Toast Item Partial (`partials/toast-item.blade.php`)
Accepts `$variant`: `mobile`, `desktop-right`, or `desktop-left`.

| Aspect | Mobile | Desktop-Right | Desktop-Left |
|--------|--------|---------------|--------------|
| Card | `rounded-2xl`, `shadow-lg`, backdrop-blur | `rounded-xl`, `shadow` | `rounded-xl`, `shadow` |
| Enter | vertical `translate-y-2` | `translate-x-6` | `-translate-x-6` |
| Leave | vertical `translate-y-2` | `translate-x-6` | `-translate-x-6` |
| Icon colors | `-500` | `-400` | `-400` |
| Progress | `h-[2px]`, `-500` | `h-[1px]`, `-400` | `h-[1px]`, `-400` |

## Icons
- **Success**: circle + checkmark (green)
- **Error**: circle + X (red)
- **Warning**: triangle + exclamation mark (yellow) — distinct triangle shape
- **Info**: circle + "i" mark (blue) — dot at top, line below

## Action Button
- Rendered as a `<button>` with underline style
- If `actionUrl` is set, navigates via `window.location.href`
- Always dismisses the toast on click

## Stacking Limit
- Mobile: `slice(-min(maxVisible, 3))` — default 3 visible
- Desktop: `slice(-maxVisible)` — default 5 visible
- Excess toasts remain in store; become visible as others dismiss

## Timer System
- Uses `window.bt_toast_timers` global object to track timeouts per toast ID
- `init()`: starts countdown, animates progress bar width from 100% → 0%
- `pause()`: freezes timer + progress bar on mouseenter
- `resume()`: resumes countdown + animation on mouseleave
- Sticky toasts (`duration <= 0`) skip timer entirely

## Common Patterns

```blade
{{-- Layout: place once --}}
<x-bt-toast />

{{-- Custom position + limit --}}
<x-bt-toast position="bottom-right" :max-visible="3" />
```

```php
// Simple
$this->toast()->success('Saved!');

// With message
$this->toast()->error('Error', 'Could not save.');

// Sticky
$this->toast()->error('Fatal', 'Contact support.', 0);

// With action
$this->toast()->success('Deleted', 'Removed.', 5000, null, 'Undo', '/restore');

// Custom position
$this->toast()->info('Update', 'New version.', 4000, 'bottom-left');
```

## Key Notes
- Singleton component — place once in layout, not per-page
- Mobile ignores `position` — always centered snackbar at bottom
- Desktop uses `@foreach` over 4 positions for correct transition direction
- Left-positioned toasts slide in from left, right from right
- Action button navigates if `actionUrl` is set, always dismisses toast
- `maxVisible` limits rendered toasts; excess remain in store with running timers
- Store init is guarded to prevent double-init
- Event listeners cleaned up in `destroy()`
