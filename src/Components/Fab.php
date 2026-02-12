<?php

namespace Beartropy\Ui\Components;

/**
 * Floating Action Button (FAB) component.
 *
 * Renders a fixed-position circular action button at a configurable screen
 * corner. Supports color and size presets via `HasPresets`. When an `href`
 * attribute is present, renders as `<a>`; otherwise renders as `<button>`.
 * Accepts a default slot for fully custom content, or an `icon` prop to
 * render a Heroicon. The `label` prop sets the accessible `aria-label`.
 *
 * @property string|null $icon       Heroicon name (default: 'plus').
 * @property string|null $label      Accessible label for aria-label.
 * @property string|null $onlyMobile When truthy, hides the FAB on md+ screens.
 * @property string|null $zIndex     CSS z-index value (default: 50).
 * @property string|null $right      CSS right offset (default: '1rem').
 * @property string|null $bottom     CSS bottom offset (default: '1rem').
 * @property string|null $color      Color preset name.
 * @property string|null $size       Size preset name.
 */
class Fab extends BeartropyComponent
{
    /**
     * Create a new Fab component instance.
     *
     * @param string|null $icon       Icon name.
     * @param string|null $label      Label text.
     * @param string|null $onlyMobile Only show on mobile.
     * @param string|null $zIndex     Z-index value.
     * @param string|null $right      Right position.
     * @param string|null $bottom     Bottom position.
     * @param string|null $color      Color preset.
     * @param string|null $size       Size preset.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Button content (if no icon used).
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     */
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $onlyMobile = null,
        public ?string $zIndex = null,
        public ?string $right = null,
        public ?string $bottom = null,
        public ?string $color = null,
        public ?string $size = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::fab');
    }
}
