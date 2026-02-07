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
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Alert content/message.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
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
