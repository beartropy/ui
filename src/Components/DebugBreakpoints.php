<?php

namespace Beartropy\Ui\Components;

/**
 * DebugBreakpoints component.
 *
 * A development utility that displays the current Tailwind CSS breakpoint
 * (XS–2XL) and viewport width in pixels, fixed to the bottom-right corner.
 * Only renders when `app()->environment()` matches the `$env` prop (defaults
 * to `'local'`). An Alpine.js `x-data` block manages expanded/minimized
 * state, persisted to `localStorage('debug_breakpoints_expanded')`. When
 * minimized, shows a small floating red button; when expanded, shows a bar
 * with breakpoint label, pixel width, and a minimize button.
 *
 * @property bool   $expanded Initial expansion state (overridden by localStorage).
 * @property string $env      Environment to render in (default: 'local').
 */
class DebugBreakpoints extends BeartropyComponent
{
    /**
     * Create a new DebugBreakpoints component instance.
     *
     * @param bool   $expanded Initial expansion state.
     * @param string $env      Environment setting.
     *
     * ## Blade Props
     *
     * This component has no significant slots or additional view properties involved in public API.
     */
    public function __construct(
        public bool $expanded = false,
        public string $env = 'local',
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::debug-breakpoints');
    }
}
