<?php

namespace Beartropy\Ui\Components;

/**
 * Lookup component.
 *
 * Extends Input component to provide autocomplete/lookup functionality.
 *
 * @property array  $options     List of options.
 * @property string $optionLabel Property name for option label.
 * @property string $optionValue Property name for option value.
 * @property string|null $label  Label text.
 */
class Lookup extends Input
{
    /**
     * Create a new Lookup component instance.
     *
     * @param array       $options     Options array.
     * @param string      $optionLabel Label key.
     * @param string      $optionValue Value key.
     * @param string|null $label       Label text.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot start Prepend content/icon.
     * @slot end   Append content/icon.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     */
    public function __construct(
        public $options = [],
        public $optionLabel = "name",
        public $optionValue = "id",
        public $label = null,
    ) {
        $this->normalizeOptions($options);
        parent::__construct(label: $label ?? null);
    }

    /**
     * Normalize options into a standard format.
     *
     * Supports simple arrays, arrays of objects/arrays, and single key-value pairs.
     *
     * @param array $options Raw options.
     *
     * @return array Normalized options.
     */
    public function normalizeOptions($options)
    {
        $this->options = collect($options)
            ->map(function ($item) {
                // 1) Simple list: ["asd", "dsa", 123, ...]
                if (is_scalar($item)) {
                    $val = (string) $item;
                    return ['id' => $val, 'name' => $val];
                }

                // 2) Array/object with dynamic keys (uses $this->optionValue / $this->optionLabel)
                $id   = data_get($item, $this->optionValue);
                $name = data_get($item, $this->optionLabel);

                // 3) Single key-value pair: ["ar" => "Argentina"]
                if (is_null($id) && is_null($name) && is_array($item) && count($item) === 1) {
                    $k = array_key_first($item);
                    return ['id' => (string) $k, 'name' => (string) $item[$k]];
                }

                // If id exists but name is missing, use id as name
                if (!is_null($id)) {
                    return ['id' => (string) $id, 'name' => (string) ($name ?? $id)];
                }

                // Unrecognizable format: discard
                return null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::lookup');
    }
}
