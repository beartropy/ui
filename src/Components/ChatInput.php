<?php

namespace Beartropy\Ui\Components;

/**
 * ChatInput component.
 *
 * A specialized textarea for chat applications with auto-resize,
 * single-line/stacked layout switching, and submit-on-Enter.
 *
 * @property string|null $id            Component id.
 * @property string|null $name          Input name.
 * @property string|null $label         Input label.
 * @property string|null $color         Color preset key.
 * @property string      $placeholder   Placeholder text.
 * @property bool        $disabled      Disabled state.
 * @property bool        $readonly      Readonly state.
 * @property bool        $required      Required state.
 * @property string|null $help          Helper text.
 * @property string|null $hint          Hint text.
 * @property mixed       $customError   Custom error message.
 * @property int|null    $maxLength     Max character length.
 * @property bool        $stacked       Stacked layout.
 * @property bool        $submitOnEnter Submit form on Enter key.
 * @property string|null $action        Livewire action to fire on submit.
 * @property bool        $border        Show border styling.
 */
class ChatInput extends BeartropyComponent
{
    /**
     * Create a new ChatInput component instance.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot tools   Left-side tool buttons.
     * @slot footer  Footer area (alias: actions).
     * @slot actions Action buttons area.
     *
     * ### Magic Attributes (Color)
     * @property bool $beartropy Beartropy color.
     * @property bool $red       Red color.
     * @property bool $orange    Orange color.
     * @property bool $amber     Amber color.
     * @property bool $yellow    Yellow color.
     * @property bool $lime      Lime color.
     * @property bool $green     Green color.
     * @property bool $emerald   Emerald color.
     * @property bool $teal      Teal color.
     * @property bool $cyan      Cyan color.
     * @property bool $sky       Sky color.
     * @property bool $blue      Blue color.
     * @property bool $indigo    Indigo color.
     * @property bool $violet    Violet color.
     * @property bool $purple    Purple color.
     * @property bool $fuchsia   Fuchsia color.
     * @property bool $pink      Pink color.
     * @property bool $rose      Rose color.
     * @property bool $slate     Slate color.
     * @property bool $gray      Gray color.
     * @property bool $zinc      Zinc color.
     * @property bool $neutral   Neutral color.
     * @property bool $stone     Stone color.
     * @property bool $primary   Primary color (alias for beartropy).
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $color = null,
        public string $placeholder = '',
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $help = null,
        public ?string $hint = null,
        public mixed $customError = null,
        public ?int $maxLength = null,
        public bool $stacked = false,
        public bool $submitOnEnter = true,
        public ?string $action = null,
        public bool $border = false,
    ) {
        $this->id = $id ?? ('beartropy-chat-input-' . uniqid());
        $this->name = $name ?? $this->id;

        if ($placeholder === '') {
            $this->placeholder = __('beartropy-ui::ui.type_message');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::chat-input');
    }
}
