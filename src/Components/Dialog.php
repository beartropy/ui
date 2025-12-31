<?php

namespace Beartropy\Ui\Components;

/**
 * Dialog Component.
 *
 * Renders a dialog modal.
 */
class Dialog extends BeartropyComponent
{

    /**
     * Create a new Dialog component instance.
     *
     * @param string|null $size Dialog size (default: md).
     */
    public function __construct(
        public ?string $size = 'md'
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::dialog');
    }
}
