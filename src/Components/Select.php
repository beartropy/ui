<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Beartropy\Ui\Components\Base\InputTriggerBase;

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

    public function __construct(
        $options = [],
        $selected = null,
        $icon = null,
        $placeholder = 'Seleccionar...',
        $searchable = false,
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
    ) {
        $this->options = $this->normalizeOptions($options);
        $this->selected = $selected;
        $this->icon = $icon;
        $this->placeholder = $placeholder;
        $this->searchable = filter_var($searchable, FILTER_VALIDATE_BOOLEAN);
        $this->label = $label;
        $this->multiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);
        $this->clearable = filter_var($clearable, FILTER_VALIDATE_BOOLEAN);
        $this->remote = filter_var($remote, FILTER_VALIDATE_BOOLEAN);
        $this->remoteUrl = $remoteUrl;
        $this->size = $size;
        $this->color = $color;
        $this->initialValue = $initialValue;
        $this->perPage = (int)$perPage;
        $this->remoteUrl = $remoteUrl;
        $this->customError = $customError;
        $this->hint = $hint;
    }

    protected function normalizeOptions($options)
    {
        if ($options instanceof Collection) {
            $options = $options->all();
        }

        // Detección de array asociativo
        $isAssociative = false;
        if (is_array($options) && count($options)) {
            $keys = array_keys($options);
            $isAssociative = array_keys($keys) !== $keys;
        }

        $isEmoji = function($icon) {
            // Emoji unicode ranges + símbolos y pictogramas
            return is_string($icon) && preg_match('/^[\p{Emoji}\p{S}\p{So}\x{1F600}-\x{1F64F}]{1,3}$/u', $icon);
        };

        // Renderizado de icono
        $renderIcon = function($icon) use ($isEmoji) {
            if (empty($icon)) return null;
            // Emoji: no tocar
            if ($isEmoji($icon)) {
                return $icon;
            }
            // SVG crudo: dejar pasar
            if (is_string($icon) && str_starts_with(trim($icon), '<svg')) {
                return $icon;
            }

            if (is_string($icon) && str_starts_with(trim($icon), '<img')) {
                return $icon;
            }
            // Fallback: si es muy corto y no tiene tags, consideramos que es texto tipo emoji (extra safe)
            if (mb_strlen($icon) <= 2 && strip_tags($icon) === $icon) {
                return $icon;
            }
            // Si no, renderizar por Blade
            $iconComponent = new \Beartropy\Ui\Components\Icon(name: $icon, class: 'w-5 h-5');
            return Blade::renderComponent($iconComponent);
        };

        $normalized = [];

        $processOption = function($id, $option) use ($renderIcon) {
            if (is_object($option) && method_exists($option, 'getAttribute')) {
                $label = $option->getAttribute('label') ?? $option->getAttribute('name') ?? $option->getAttribute('value') ?? null;
                $icon  = $option->getAttribute('icon') ?? null;
                $avatar= $option->getAttribute('avatar') ?? null;
                $desc  = $option->getAttribute('description') ?? null;
            } elseif (is_string($option)) {
                // String plano: usalo como label
                $label = $option;
                $icon = null;
                $avatar = null;
                $desc = null;
            } else {
                $label = $option['label'] ?? $option['name'] ?? $option['value'] ?? null;
                $icon  = $option['icon'] ?? null;
                $avatar= $option['avatar'] ?? null;
                $desc  = $option['description'] ?? null;
            }
            return [
                'label' => $label,
                'icon' => $renderIcon($icon),
                'avatar' => $avatar,
                'description' => $desc,
            ];
        };


        if ($isAssociative) {
            foreach ($options as $id => $option) {
                $normalized[(string)$id] = $processOption($id, $option);
            }
        } else {
            foreach ($options as $index => $item) {
                if (is_object($item) && method_exists($item, 'getAttribute')) {
                    $id = $item->getAttribute('id') ?? null;
                } else {
                    $id = is_array($item) && isset($item['id']) ? $item['id'] : null;
                }

                if ($id !== null) {
                    $normalized[(string)$id] = $processOption($id, $item);
                } else {
                    if (is_string($item)) {
                        $normalized[(string)$index] = [
                            'label' => $item,
                            'icon' => null,
                            'avatar' => null,
                            'description' => null,
                        ];
                    } else {
                        $normalized[(string)$index] = $processOption($index, $item);
                    }
                }
            }
        }

        return $normalized;
    }

    public function render()
    {
        return view('beartropy-ui::select');
    }
}
