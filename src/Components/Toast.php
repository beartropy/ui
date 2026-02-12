<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Toast notification container component.
 *
 * Renders a toast notification system powered by an Alpine.js store. Supports
 * four toast types (success, error, warning, info), auto-dismiss with progress
 * bar, pause-on-hover, sticky toasts, action buttons, and grouped positioning.
 * Provides both mobile (centered snackbar) and desktop (corner-positioned) layouts.
 *
 * Toasts can be triggered from Livewire (via the HasToasts trait) or from
 * JavaScript (via `window.$beartropy.toast`).
 *
 * @property string $position     Default toast position: top-right, top-left, bottom-right, bottom-left.
 * @property string $bottomOffset Bottom offset for mobile layout (CSS value, e.g. '1rem', '64px').
 * @property int    $maxVisible   Maximum number of visible toasts per position.
 */
class Toast extends BeartropyComponent
{
    /**
     * Create a new Toast component instance.
     *
     * @param string $position     Default position for toasts.
     * @param string $bottomOffset Bottom offset for mobile snackbar.
     * @param int    $maxVisible   Max visible toasts per position (overflow is hidden, not removed).
     *
     * ## Blade Props
     *
     * ### Events
     * @see beartropy-add-toast Livewire event to add a new toast.
     */
    public function __construct(
        public string $position = 'top-right',
        public string $bottomOffset = '1rem',
        public int $maxVisible = 5,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::toast');
    }
}
