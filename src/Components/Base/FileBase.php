<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * Base class for File input logic.
 *
 * Currently a placeholder for future shared file input logic.
 */
class FileBase extends BeartropyComponent
{
    /**
     * Create a new FileBase component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::base.file-base');
    }
}
