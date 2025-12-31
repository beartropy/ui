<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Button logic.
 *
 * Handles properties common to all button implementations, including icon, spinner, tag type (a/button), and wire:target.
 */
class ButtonBase extends BeartropyComponent
{
    public $iconStart = null;

    public $iconEnd = null;

    public $spinner = null;

    public $tag = 'button';

    public $type = 'button';

    public $href = null;

    public $disabled = false;

    public $size = null;

    public $color = null;

    public $variant = null;

    public $iconSet = null;

    public $iconVariant = null;

    /**
     * Create a new ButtonBase component instance.
     *
     * @param string|null $iconStart   Icon to show at start.
     * @param string|null $iconEnd     Icon to show at end.
     * @param mixed       $spinner     Loading spinner configuration.
     * @param string|null $tag         HTML tag to render (button/a).
     * @param string|null $type        Button type attribute.
     * @param string|null $href        Link URL.
     * @param bool|null   $disabled    Disabled state.
     * @param string|null $size        Button size.
     * @param string|null $color       Button color.
     * @param string|null $variant     Button variant.
     * @param string|null $iconSet     Icon set used.
     * @param string|null $iconVariant Icon variant used.
     */
    public function __construct($iconStart = null, $iconEnd = null, $spinner = null, $tag = null, $type = null, $href = null, $disabled = null, $size = null, $color = null, $variant = null, $iconSet = null, $iconVariant = null)
    {
        $this->iconStart = $iconStart;
        $this->iconEnd = $iconEnd;
        $this->spinner = $spinner;
        $this->tag = $tag;
        $this->type = $type;
        $this->href = $href;
        $this->disabled = $disabled;
        $this->size = $size;
        $this->color = $color;
        $this->variant = $variant;
        $this->iconSet = $iconSet ?? config('beartropyui.icons.set', 'heroicons');
        $this->iconVariant = $iconVariant ?? config('beartropyui.icons.variant', 'outline');
    }

    /**
     * Determine the HTML tag to use.
     *
     * Returns 'a' if href attribute is present, otherwise 'button'.
     *
     * @return string
     */
    public function getTag()
    {
        if ($this->attributes->has('href')) {
            return 'a';
        } else {
            return 'button';
        }
    }

    /**
     * Get the Livewire target for loading states.
     *
     * Infers target from wire:target or wire:click if not explicitly set.
     *
     * @return string|null
     */
    public function getWireTarget()
    {
        $wireTarget = $this->attributes->get('wire:target');
        if (! $wireTarget && $this->attributes->has('wire:click')) {
            $wireClick = $this->attributes->get('wire:click');
            if (preg_match('/^\s*([a-zA-Z0-9_]+)/', $wireClick, $matches)) {
                $wireTarget = $matches[1];
            }
        }

        return $wireTarget;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.button-base');
    }
}
