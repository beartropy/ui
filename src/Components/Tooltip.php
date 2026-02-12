<?php

namespace Beartropy\Ui\Components;

/**
 * Tooltip component.
 *
 * Renders a floating tooltip on hover via Alpine.js and `x-teleport` to portal
 * the tooltip panel to `<body>`. Supports four positions (top, bottom, left,
 * right) and a configurable show-delay in milliseconds.
 *
 * @property string|null $label    Tooltip text displayed inside the floating panel.
 * @property int|null    $delay    Delay in ms before showing (default 0).
 * @property string|null $position Position relative to trigger: top, bottom, left, right (default right).
 */
class Tooltip extends BeartropyComponent
{
    /**
     * Create a new Tooltip component instance.
     *
     * @param string|null $label    Label.
     * @param int|null    $delay    Delay.
     * @param string|null $position Position.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Trigger content.
     */
    public function __construct(
        public ?string $label = null,
        public ?int $delay = 0,
        public ?string $position = 'right',
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::tooltip');
    }
}
