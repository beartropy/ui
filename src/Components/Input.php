<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Components\Base\InputBase;
use Illuminate\Contracts\View\View;

/**
 * Input Component.
 *
 * Renders a form input field with support for icons, clear button, password toggle, and standard attributes.
 */
class Input extends InputBase
{
    /**
     * Create a new Input component instance.
     *
     * @param string|null $iconStart          Icon at the start.
     * @param string|null $iconStartSvg       Raw SVG for start icon.
     * @param string|null $iconEnd            Icon at the end.
     * @param string|null $iconEndSvg         Raw SVG for end icon.
     * @param bool        $copyButton         Enable copy-to-clipboard button.
     * @param bool        $clearable          Enable clear input button.
     * @param string|null $help               Help text.
     * @param bool        $showPasswordToggle Enable password visibility toggle.
     * @param mixed       $customError        Custom error message/state.
     * @param mixed       $value              Input value.
     * @param string|null $hint               Input hint.
     * @param string      $type               Input type (text, password, etc.).
     * @param string|null $size               Input size.
     * @param string|null $color              Input color state.
     * @param string|null $label              Label text.
     * @param string|null $placeholder        Placeholder text.
     * @param bool        $spinner            Show spinner on loading.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot start Start content/icon override.
     * @slot end   End content/icon override.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     */
    public function __construct(
        public ?string $iconStart = null,
        public ?string $iconStartSvg = null,
        public ?string $iconEnd = null,
        public ?string $iconEndSvg = null,
        public bool $copyButton = false,
        public bool $clearable = true,
        public ?string $help = null,
        public bool $showPasswordToggle = false,
        public mixed $customError = null,
        public mixed $value = null,
        public ?string $hint = null,
        public $type = 'text',
        public $size = null,
        public $color = null,
        public $label = null,
        public $placeholder = null,
        public bool $spinner = true,
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::input');
    }
}
