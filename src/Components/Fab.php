<?php

namespace Beartropy\Ui\Components;

/**
 * Floating Action Button (FAB) component.
 *
 * Renders a floating action button, typically fixed position on screen.
 *
 * @property string|null $icon       Icon name.
 * @property string|null $label      Label text (tooltip or accessible).
 * @property string|null $onlyMobile Only show on mobile breakpoint.
 * @property string|null $zIndex     Z-index class.
 * @property string|null $right      Right position class.
 * @property string|null $bottom     Bottom position class.
 * @property string|null $color      Color preset.
 * @property string|null $size       Size preset.
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
        return view('beartropy-ui::fab');
    }
}
