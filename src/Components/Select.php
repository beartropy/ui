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
    public ?string $icon;
    public $placeholder;
    public bool $searchable;
    public $label;
    public bool $multiple;
    public bool $clearable;
    public bool $remote;
    public ?string $remoteUrl;
    public $size;
    public $color;
    public $initialValue;
    public int $perPage;
    public $customError;
    public ?string $hint;
    public ?string $help;

    public ?string $optionLabel;
    public ?string $optionValue;
    public ?string $optionDescription;
    public ?string $optionIcon;
    public ?string $optionAvatar;

    public bool $autosave;
    public ?string $autosaveMethod;
    public ?string $autosaveKey;
    public int $autosaveDebounce;

    public ?string $emptyMessage;
    public bool $isEmpty = false;
    public bool $spinner;
    public bool $defer;
    public bool $fitTrigger;

    public bool $userSearchable;
    public bool $userClearable;

    /** @var array<int, array{_value: mixed, label: string|null, icon: string|null, avatar: string|null, description: string|null}> */
    public static array $pendingSlotOptions = [];

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
     * @param string|null $help              Help text displayed below the field.
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
     * @param bool        $defer             Defer remote fetch until dropdown opens.
     * @param bool        $fitTrigger        Match dropdown width to trigger (false allows wider dropdown).
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
        $placeholder = null,
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
        $help = null,
        $autosave = false,
        $autosaveMethod = 'savePreference',
        $autosaveKey = null,
        $autosaveDebounce = 300,

        $optionLabel = 'label',
        $optionValue = 'value',
        $optionDescription = 'description',
        $optionIcon = 'icon',
        $optionAvatar = 'avatar',

        $emptyMessage = null,
        $spinner = true,
        $defer = false,
        $fitTrigger = true,
    ) {
        // Store mappings first (used by normalizeOptions)
        $this->optionLabel = $optionLabel ?: 'label';
        $this->optionValue = $optionValue ?: 'value';
        $this->optionDescription = $optionDescription ?: 'description';
        $this->optionIcon = $optionIcon ?: 'icon';
        $this->optionAvatar = $optionAvatar ?: 'avatar';

        // Preserve user's original intent before the empty-options guard may override them
        $this->userSearchable = filter_var($searchable, FILTER_VALIDATE_BOOLEAN);
        $this->userClearable = filter_var($clearable, FILTER_VALIDATE_BOOLEAN);

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
        $this->placeholder  = $placeholder ?? __('beartropy-ui::ui.select');
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
        $this->help         = $help;
        $this->autosave          = filter_var($autosave, FILTER_VALIDATE_BOOLEAN);
        $this->autosaveMethod    = $autosaveMethod;
        $this->autosaveKey       = $autosaveKey;
        $this->autosaveDebounce  = (int) $autosaveDebounce;
        $this->emptyMessage     = $emptyMessage ?? __('beartropy-ui::ui.no_options_found');
        $this->spinner          = $spinner;
        $this->defer            = filter_var($defer, FILTER_VALIDATE_BOOLEAN);
        $this->fitTrigger       = filter_var($fitTrigger, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Pre-render an icon string into HTML.
     *
     * Handles emoji, raw SVG/IMG markup, short text, and Heroicon names.
     *
     * @param string|null $icon Raw icon value.
     *
     * @return string|null Rendered HTML or the original string.
     */
    public static function renderIcon(?string $icon): ?string
    {
        if (empty($icon)) {
            return null;
        }

        if (preg_match('/^[\p{Emoji}\p{S}\p{So}\x{1F600}-\x{1F64F}]{1,3}$/u', $icon)) {
            return $icon;
        }

        $trim = trim($icon);
        if (str_starts_with($trim, '<svg') || str_starts_with($trim, '<img')) {
            return $icon;
        }

        if (mb_strlen($icon) <= 2 && strip_tags($icon) === $icon) {
            return $icon;
        }

        $iconComponent = new \Beartropy\Ui\Components\Icon(name: $icon, class: 'w-5 h-5');

        return Blade::renderComponent($iconComponent);
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

        // Associative array?
        $isAssociative = false;
        if (is_array($options) && count($options)) {
            $keys = array_keys($options);
            $isAssociative = count(array_filter($keys, 'is_string')) > 0;
        }

        $renderIcon = static fn($icon) => self::renderIcon($icon);

        // Defensive field getter
        $get = function ($source, string $field, $fallbacks = []) {
            // 1) Explicit field
            $candidates = array_filter([$field, ...$fallbacks]);

            foreach ($candidates as $key) {
                if (is_array($source) && array_key_exists($key, $source)) {
                    return $source[$key];
                }
                if (is_object($source) && method_exists($source, 'getAttribute')) {
                    $val = $source->getAttribute($key);
                    if (!is_null($val)) {
                        return $val;
                    }
                }
                if (is_object($source) && isset($source->{$key})) {
                    return $source->{$key};
                }
            }

            return null;
        };

        $normalized = [];

        $processOption = function ($rawId, $option) use ($get, $renderIcon) {
            $id = $get($option, $this->optionValue, ['id', 'key', 'value']);
            $label = $get($option, $this->optionLabel, ['label', 'name', 'text', 'value']);
            $desc = $get($option, $this->optionDescription, ['description', 'desc', 'subtitle']);
            $icon = $get($option, $this->optionIcon, ['icon']);
            $avatar = $get($option, $this->optionAvatar, ['avatar', 'image', 'photo', 'picture']);

            if (is_null($label)) {
                $label = $id ?? (is_scalar($option) ? (string) $option : null);
            }

            return [
                '_value'      => $id ?? $rawId,
                'label'       => $label,
                'icon'        => $renderIcon($icon),
                'avatar'      => $avatar,
                'description' => $desc,
            ];
        };

        if ($isAssociative) {
            foreach ($options as $id => $option) {
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
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::select');
    }
}
