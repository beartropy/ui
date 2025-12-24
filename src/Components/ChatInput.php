<?php

namespace Beartropy\Ui\Components;

class ChatInput extends BeartropyComponent
{
    public function __construct(
        public $label = null,
        public $placeholder = '',
        public $rows = 1,
        public $name = null,
        public $id = null,
        public $color = null,
        public $disabled = false,
        public $readonly = false,
        public $required = false,
        public $help = null,
        public $customError = null,
        public $maxLength = null,
    ) {}

    public function render()
    {
        return view('beartropy-ui::chat-input');
    }
}
