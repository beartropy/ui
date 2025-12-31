<?php

namespace Beartropy\Ui\Components;

/**
 * ButtonIcon component.
 *
 * Renders a button that consists primarily of an icon.
 *
 * @property string|null $icon        Icon name.
 * @property string|null $label       Accessible label.
 * @property string|null $color       Button color.
 * @property string|null $size        Button size.
 * @property bool|null   $spinner     Show spinner on loading.
 * @property string|null $rounded     Rounded class (default 'full').
 * @property string|null $iconSet     Icon set to use.
 * @property string|null $iconVariant Icon variant.
 */
class ButtonIcon extends BeartropyComponent
{
    /**
     * Create a new ButtonIcon component instance.
     *
     * @param string|null $icon        Icon name.
     * @param string|null $label       Accessible label.
     * @param string|null $color       Button color.
     * @param string|null $size        Button size.
     * @param bool|null   $spinner     Show spinner on loading.
     * @param string|null $rounded     Rounded class.
     * @param string|null $iconSet     Icon set.
     * @param string|null $iconVariant Icon variant.
     *
     * ## Blade Props
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
     *
     * ### Magic Attributes (Variant)
     * @property bool $solid   Solid variant.
     * @property bool $outline Outline variant.
     * @property bool $ghost   Ghost variant.
     * @property bool $light   Light variant.
     */
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?bool $spinner = true,
        public ?string $rounded = 'full',
        public ?string $iconSet = null,
        public ?string $iconVariant = null,
    ) {
        parent::__construct();
        $this->iconSet = $iconSet ?? config('beartropyui.icons.set', 'heroicons');
        $this->iconVariant = $iconVariant ?? config('beartropyui.icons.variant', 'outline');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::button-icon');
    }
}
