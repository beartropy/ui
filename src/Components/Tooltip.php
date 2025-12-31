<?php

namespace Beartropy\Ui\Components;

/**
 * Tooltip component.
 *
 * Renders a tooltip on hover.
 *
 * @property string|null $label    Tooltip text.
 * @property int|null    $delay    Delay in ms.
 * @property string|null $position Position (top, bottom, left, right).
 */
class Tooltip extends BeartropyComponent
{
    /**
     * Create a new Tooltip component instance.
     *
     * @param string|null $label    Label.
     * @param int|null    $delay    Delay.
     * @param string|null $position Position.
     */
    public function __construct(
        public ?string $label = null,
        public ?int $delay = 0,
        public ?string $position = 'right',
    ) {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::tooltip');
    }
}
