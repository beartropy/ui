<?php

namespace Beartropy\Ui\Components;

/**
 * Tag component.
 *
 * Renders a tag input or display.
 *
 * @property string|null $name        Input name.
 * @property string|null $label       Label text.
 * @property string      $placeholder Placeholder text.
 * @property array       $value       Initial tags.
 * @property array|string $separator   Separator(s) for tags.
 * @property bool        $disabled    Disabled state.
 * @property bool        $unique      Enforce unique tags.
 * @property int|null    $maxTags     Maximum number of tags.
 * @property string|null $help        Helper text.
 * @property string|null $error       Error message.
 * @property string|null $start       Start slot content/icon.
 * @property string|null $end         End slot content/icon.
 * @property string|null $size        Size preset.
 * @property string|null $customError Custom error message.
 * @property string|null $color       Color preset.
 */
class Tag extends BeartropyComponent
{
    /**
     * Create a new Tag component instance.
     *
     * @param string|null $name        Input name.
     * @param string|null $label       Label text.
     * @param string      $placeholder Placeholder.
     * @param array       $value       Initial value.
     * @param array|string $separator   Separator.
     * @param bool        $disabled    Disabled state.
     * @param bool        $unique      Unique tags only.
     * @param int|null    $maxTags     Max tags.
     * @param string|null $help        Help text.
     * @param string|null $error       Error text.
     * @param string|null $start       Start content.
     * @param string|null $end         End content.
     * @param string|null $size        Size preset.
     * @param string|null $customError Custom error.
     * @param string|null $color       Color preset.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public string $placeholder = 'Add tag...',
        public array $value = [],
        public array|string $separator = ',',
        public bool $disabled = false,
        public bool $unique = true,
        public ?int $maxTags = null,
        public ?string $help = null,
        public ?string $error = null,
        public ?string $start = null,
        public ?string $end = null,
        public ?string $size = null,
        public ?string $customError = null,
        public ?string $color = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::tag');
    }
}
