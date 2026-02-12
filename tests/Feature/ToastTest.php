<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders as a section with Alpine store initialization', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('<section')
        ->toContain('x-data=')
        ->toContain("Alpine.store('toasts'")
        ->toContain('items: []');
});

it('registers Alpine store with add, remove, grouped, and get methods', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('add(toast)')
        ->toContain('remove(id)')
        ->toContain('grouped()')
        ->toContain('get(id)');
});

it('renders aria-live and role attributes for accessibility', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('role="status"')
        ->toContain('aria-live="polite"');
});

// --- Positions ---

it('uses top-right as default position', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain("toast.position || 'top-right'");
});

it('renders all four position mappings', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("'top-right': 'top-6 right-6'")
        ->toContain("'top-left': 'top-6 left-6'")
        ->toContain("'bottom-right': 'bottom-6 right-6'")
        ->toContain("'bottom-left': 'bottom-6 left-6'");
});

it('passes custom position to the store default', function () {
    $html = Blade::render('<x-bt-toast position="bottom-left" />');

    expect($html)->toContain("toast.position || 'bottom-left'");
});

it('renders per-position desktop containers', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("positions['top-right']")
        ->toContain("positions['top-left']")
        ->toContain("positions['bottom-right']")
        ->toContain("positions['bottom-left']");
});

// --- Mobile & Desktop Layouts ---

it('renders mobile layout hidden on md+', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('md:hidden');
});

it('renders desktop layout visible on md+', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('hidden md:flex');
});

it('renders mobile snackbar centered with max width', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('left-1/2')
        ->toContain('-translate-x-1/2')
        ->toContain('w-[min(92vw,28rem)]');
});

// --- Transition Direction ---

it('renders right-side desktop transitions sliding from right', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('opacity-0 scale-95 translate-x-6');
});

it('renders left-side desktop transitions sliding from left', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('opacity-0 scale-95 -translate-x-6');
});

it('renders mobile transitions with vertical direction', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('opacity-0 translate-y-2 scale-95');
});

// --- Toast Types & Icons ---

it('renders icon conditionals for all four toast types', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("toast.type === 'success'")
        ->toContain("toast.type === 'error'")
        ->toContain("toast.type === 'warning'")
        ->toContain("toast.type === 'info'");
});

it('renders warning icon as a triangle', function () {
    $html = Blade::render('<x-bt-toast />');

    // Warning uses a triangle path, not a circle
    expect($html)->toContain('M12 3L2 21h20L12 3z');
});

it('renders info icon with distinct i-mark', function () {
    $html = Blade::render('<x-bt-toast />');

    // Info has dot at top (cy=8) and line below (y1=11 to y2=16)
    expect($html)
        ->toContain('cx="12" cy="8" r="1"')
        ->toContain('x1="12" y1="11" x2="12" y2="16"');
});

it('renders type-colored icons in both mobile and desktop variants', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('text-green-500')
        ->toContain('text-green-400')
        ->toContain('text-red-500')
        ->toContain('text-red-400')
        ->toContain('text-yellow-500')
        ->toContain('text-yellow-400')
        ->toContain('text-blue-500')
        ->toContain('text-blue-400');
});

// --- Dismiss ---

it('renders close button with localized aria-label', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("Alpine.store('toasts').remove(toast.id)")
        ->toContain('aria-label="Close"');
});

// --- Action Button ---

it('renders action button template', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('toast.action')
        ->toContain('toast.actionUrl')
        ->toContain('underline');
});

// --- Progress Bar ---

it('renders progress bar with x-ref and duration check', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('x-ref="progress"')
        ->toContain('toast.duration > 0');
});

it('renders progress bar with type-based colors for both variants', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("'bg-green-500': toast.type === 'success'")
        ->toContain("'bg-green-400': toast.type === 'success'");
});

// --- Pause/Resume ---

it('supports pause on hover and resume on leave', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('@mouseenter="pause"')
        ->toContain('@mouseleave="resume"')
        ->toContain('pause()')
        ->toContain('resume()');
});

// --- Sticky Toasts ---

it('supports sticky toasts with zero duration', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('isSticky: toast.duration <= 0')
        ->toContain('if (this.isSticky)');
});

// --- Stacking Limit ---

it('limits visible mobile toasts to 3 by default', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('.slice(-3)');
});

it('limits visible desktop toasts to maxVisible', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('.slice(-5)');
});

it('accepts custom maxVisible prop', function () {
    $html = Blade::render('<x-bt-toast :max-visible="8" />');

    expect($html)->toContain('.slice(-8)');
});

// --- Bottom Offset ---

it('renders default bottom offset CSS custom property', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('--bt-prop-bottom-offset: calc(1rem + env(safe-area-inset-bottom))');
});

it('renders custom bottom offset', function () {
    $html = Blade::render('<x-bt-toast bottom-offset="4rem" />');

    expect($html)->toContain('--bt-prop-bottom-offset: calc(4rem + env(safe-area-inset-bottom))');
});

it('uses safe area insets for mobile positioning', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('env(safe-area-inset-bottom)')
        ->toContain('pb-[env(safe-area-inset-bottom)]');
});

// --- Bottom Bar Auto-Detection ---

it('auto-detects bottom bar element', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('measureBottomBar()')
        ->toContain('[data-bottom-bar]')
        ->toContain('--bt-bottom-offset');
});

// --- Livewire Integration ---

it('listens for Livewire beartropy-add-toast event', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain("Livewire.on('beartropy-add-toast'")
        ->toContain("Alpine.store('toasts').add(t)");
});

// --- Event Listener Cleanup ---

it('cleans up resize and bottom-bar listeners on destroy', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('destroy()')
        ->toContain("window.removeEventListener('resize'")
        ->toContain("window.removeEventListener('beartropy:bottom-bar:changed'");
});

// --- Content ---

it('renders title and message text bindings', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('x-text="toast.title"')
        ->toContain('x-text="toast.message"');
});

it('supports single-line toasts without message', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('toast.single')
        ->toContain('font-medium');
});

// --- Grouping ---

it('groups toasts by position', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)
        ->toContain('$store.toasts.grouped()')
        ->toContain('Object.entries');
});
