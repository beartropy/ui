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
     * @param string|null $icon        Custom icon name (overrides preset icon).
     * @param string|null $title       Optional title/heading for the alert.
     * @param bool        $dismissible If true, adds a dismiss button.
     * @param string      $class       Additional CSS classes.
     * @param string|null $color       Alert color (e.g., 'success', 'error', 'blue').
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Alert content/message.
     *
     * ### Magic Attributes (Color)
     * Semantic: `beartropy`, `success`, `info`, `warning`, `error` (these include preset icons).
     * Named: `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`,
     * `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`,
     * `slate`, `stone`, `zinc`, `neutral` (no preset icon).
     *
     * @property bool $success Success color (icon: check-circle).
     * @property bool $info    Info color (icon: exclamation-circle).
     * @property bool $warning Warning color (icon: exclamation-triangle).
     * @property bool $error   Error color (icon: x-circle).
     */
    public function __construct(
        public bool $noIcon = false,
        public ?string $icon = null,
        public ?string $title = null,
        public bool $dismissible = false,
        public string $class = '',
        public ?string $color = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::alert');
    }
}
