<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Avatar component.
 *
 * Displays a user avatar image or initials.
 *
 * @property string|null $src        Image source URL.
 * @property string      $alt        Alt text.
 * @property string|null $size       Size preset (e.g. 'sm', 'md').
 * @property string|null $color      Color preset.
 * @property string|null $initials   Fallback initials.
 * @property string|null $customSize Custom CSS size (e.g. 'h-12 w-12').
 */
class Avatar extends BeartropyComponent
{
    public $src, $alt, $size, $initials, $color, $customSize;

    /**
     * Create a new Avatar component instance.
     *
     * @param string|null $src        Image source URL.
     * @param string      $alt        Alt text.
     * @param string|null $size       Size preset.
     * @param string|null $color      Color preset.
     * @param string|null $initials   Fallback initials.
     * @param string|null $customSize Custom CSS size.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Initials or custom content if image fails/missing.
     * @slot status  Status indicator content.
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
    public function __construct($src = null, $alt = '', $size = null, $color = null, $initials = null, $customSize = null)
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->size = $size;
        $this->color = $color;
        $this->initials = $initials;
        $this->customSize = $customSize;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::avatar');
    }
}
