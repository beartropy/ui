<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\View\View;

/**
 * FileInput component.
 *
 * Standard file input component.
 *
 * @property string      $id          Input ID.
 * @property string|null $name        Input name.
 * @property string      $placeholder Placeholder text.
 * @property bool        $clearable   Allow clearing.
 * @property bool        $disabled    Disabled state.
 * @property bool        $multiple    Allow multiple files.
 * @property string|null $accept      Accept attribute.
 * @property string|null $label       Label text.
 * @property string|null $customError Custom error message.
 * @property string|null $hint        Hint text.
 */
class FileInput extends BeartropyComponent
{
    public string $id;

    public ?string $name;

    public string $placeholder;

    public bool $clearable;

    public bool $disabled;

    public bool $multiple;

    public ?string $accept;

    public ?string $label = null;
    public ?string $customError = null;
    public ?string $hint = null;

    /**
     * Create a new FileInput component instance.
     *
     * @param string|null $id          Input ID.
     * @param string|null $name        Input name.
     * @param bool        $multiple    Allow multiple files.
     * @param string|null $accept      Accept attribute.
     * @param string      $placeholder Placeholder text.
     * @param bool        $clearable   Allow clearing.
     * @param bool        $disabled    Disabled state.
     * @param string|null $customError Custom error message.
     * @param string|null $label       Label text.
     * @param string|null $hint        Hint text.
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        bool $multiple = false,
        ?string $accept = null,
        string $placeholder = 'Elegir archivo',
        bool $clearable = true,
        bool $disabled = false,
        ?string $customError = null,
        ?string $label = null,
        ?string $hint = null
    ) {
        $this->id = $id ?? ('input-file-' . uniqid());
        $this->name = $name ?? $this->id;
        $this->multiple = $multiple;
        $this->accept = $accept;
        $this->placeholder = $placeholder;
        $this->clearable = $clearable;
        $this->disabled = $disabled;
        $this->customError = $customError;
        $this->label = $label;
        $this->hint = $hint;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): View
    {
        return view('beartropy-ui::file-input');
    }
}
