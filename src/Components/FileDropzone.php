<?php

namespace Beartropy\Ui\Components;

class FileDropzone extends BeartropyComponent
{
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


    public function render()
    {
        return view('beartropy-ui::file-dropzone');
    }
}
