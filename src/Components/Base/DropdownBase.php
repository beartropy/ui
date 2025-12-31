<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for Dropdown logic.
 */
class DropdownBase extends BeartropyComponent
{
    public $placement;
    public $side;
    public $width;
    public $color;
    public $presetFor;

    public $autoFit;
    public $autoFlip;
    public $maxHeight;
    public $overflow;

    public $triggerLabel;

    /**
     * Create a new DropdownBase component instance.
     *
     * @param string|null $color        Dropdown color.
     * @param string|null $placement    Popper placement (e.g. bottom-start).
     * @param string|null $side         Side logic.
     * @param string|null $width        Width class.
     * @param string|null $presetFor    Target preset.
     * @param bool|null   $autoFit      Enable auto-fit.
     * @param bool|null   $autoFlip     Enable auto-flip.
     * @param string|null $maxHeight    Max height style.
     * @param string|null $overflow     Overflow style.
     * @param string|null $triggerLabel Label for the trigger button.
     */
    public function __construct($color = null, $placement = null, $side = null, $width = null, $presetFor = null, $autoFit = null, $autoFlip = null, $maxHeight = null, $overflow = null, $triggerLabel = null)
    {
        $this->color = $color;
        $this->placement = $placement;
        $this->side = $side;
        $this->width = $width;
        $this->presetFor = $presetFor;
        $this->autoFit = $autoFit;
        $this->autoFlip = $autoFlip;
        $this->maxHeight = $maxHeight;
        $this->overflow = $overflow;
        $this->triggerLabel = $triggerLabel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.dropdown-base');
    }
}
