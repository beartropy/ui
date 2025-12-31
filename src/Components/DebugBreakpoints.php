<?php

namespace Beartropy\Ui\Components;

/**
 * DebugBreakpoints component.
 *
 * Displays current Tailwind CSS breakpoint in the corner of the screen.
 * Useful for development.
 *
 * @property bool   $expanded Initial expansion state.
 * @property string $env      Environment setting (e.g. 'local').
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
        public $expanded = false,
        public $env = 'local',
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::debug-breakpoints');
    }
}
