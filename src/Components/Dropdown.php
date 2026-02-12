<?php

namespace Beartropy\Ui\Components;

/**
 * Dropdown Component.
 *
 * Click-triggered menu with two rendering modes:
 * - **Portal** (default): teleports a fixed-position panel to `<body>` via Alpine `x-teleport`,
 *   escaping parent overflow/stacking contexts. Handles auto-flip, auto-fit, and horizontal clamping.
 * - **Classic**: delegates to `DropdownBase` for a relative-positioned panel anchored to the trigger.
 *
 * Switch modes in Blade with `:usePortal="false"`.
 *
 * @param string      $placement    Horizontal alignment: 'left' (default), 'center', 'right'.
 * @param string      $side         Vertical side: 'bottom' (default) or 'top'.
 * @param string|null $color        Color preset name (resolved via dropdown presets).
 * @param string|null $size         Size preset name (controls dropdown width).
 * @param bool|null   $withnavigate Pass true to add `wire:navigate` on dropdown items.
 */
class Dropdown extends BeartropyComponent
{
    public function __construct(
        public string $placement = 'bottom',
        public string $side = 'left',
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $withnavigate = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        // Resolve default color before view renders so @aware children see it
        if ($this->color === null) {
            $colors = config('beartropyui.presets.dropdown.colors', []);
            $this->color = (string) array_key_first($colors) ?: 'neutral';
        }

        return view('beartropy-ui::dropdown');
    }
}
