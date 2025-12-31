<?php

namespace Beartropy\Ui\Components;

/**
 * Alert Component.
 *
 * Renders an alert box with customizable color, icon, and dismissibility.
 */
class Alert extends BeartropyComponent
{
    /**
     * Create a new Alert component instance.
     *
     * @param bool        $noIcon      If true, hides the default or provided icon.
     * @param string|null $icon        Custom icon name/SVG.
     * @param string|null $title       Optional title/heading for the alert.
     * @param bool        $dismissible If true, adds a dismiss button.
     * @param string      $class       Additional CSS classes.
     * @param string|null $color       Alert color theme (e.g., 'primary', 'danger').
     */
    public function __construct(
        public $noIcon = false,
        public $icon = null,
        public $title = null,
        public $dismissible = false,
        public $class = '',
        public $color = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::alert');
    }
}
