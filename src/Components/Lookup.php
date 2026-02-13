<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Lookup Component.
 *
 * An autocomplete/combobox input: type text, filter options, pick from a dropdown.
 * Extends BeartropyComponent directly (not Input) for a clean, self-contained constructor.
 *
 * @property array       $options     Normalized list of options.
 * @property string      $optionLabel Property name for option label.
 * @property string      $optionValue Property name for option value.
 * @property string|null $label       Label text.
 *
 * ## Blade Props
 *
 * ### Slots
 * @slot start Prepend content/icon.
 * @slot end   Append content/icon.
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
 * @property bool $beartropy Beartropy color.
 * @property bool $primary   Primary color.
 * @property bool $secondary Secondary color.
 * @property bool $slate     Slate color.
 * @property bool $gray      Gray color.
 * @property bool $zinc      Zinc color.
 * @property bool $neutral   Neutral color.
 * @property bool $stone     Stone color.
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
 */
class Lookup extends BeartropyComponent
{
    /**
     * Create a new Lookup component instance.
     *
     * @param string|null $id          Element ID (auto-generated if null).
     * @param string|null $name        Hidden input name (defaults to $id).
     * @param string|null $label       Label text.
     * @param string|null $color       Color theme.
     * @param string|null $size        Component size.
     * @param string|null $placeholder Placeholder text.
     * @param array|\Illuminate\Support\Collection $options Options array or Collection.
     * @param string      $optionLabel Label key for options.
     * @param string      $optionValue Value key for options.
     * @param mixed       $value       Initial value.
     * @param bool        $disabled    Disabled state.
     * @param bool        $readonly    Readonly state.
     * @param bool        $clearable   Enable clear button.
     * @param string|null $iconStart   Icon at the start.
     * @param string|null $iconEnd     Icon at the end.
     * @param string|null $help        Help text.
     * @param string|null $hint        Hint text.
     * @param mixed       $customError Custom error message/state.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $label = null,
        public ?string $color = null,
        public ?string $size = null,
        public ?string $placeholder = null,
        public array|\Illuminate\Support\Collection $options = [],
        public string $optionLabel = 'name',
        public string $optionValue = 'id',
        public mixed $value = null,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $clearable = true,
        public ?string $iconStart = null,
        public ?string $iconEnd = null,
        public ?string $help = null,
        public ?string $hint = null,
        public mixed $customError = null,
    ) {
        $this->id = $id ?? ('beartropy-lookup-' . uniqid());
        $this->name = $name ?? $this->id;
        $this->normalizeOptions($options);
    }

    /**
     * Normalize options into a standard format.
     *
     * Supports simple arrays, arrays of objects/arrays, and single key-value pairs.
     *
     * @param array|\Illuminate\Support\Collection $options Raw options.
     */
    public function normalizeOptions(array|\Illuminate\Support\Collection $options): void
    {
        $vKey = $this->optionValue;
        $lKey = $this->optionLabel;

        $this->options = collect($options)
            ->map(function ($item) use ($vKey, $lKey) {
                // Simple list: ["asd", "dsa", 123, ...]
                if (is_scalar($item)) {
                    $val = (string) $item;

                    return [$vKey => $val, $lKey => $val];
                }

                // Array/object with dynamic keys
                $id = data_get($item, $vKey);
                $name = data_get($item, $lKey);

                // Single key-value pair: ["ar" => "Argentina"]
                if (is_null($id) && is_null($name) && is_array($item) && count($item) === 1) {
                    $k = array_key_first($item);

                    return [$vKey => (string) $k, $lKey => (string) $item[$k]];
                }

                // If value exists but label is missing, use value as label
                if (! is_null($id)) {
                    return [$vKey => (string) $id, $lKey => (string) ($name ?? $id)];
                }

                // Unrecognizable format: discard
                return null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Get the view that represents the component.
     */
    public function render(): View
    {
        return view('beartropy-ui::lookup');
    }
}
