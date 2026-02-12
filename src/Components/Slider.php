<?php

namespace Beartropy\Ui\Components;

/**
 * Slider (slide-over panel) component.
 *
 * Renders a side panel that slides in from the left or right edge of the
 * viewport. Uses Alpine.js for state, `x-trap` for focus trapping, and
 * supports both event-based (`$dispatch('open-slider', 'name')`) and
 * Livewire (`wire:model`) control.
 *
 * @property bool        $show          Initial visibility (usually controlled via wire:model).
 * @property string|null $name          Unique name for event-based triggers (open/close/toggle-slider).
 * @property string|null $color         Color preset for close button styling.
 * @property string|null $title         Panel header title text.
 * @property string      $side          Side to slide from: 'left' or 'right'.
 * @property bool        $backdrop      Show semi-transparent backdrop overlay.
 * @property bool        $blur          Apply blur effect to the backdrop.
 * @property string      $maxWidth      Tailwind max-width classes for the panel.
 * @property string      $headerPadding Tailwind padding classes for the header.
 * @property string      $bodyPadding   Tailwind padding classes for the body.
 * @property bool        $static        When true, backdrop click does not close the slider.
 */
class Slider extends BeartropyComponent
{
    /**
     * Create a new Slider component instance.
     *
     * @param bool        $show          Initial visibility.
     * @param string|null $name          Unique name for event-based triggers.
     * @param string|null $color         Color preset.
     * @param string|null $title         Header title text.
     * @param string      $side          Slide direction (left or right).
     * @param bool        $backdrop      Has backdrop.
     * @param bool        $blur          Backdrop blur.
     * @param string      $maxWidth      Max width classes.
     * @param string      $headerPadding Header padding classes.
     * @param string      $bodyPadding   Body padding classes.
     * @param bool        $static        Static mode (no backdrop dismiss).
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Slider body content.
     * @slot footer  Slider footer content (sticky bottom bar).
     */
    public function __construct(
        public bool $show = false,
        public ?string $name = null,
        public ?string $color = null,
        public ?string $title = null,
        public string $side = 'right',
        public bool $backdrop = true,
        public bool $blur = true,
        public string $maxWidth = 'max-w-xl 2xl:max-w-4xl',
        public string $headerPadding = 'px-4 py-3 sm:px-6',
        public string $bodyPadding = 'p-4',
        public bool $static = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::slider');
    }
}
