<?php

namespace Beartropy\Ui\Components;

/**
 * Slider component.
 *
 * Renders a slide-over panel.
 *
 * @property bool   $show          Initial show state (usually controlled via wire:model).
 * @property string|null $color    Color preset.
 * @property string $side          Side to slide from (left, right).
 * @property bool   $backdrop      Show backdrop.
 * @property bool   $blur          Blur backdrop.
 * @property string $maxWidth      Max width class.
 * @property string $headerPadding Padding for header.
 * @property bool   $static        Static positioning (no slide animation).
 */
class Slider extends BeartropyComponent
{
    /**
     * Create a new Slider component instance.
     *
     * @param bool   $show          Initial visibility.
     * @param string|null $color    Color preset.
     * @param string $side          Side.
     * @param bool   $backdrop      Has backdrop.
     * @param bool   $blur          Backdrop blur.
     * @param string $maxWidth      Max width.
     * @param string $headerPadding Header padding.
     * @param bool   $static        Is static.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Slider body content.
     * @slot footer  Slider footer content.
     */
    public function __construct(
        public bool $show = false,
        public ?string $color = null,
        public string $side = 'right',
        public bool $backdrop = true,
        public bool $blur = true,
        public string $maxWidth = 'max-w-xl 2xl:max-w-4xl',
        public string $headerPadding = 'px-4 py-3 sm:px-6',
        public bool $static = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::slider');
    }
}
