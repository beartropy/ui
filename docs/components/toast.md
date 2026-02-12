# Toast

A floating notification system for success, error, warning, and info messages. Supports auto-dismiss with progress bar, pause-on-hover, sticky toasts, action buttons, and responsive layouts (mobile snackbar + desktop corner positions).

## Setup

Place the toast container once in your layout (typically in `layouts/app.blade.php`):

```blade
<x-bt-toast />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `position` | `string` | `'top-right'` | Default position: `top-right`, `top-left`, `bottom-right`, `bottom-left` |
| `bottom-offset` | `string` | `'1rem'` | Bottom offset for mobile snackbar (CSS value) |
| `max-visible` | `int` | `5` | Maximum visible toasts per position (overflow hidden, not removed) |

## Triggering from Livewire

Use the `HasToasts` trait in your Livewire component:

```php
use Beartropy\Ui\Traits\HasToasts;

class MyComponent extends Component
{
    use HasToasts;

    public function save(): void
    {
        // ... save logic

        $this->toast()->success('Saved!', 'Your changes have been saved.');
    }
}
```

### Toast Types

```php
$this->toast()->success('Title', 'Optional message');
$this->toast()->error('Title', 'Optional message');
$this->toast()->warning('Title', 'Optional message');
$this->toast()->info('Title', 'Optional message');
```

### Title-Only (Single Line)

```php
$this->toast()->success('Saved!');
$this->toast()->info('Copied to clipboard');
```

### Custom Duration

```php
// 8 seconds
$this->toast()->success('Done', 'Completed.', 8000);

// Sticky (no auto-dismiss)
$this->toast()->error('Critical error', 'Please contact support.', 0);
```

### Custom Position

```php
$this->toast()->info('Update', 'New version available.', 4000, 'bottom-left');
```

### Action Button

Add an action button with an optional URL:

```php
// Action with URL — navigates on click
$this->toast()->success('Deleted', 'Item removed.', 5000, null, 'Undo', '/items/restore/123');

// Action without URL — just closes the toast
$this->toast()->info('Exported', 'Report is ready.', 5000, null, 'Download');
```

## Triggering from JavaScript

```js
// Via the global helper
window.$beartropy.toast.success('Saved!');
window.$beartropy.toast.error('Error', 'Something went wrong.');
window.$beartropy.toast.warning('Warning', 'Check your input.', 6000);
window.$beartropy.toast.info('Info', 'New update.', 4000, 'bottom-right');

// With action button
window.$beartropy.toast.success('Deleted', 'Item removed.', 5000, 'top-right', 'Undo', '/restore');

// Or the base function
window.$beartropy.toast('success', 'Title', 'Message', 4000, 'top-right', 'Action', '/url');
```

## Positions

```blade
{{-- Default: top-right --}}
<x-bt-toast />

{{-- Bottom-left --}}
<x-bt-toast position="bottom-left" />
```

On mobile (`< md`), toasts always appear as a centered snackbar at the bottom, regardless of the `position` prop. Desktop toasts slide in from the correct direction based on their position (left positions slide from left, right from right).

## Stacking Limit

By default, a maximum of 5 toasts are visible per position (3 on mobile). Excess toasts remain in the store and become visible as others dismiss.

```blade
{{-- Show up to 8 toasts --}}
<x-bt-toast :max-visible="8" />
```

## Bottom Offset

Adjust mobile snackbar position to account for bottom navigation bars:

```blade
<x-bt-toast bottom-offset="64px" />
```

The component also auto-detects elements with a `data-bottom-bar` attribute and adjusts positioning automatically.

## Sticky Toasts

Pass `0` as the duration to create a toast that stays until manually dismissed:

```php
$this->toast()->error('Connection lost', 'Please check your network.', 0);
```

## Pause on Hover

Hovering over a toast pauses the auto-dismiss timer. The progress bar freezes in place and resumes when the mouse leaves.

## Dark Mode

All toast styles include dark mode variants automatically.

## Accessibility

- Container has `role="status"` and `aria-live="polite"` for screen reader announcements
- Close button has a localized `aria-label`
- Warning icon uses a distinct triangle shape; info uses a circled "i" mark
