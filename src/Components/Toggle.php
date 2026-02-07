<?php

namespace Beartropy\Ui\Components;

/**
 * Toggle Component.
 *
 * Renders a toggle switch (checkbox alternative).
 */
class Toggle extends BeartropyComponent
{

    /**
     * Create a new Toggle component instance.
     *
     * @param string|null $name             Input name.
     * @param string|null $label            Label text.
     * @param string|null $labelPosition    Label position (left/right).
     * @param string|null $color            Switch color.
     * @param string|null $size             Switch size.
     * @param string|null $customError      Custom error message.
     * @param bool|null   $disabled         Disabled state.
     * @param string|null $hint             Hint text.
     * @param string|null $help             Help text.
     * @param bool        $autosave         Auto-save on toggle.
     * @param string      $autosaveMethod   Method for auto-save.
     * @param string|null $autosaveKey      Key for auto-save.
     * @param int         $autosaveDebounce Debounce ms.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot default Label content override.
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
        public ?string $name = null,
        public ?string $label = null,
        public ?string $labelPosition = 'right',
        public ?string $color = null,
        public ?string $size = null,
        public ?string $customError = null,
        public ?bool $disabled = false,
        public ?string $hint = null,
        public ?string $help = null,
        public bool $autosave = false,
        public string $autosaveMethod = 'savePreference',
        public ?string $autosaveKey = null,
        public int $autosaveDebounce = 300,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::toggle');
    }
}
