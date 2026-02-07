<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * FileInput component.
 *
 * Standard file input component.
 */
class FileInput extends BeartropyComponent
{
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
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot start  Prepend content/icon (default: paperclip).
     * @slot button Trigger area content.
     * @slot end    Append content/icon (default: spinner/status).
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
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public bool $multiple = false,
        public ?string $accept = null,
        public ?string $placeholder = null,
        public bool $clearable = true,
        public bool $disabled = false,
        public ?string $customError = null,
        public ?string $label = null,
        public ?string $hint = null,
    ) {
        $this->id = $id ?? ('input-file-' . uniqid());
        $this->name = $name ?? $this->id;
    }

    public function render(): View
    {
        return view('beartropy-ui::file-input');
    }
}
