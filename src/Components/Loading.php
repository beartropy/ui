<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;

/**
 * Loading component.
 *
 * Wrapper around Livewire loading indicators.
 */
class Loading extends Component
{
    /**
     * Create a new Loading component instance.
     *
     * @param string|null $customView Custom view path.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Content (unused in standard loading).
     */
    public function __construct(
        public ?string $customView = null,
    ) {}

    public function render(): string
    {
        return <<<'blade'
            <livewire:loading :customView="$customView" />
        blade;
    }
}
