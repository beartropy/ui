<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Beartropy\Ui\Components\Base\InputTriggerBase;

/**
 * Select Component.
 *
 * A powerful select input supporting arrays, Eloquent collections, remote search, and object mapping.
 *
 * @property string|null $optionLabel       Field map for option label.
 * @property string|null $optionValue       Field map for option value.
 * @property string|null $optionDescription Field map for option description.
 * @property string|null $optionIcon        Field map for option icon.
 * @property string|null $optionAvatar      Field map for option avatar.
 */
class Select extends InputTriggerBase
{
    public $options;

    public $selected;
    public $icon;
    public $placeholder;
    public $searchable;
    public $label;
    public $multiple;
    public $clearable;
    public $remote;
    public $remoteUrl;
    public $size;
    public $color;
    public $initialValue;
    public $perPage;
    public $customError;
    public $hint;

    // 🔑 Nuevos mapeos
    public $optionLabel;
    public $optionValue;
    public $optionDescription;
    public $optionIcon;
    public $optionAvatar;

    public $autosave;
    public $autosaveMethod;
    public $autosaveKey;
    public $autosaveDebounce;

    public $emptyMessage = 'No se encontraron opciones';
    public $isEmpty = false;
    public $spinner;

    /**
     * Create a new Select component instance.
     *
     * @param mixed       $options           Options array or Collection.
     * @param mixed       $selected          Initially selected value.
     * @param string|null $icon              Trigger icon.
     * @param string      $placeholder       Placeholder text.
     * @param bool        $searchable        Enable search input.
     * @param string|null $label             Label text.
     * @param bool        $multiple          Enable multiple selection.
     * @param bool        $clearable         Enable clear button.
     * @param bool        $remote            Enable remote data fetching.
     * @param string|null $remoteUrl         Endpoint for remote data.
     * @param string|null $size              Component size.
     * @param string|null $color             Color theme.
     * @param mixed       $initialValue      Initial value for remote loading context.
     * @param int         $perPage           Results per page.
     * @param mixed       $customError       Custom error state.
     * @param string|null $hint              Helper text.
     * @param bool|int    $autosave          Auto-save selection on change.
     * @param string      $autosaveMethod    Method to call on auto-save (Livewire).
     * @param string|null $autosaveKey       Key to update on auto-save.
     * @param int         $autosaveDebounce  Debounce Ms for auto-save.
     * @param string      $optionLabel       Key/Method for label mapping.
     * @param string      $optionValue       Key/Method for value mapping.
     * @param string      $optionDescription Key/Method for description mapping.
     * @param string      $optionIcon        Key/Method for icon mapping.
     * @param string      $optionAvatar      Key/Method for avatar mapping.
     * @param string      $emptyMessage      Text to show when no options found.
     * @param bool        $spinner           Show loading spinner.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot start         Content before trigger.
     * @slot beforeOptions Content at top of dropdown.
     * @slot afterOptions  Content at bottom of dropdown.
     * @slot dropdown      Dropdown content override.
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
        $options = null,
        $selected = null,
        $icon = null,
        $placeholder = 'Seleccionar...',
        $searchable = true,
        $label = null,
        $multiple = false,
        $clearable = true,
        $remote = false,
        $remoteUrl = null,
        $size = null,
        $color = null,
        $initialValue = null,
        $perPage = 15,
        $customError = null,
        $hint = null,
        $autosave = false,
        $autosaveMethod = 'savePreference',
        $autosaveKey = null,
        $autosaveDebounce = 300,

        // 🔑 Defaults de mapeo
        $optionLabel = 'label',
        $optionValue = 'value',
        $optionDescription = 'description',
        $optionIcon = 'icon',
        $optionAvatar = 'avatar',

        $emptyMessage = 'No se encontraron opciones',
        $spinner = true,
    ) {
        // Guardar mapeos primero (los usa normalizeOptions)
        $this->optionLabel = $optionLabel ?: 'label';
        $this->optionValue = $optionValue ?: 'value';
        $this->optionDescription = $optionDescription ?: 'description';
        $this->optionIcon = $optionIcon ?: 'icon';
        $this->optionAvatar = $optionAvatar ?: 'avatar';

        if (empty($options) || is_null($options)) {
            $this->isEmpty = true;

            // Fix: Don't disable search/clear if it's a remote select
            if (!filter_var($remote, FILTER_VALIDATE_BOOLEAN) && empty($remoteUrl)) {
                $clearable = false;
                $searchable = false;
            }

            $options = [];
        } else {
            $this->options      = $this->normalizeOptions($options);
        }
        $this->selected     = $selected;
        $this->icon         = $icon;
        $this->placeholder  = $placeholder;
        $this->searchable   = filter_var($searchable, FILTER_VALIDATE_BOOLEAN);
        $this->label        = $label;
        $this->multiple     = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);
        $this->clearable    = filter_var($clearable, FILTER_VALIDATE_BOOLEAN);
        $this->remote       = filter_var($remote, FILTER_VALIDATE_BOOLEAN);
        $this->remoteUrl    = $remoteUrl;
        $this->size         = $size;
        $this->color        = $color;
        $this->initialValue = $initialValue;
        $this->perPage      = (int) $perPage;
        $this->customError  = $customError;
        $this->hint         = $hint;
        $this->autosave          = filter_var($autosave, FILTER_VALIDATE_BOOLEAN);
        $this->autosaveMethod    = $autosaveMethod;
        $this->autosaveKey       = $autosaveKey;
        $this->autosaveDebounce  = (int) $autosaveDebounce;
        $this->emptyMessage     = $emptyMessage;
        $this->spinner          = $spinner;
    }

    /**
     * Normalize options into a standard array format.
     *
     * Handles:
     * - Eloquent Collections.
     * - Associative arrays (key => label).
     * - Indexed arrays.
     * - Array of Options (objects/arrays).
     *
     * Uses configured field mappings (`optionLabel`, `optionValue`) to extract data.
     *
     * @param mixed $options Raw options data.
     *
     * @return array<string, array{_value: mixed, label: string|null, icon: string|null, avatar: string|null, description: string|null}>
     */
    protected function normalizeOptions($options)
    {
        if ($options instanceof Collection) {
            $options = $options->all();
        }

        // ¿array asociativo?
        $isAssociative = false;
        if (is_array($options) && count($options)) {
            $keys = array_keys($options);
            $isAssociative = count(array_filter($keys, 'is_string')) > 0;
        }

        $isEmoji = function ($icon) {
            return is_string($icon) && preg_match('/^[\p{Emoji}\p{S}\p{So}\x{1F600}-\x{1F64F}]{1,3}$/u', $icon);
        };

        $renderIcon = function ($icon) use ($isEmoji) {
            if (empty($icon)) return null;
            if ($isEmoji($icon)) return $icon;
            $trim = is_string($icon) ? trim($icon) : $icon;
            if (is_string($trim) && str_starts_with($trim, '<svg')) return $icon;
            if (is_string($trim) && str_starts_with($trim, '<img')) return $icon;
            if (is_string($icon) && mb_strlen($icon) <= 2 && strip_tags($icon) === $icon) return $icon;

            $iconComponent = new \Beartropy\Ui\Components\Icon(name: $icon, class: 'w-5 h-5');
            return Blade::renderComponent($iconComponent);
        };

        // 🔍 Getter defensivo por campo
        $get = function ($source, string $field, $fallbacks = []) {
            // 1) Campo explícito
            $candidates = array_filter([$field, ...$fallbacks]);

            foreach ($candidates as $key) {
                // Array
                if (is_array($source) && array_key_exists($key, $source)) {
                    return $source[$key];
                }
                // Eloquent / Model-like
                if (is_object($source) && method_exists($source, 'getAttribute')) {
                    $val = $source->getAttribute($key);
                    if (!is_null($val)) return $val;
                }
                // Objeto simple
                if (is_object($source) && isset($source->{$key})) {
                    return $source->{$key};
                }
            }

            return null;
        };

        $normalized = [];

        $processOption = function ($rawId, $option) use ($get, $renderIcon) {
            // 🆔 ID / value
            $id = $get($option, $this->optionValue, ['id', 'key', 'value']);

            // 🏷️ Label
            $label = $get($option, $this->optionLabel, ['label', 'name', 'text', 'value']);

            // 🧾 Description
            $desc = $get($option, $this->optionDescription, ['description', 'desc', 'subtitle']);

            // 🖼️ Icon / Avatar
            $icon = $get($option, $this->optionIcon, ['icon']);
            $avatar = $get($option, $this->optionAvatar, ['avatar', 'image', 'photo', 'picture']);

            // Fallbacks finales
            if (is_null($label)) {
                // Si no hay label, usar el id como label si existe
                $label = $id ?? (is_scalar($option) ? (string)$option : null);
            }

            return [
                '_value'      => $id ?? $rawId, // guardamos el "value" real para la vista/JS
                'label'       => $label,
                'icon'        => $renderIcon($icon),
                'avatar'      => $avatar,
                'description' => $desc,
            ];
        };

        if ($isAssociative) {
            foreach ($options as $id => $option) {
                // Caso simple: label string
                if (is_string($option)) {
                    $normalized[(string)$id] = [
                        '_value'      => $id,
                        'label'       => $option,
                        'icon'        => null,
                        'avatar'      => null,
                        'description' => null,
                    ];
                    continue;
                }

                $normalized[(string)$id] = $processOption($id, $option);
            }
        } else {
            foreach ($options as $index => $item) {
                if (is_string($item)) {
                    $normalized[(string)$item] = [
                        '_value'      => $item,
                        'label'       => $item,
                        'icon'        => null,
                        'avatar'      => null,
                        'description' => null,
                    ];
                    continue;
                }

                $id = null;
                // Si trae id/value, úsalo como key para consistencia
                if (is_array($item)) {
                    $id = $item[$this->optionValue] ?? $item['id'] ?? $item['value'] ?? null;
                } elseif (is_object($item) && method_exists($item, 'getAttribute')) {
                    $id = $item->getAttribute($this->optionValue) ?? $item->getAttribute('id') ?? $item->getAttribute('value');
                } elseif (is_object($item) && isset($item->{$this->optionValue})) {
                    $id = $item->{$this->optionValue};
                }

                $key = $id !== null ? (string)$id : (string)$index;
                $normalized[$key] = $processOption($key, $item);
            }
        }

        return $normalized;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::select');
    }
}
