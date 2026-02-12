<?php

namespace Beartropy\Ui\Components;

/**
 * Badge Component.
 *
 * Renders a small badge or tag, used to label items with status or category.
 *
 * @param string|null $color     Badge color theme.
 * @param string|null $size      Badge size (xs, sm, md, lg, xl). Default: sm (via config).
 * @param string|null $variant   Style variant (solid, soft, outline, tint, glass). Default: solid.
 * @param string|null $label     Text content (alternative to slot).
 * @param string|null $icon      Icon shorthand for $iconLeft.
 * @param string|null $iconLeft  Icon to display on the left.
 * @param string|null $iconRight Icon to display on the right.
 *
 * ## Blade Props
 *
 * ### Slots
 * @slot default Badge content/label.
 * @slot start   Prefix content.
 * @slot end     Suffix content.
 *
 * ### Magic Attributes (Size)
 * @property bool $xs Extra Small.
 * @property bool $sm Small (default via config).
 * @property bool $md Medium.
 * @property bool $lg Large.
 * @property bool $xl Extra Large.
 *
 * ### Magic Attributes (Variant)
 * @property bool $solid   Solid background (default).
 * @property bool $soft    Soft/muted background.
 * @property bool $outline Outline with border.
 * @property bool $tint    Tinted translucent background.
 * @property bool $glass   Glassmorphism effect.
 *
 * ### Magic Attributes (Color)
 * @property bool $beartropy Beartropy color (default via config).
 * @property bool $red       Red color.
 * @property bool $orange    Orange color.
 * @property bool $amber     Amber color.
 * @property bool $yellow    Yellow color.
 * @property bool $lime      Lime color.
 * @property bool $green     Green color.
 * @property bool $emerald   Emerald color.
 * @property bool $teal      Teal color.
 * @property bool $cyan      Cyan color.
 * @property bool $sky       Sky color.
 * @property bool $blue      Blue color.
 * @property bool $indigo    Indigo color.
 * @property bool $violet    Violet color.
 * @property bool $purple    Purple color.
 * @property bool $fuchsia   Fuchsia color.
 * @property bool $pink      Pink color.
 * @property bool $rose      Rose color.
 */
class Badge extends BeartropyComponent
{
    public function __construct(
        public ?string $color = null,
        public ?string $size = null,
        public ?string $variant = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconLeft = null,
        public ?string $iconRight = null,
    ) {}

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::badge');
    }
}
