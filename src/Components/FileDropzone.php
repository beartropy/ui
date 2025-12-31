<?php

namespace Beartropy\Ui\Components;

/**
 * FileDropzone component.
 *
 * A drag-and-drop file upload area.
 *
 * @property string|null $name        Input name.
 * @property string|null $label       Label text.
 * @property string|null $icon        Icon name.
 * @property bool        $preview     Show file preview.
 * @property bool        $multiple    Allow multiple files.
 * @property string|null $accept      File accept attribute.
 * @property bool        $clearable   Allow clearing selection.
 * @property bool        $disabled    Disabled state.
 * @property string|null $color       Color preset.
 * @property string|null $customError Custom error message.
 */
class FileDropzone extends BeartropyComponent
{
    /**
     * Create a new FileDropzone component instance.
     *
     * @param string|null $name        Input name.
     * @param string|null $label       Label text.
     * @param string|null $icon        Icon name.
     * @param bool        $preview     Show file preview.
     * @param bool        $multiple    Allow multiple files.
     * @param string|null $accept      File accept attribute.
     * @param bool        $clearable   Allow clearing selection.
     * @param bool        $disabled    Disabled state.
     * @param string|null $color       Color preset.
     * @param string|null $customError Custom error message.
     */
    public function __construct(
        public $name = null,
        public $label = null,
        public $icon = null,
        public $preview = true,
        public $multiple = true,
        public $accept = null,
        public $clearable = true,
        public $disabled = false,
        public $color = null,
        public $customError = null,
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::file-dropzone');
    }
}
