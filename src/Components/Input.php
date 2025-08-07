<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\Base\InputBase;

class Input extends InputBase
{
    public $iconStart;
    public $iconStartSvg;
    public $iconEnd;
    public $iconEndSvg;
    public $copyButton;
    public $clearable;
    public $help;
    public $showPasswordToggle;
    public $customError;
    public $value;
    public $hint;
    public $type;
    public $size;
    public $color;
    public $label;
    public $placeholder;

    public function __construct(
        $iconStart = null,
        $iconStartSvg = null,
        $iconEnd = null,
        $iconEndSvg = null,
        $copyButton = false,
        $clearable = true,
        $help = null,
        $showPasswordToggle = false,
        $customError = null,
        $value = null,
        $hint = null,
        $type   = 'text',
        $size = null,
        $color = null,
        $label = null,
        $placeholder = null,
        ...$args
    ) {
        parent::__construct(...$args);
        $this->iconStart = $iconStart;
        $this->iconStartSvg = $iconStartSvg;
        $this->iconEnd = $iconEnd;
        $this->iconEndSvg = $iconEndSvg;
        $this->copyButton = $copyButton;
        $this->clearable = $clearable;
        $this->help = $help;
        $this->showPasswordToggle = $showPasswordToggle;
        $this->customError = $customError;
        $this->value = $value;
        $this->hint = $hint;
        $this->type = $type;
        $this->size = $size;
        $this->color = $color;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('beartropy-ui::input');
    }
}
