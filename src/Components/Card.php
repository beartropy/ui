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
     * @param  string|null  $title        Card title header.
     * @param  string|null  $footer       Card footer content.
     * @param  string|null  $color        Color preset: beartropy, modal, neutral.
     * @param  string|null  $size         Card size/padding.
     * @param  bool|null    $collapsable  Whether the card content can be toggled.
     * @param  bool|null    $noBorder     Whether to remove the border.
     * @param  bool|null    $defaultOpen  Initial visibility state (if collapsable).
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

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::card');
    }
}
