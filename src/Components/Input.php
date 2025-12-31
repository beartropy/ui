<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\Base\InputBase;

/**
 * Input Component.
 *
 * Renders a form input field with support for icons, clear button, password toggle, and standard attributes.
 */
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
    public $spinner;

    /**
     * Create a new Input component instance.
     *
     * @param string|null $iconStart          Icon at the start.
     * @param string|null $iconStartSvg       Raw SVG for start icon.
     * @param string|null $iconEnd            Icon at the end.
     * @param string|null $iconEndSvg         Raw SVG for end icon.
     * @param bool        $copyButton         Enable copy-to-clipboard button.
     * @param bool        $clearable          Enable clear input button.
     * @param string|null $help               Help text.
     * @param bool        $showPasswordToggle Enable password visibility toggle.
     * @param mixed       $customError        Custom error message/state.
     * @param mixed       $value              Input value.
     * @param string|null $hint               Input hint.
     * @param string      $type               Input type (text, password, etc.).
     * @param string|null $size               Input size.
     * @param string|null $color              Input color state.
     * @param string|null $label              Label text.
     * @param string|null $placeholder        Placeholder text.
     * @param bool        $spinner            Show spinner on loading.
     * @param mixed       ...$args            Parent arguments.
     */
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
        $spinner = true,
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
        $this->spinner = filter_var($spinner, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::input');
    }
}
