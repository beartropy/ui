<?php

namespace Beartropy\Ui\Components;

/**
 * Tag component.
 *
 * Renders a tag input allowing users to add/remove tags with chips.
 *
 * ### Magic Attributes (Color)
 * @property bool $primary   Primary color.
 * @property bool $beartropy Beartropy color.
 * @property bool $red       Red color.
 * @property bool $blue      Blue color.
 * @property bool $green     Green color.
 * @property bool $yellow    Yellow color.
 * @property bool $purple    Purple color.
 * @property bool $pink      Pink color.
 * @property bool $gray      Gray color.
 * @property bool $orange    Orange color.
 * @property bool $amber     Amber color.
 * @property bool $lime      Lime color.
 * @property bool $emerald   Emerald color.
 * @property bool $teal      Teal color.
 * @property bool $cyan      Cyan color.
 * @property bool $sky       Sky color.
 * @property bool $indigo    Indigo color.
 * @property bool $violet    Violet color.
 * @property bool $rose      Rose color.
 * @property bool $fuchsia   Fuchsia color.
 * @property bool $slate     Slate color.
 * @property bool $stone     Stone color.
 * @property bool $zinc      Zinc color.
 * @property bool $neutral   Neutral color.
 *
 * ### Magic Attributes (Size)
 * @property bool $xs Extra Small.
 * @property bool $sm Small.
 * @property bool $md Medium (default).
 * @property bool $lg Large.
 * @property bool $xl Extra Large.
 */
class Tag extends BeartropyComponent
{
    /**
     * Create a new Tag component instance.
     *
     * @param  string|null  $id  Input ID.
     * @param  string|null  $name  Input name.
     * @param  string|null  $label  Label text.
     * @param  string|null  $color  Color preset.
     * @param  string|null  $size  Size preset.
     * @param  string  $placeholder  Placeholder text.
     * @param  array  $value  Initial tags.
     * @param  array|string  $separator  Separator(s) for splitting tags.
     * @param  bool  $disabled  Disabled state.
     * @param  bool  $unique  Enforce unique tags.
     * @param  int|null  $maxTags  Maximum number of tags.
     * @param  string|null  $help  Help text.
     * @param  string|null  $hint  Hint text.
     * @param  mixed  $customError  Custom error message.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $color = null,
        public ?string $size = null,
        public string $placeholder = '',
        public array $value = [],
        public array|string $separator = ',',
        public bool $disabled = false,
        public bool $unique = true,
        public ?int $maxTags = null,
        public ?string $help = null,
        public ?string $hint = null,
        public mixed $customError = null,
    ) {
        $this->id = $id ?? ('beartropy-tag-' . uniqid());
        $this->name = $name ?? $this->id;

        if ($placeholder === '') {
            $this->placeholder = __('beartropy-ui::ui.add_tag');
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::tag');
    }
}
