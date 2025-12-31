<?php

namespace Beartropy\Ui\Components;

/**
 * ChatInput component.
 *
 * A specialized textarea for chat applications, supporting auto-resize and submit on enter.
 *
 * @property string|null $label         Input label.
 * @property string      $placeholder   Placeholder text.
 * @property int         $rows          Initial number of rows.
 * @property string|null $name          Input name.
 * @property string|null $id            Input ID.
 * @property string|null $color         Input color.
 * @property bool        $disabled      Disabled state.
 * @property bool        $readonly      Readonly state.
 * @property bool        $required      Required state.
 * @property string|null $help          Helper text.
 * @property string|null $customError   Custom error message.
 * @property int|null    $maxLength     Max character length.
 * @property bool        $stacked       Stacked layout.
 * @property bool        $submitOnEnter Submit form on Enter key.
 * @property string|null $action        Action to fire on submit.
 */
class ChatInput extends BeartropyComponent
{
    /**
     * Create a new ChatInput component instance.
     *
     * @param string|null $label         Input label.
     * @param string      $placeholder   Placeholder text.
     * @param int         $rows          Initial number of rows.
     * @param string|null $name          Input name.
     * @param string|null $id            Input ID.
     * @param string|null $color         Input color.
     * @param bool        $disabled      Disabled state.
     * @param bool        $readonly      Readonly state.
     * @param bool        $required      Required state.
     * @param string|null $help          Helper text.
     * @param string|null $customError   Custom error message.
     * @param int|null    $maxLength     Max character length.
     * @param bool        $stacked       Stacked layout.
     * @param bool        $submitOnEnter Submit form on Enter key.
     * @param string|null $action        Action to fire on submit.
     */
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
        public $stacked = false,
        public $submitOnEnter = true,
        public $action = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::chat-input');
    }
}
