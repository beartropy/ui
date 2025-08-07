<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

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

    public function __construct($iconStart = null, $iconEnd = null, $spinner = null, $tag = null, $type = null, $href = null, $disabled = false, $size = null, $color = null, $variant = null)
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
    }

    public function getTag() {
        if ($this->attributes->has('href')) {
            return 'a';
        } else {
            return 'button';
        }
    }

    public function getWireTarget() {
        $wireTarget = $this->attributes->get('wire:target');
        if (!$wireTarget && $this->attributes->has('wire:click')) {
            $wireClick = $this->attributes->get('wire:click');
            if (preg_match('/^\s*([a-zA-Z0-9_]+)/', $wireClick, $matches)) {
                $wireTarget = $matches[1];
            }
        }
        return $wireTarget;
    }

    public function render()
    {
        return view('beartropy-ui::base.button-base');
    }

}
