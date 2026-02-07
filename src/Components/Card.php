<?php

namespace Beartropy\Ui\Components;

/**
 * Card Component.
 *
 * A content container with optional title, footer, and collapsibility.
 */
class Card extends BeartropyComponent
{

    /**
     * Create a new Card component instance.
     *
     * @param string|null $title       Card title header.
     * @param string|null $footer      Card footer content.
     * @param string|null $color       Border/accent color.
     * @param string|null $size        Card size/padding.
     * @param bool|null   $collapsable Whether the card content can be toggled.
     * @param bool|null   $noBorder    Whether to remove the border.
     * @param bool|null   $defaultOpen Initial visibility state (if collapsable).
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Card content.
     * @slot footer  Card footer content.
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
        public ?string $title = null,
        public ?string $footer = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $collapsable = false,
        public ?bool $noBorder = false,
        public ?bool $defaultOpen = true,

    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::card');
    }
}
