<?php

namespace Beartropy\Ui\Components\Support;

use Beartropy\Ui\Components\BeartropyComponent;

/**
 * FieldHelp component.
 *
 * Renders helper text or error messages for form fields.
 *
 * @property string|null $errorMessage Error message.
 * @property string|null $hint         Hint text.
 * @property string      $minHeight    Min height class.
 */
class FieldHelp extends BeartropyComponent
{
    /**
     * Create a new FieldHelp component instance.
     *
     * @param string|null $errorMessage Error text.
     * @param string|null $hint         Hint text.
     * @param string|null $minHeight    Min height value.
     */
    public function __construct(
        public ?string $errorMessage = null,
        public ?string $hint = null,
        public string $minHeight = '',
    ) {
        $this->minHeight = $minHeight ? 'min-h-[' . $minHeight . ']' : '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::support.field-help');
    }
}
