<?php

namespace Beartropy\Ui\Components\Support;

use Beartropy\Ui\Components\BeartropyComponent;

class FieldHelp extends BeartropyComponent
{
    public $errorMessage;
    public $hint;
    public $minHeight;

    public function __construct($errorMessage = null, $hint = null, $minHeight = null)
    {
        $this->errorMessage = $errorMessage;
        $this->hint = $hint;
        $this->minHeight = ($minHeight) ? 'min-h-[' . $minHeight . ']' : '';
    }

    public function render()
    {
        return view('beartropy-ui::support.field-help');
    }

}
