<?php

namespace Beartropy\Ui\Components;

/**
 * Badge Component.
 *
 * Renders a small badge or tag, used to label items with status or category.
 */
class Badge extends BeartropyComponent
{

    /**
     * Create a new Badge component instance.
     *
     * @param string|null $color     Badge color theme.
     * @param string|null $size      Badge size (sm, md, lg).
     * @param string|null $variant   Style variant (solid, outline, etc.).
     * @param string|null $iconLeft  Icon to display on the left.
     * @param string|null $iconRight Icon to display on the right.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Badge content/label.
     * @slot start   Prefix content/icon.
     * @slot end     Suffix content/icon.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     *
     * ### Magic Attributes (Variant)
     * @property bool $solid   Solid background (default).
     * @property bool $outline Outline style.
     * @property bool $ghost   Ghost/Transparent style.
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
        public $color = null,
        public $size = null,
        public $variant = null,
        public $iconLeft = null,
        public $iconRight = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::badge');
    }
}
