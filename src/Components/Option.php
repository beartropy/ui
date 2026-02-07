<?php

namespace Beartropy\Ui\Components;

use Illuminate\View\Component;

/**
 * Option Component (data-only).
 *
 * Declarative child of Select — pushes normalized option data
 * to Select::$pendingSlotOptions during Blade slot evaluation.
 * Renders nothing.
 *
 * Usage:
 *   <x-bt-select name="country">
 *       <x-bt-option value="AR" label="Argentina" />
 *       <x-bt-option value="US" label="United States" icon="flag" />
 *   </x-bt-select>
 */
class Option extends Component
{
    /**
     * @param string      $value       Option value (required).
     * @param string|null $label       Display label (defaults to $value).
     * @param string|null $icon        Icon name, emoji, or raw SVG.
     * @param string|null $avatar      Avatar URL or emoji.
     * @param string|null $description Secondary description text.
     */
    public function __construct(
        public string $value,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $avatar = null,
        public ?string $description = null,
    ) {
        Select::$pendingSlotOptions[] = [
            '_value' => $this->value,
            'label' => $this->label ?? $this->value,
            'icon' => Select::renderIcon($this->icon),
            'avatar' => $this->avatar,
            'description' => $this->description,
        ];
    }

    /**
     * Render nothing — this is a data-only component.
     */
    public function render(): \Closure
    {
        return fn () => '';
    }
}
