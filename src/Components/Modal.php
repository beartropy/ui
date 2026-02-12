<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Modal Component.
 *
 * Renders a modal dialog overlay with Alpine.js state management,
 * optional Livewire `wire:model` sync, teleportation, and styled/unstyled modes.
 *
 * ## Blade Props
 *
 * ### Slots
 * @slot default Modal content.
 * @slot title   Modal title (styled mode applies preset classes).
 * @slot footer  Modal footer buttons/actions (styled mode applies preset classes).
 *
 * ### Props
 * @property string|null $id             Custom modal ID. Auto-generated if omitted.
 * @property string      $maxWidth       Max width key: sm, md, lg, xl, 2xl, 3xl (default), 4xl, 5xl, 6xl, 7xl, full.
 * @property string      $zIndex         Tailwind z-index number (default: 30).
 * @property string      $blur           Backdrop blur level: none (default), sm, md, lg, xl, 2xl, 3xl.
 * @property string      $bgColor        Background color classes (default: bg-white dark:bg-gray-900).
 * @property bool        $closeOnClickOutside Whether clicking the overlay closes the modal (default: true).
 * @property bool        $styled         Enables styled mode with padded wrapper, title border, footer border (default: false).
 * @property bool        $showCloseButton Whether to show the X close button (default: true).
 * @property bool        $centered       Centers modal vertically (default: false, shows with top margin).
 * @property bool        $teleport       Teleports modal to target element (default: true).
 * @property string      $teleportTarget Teleport target selector (default: body).
 */
class Modal extends BeartropyComponent
{
    /**
     * Create a new Modal component instance.
     */
    public function __construct(
        public ?string $id = null,
        public string $maxWidth = '3xl',
        public string $zIndex = '30',
        public string $blur = 'none',
        public string $bgColor = 'bg-white dark:bg-gray-900',
        public bool $closeOnClickOutside = true,
        public bool $styled = false,
        public bool $showCloseButton = true,
        public bool $centered = false,
        public bool $teleport = true,
        public string $teleportTarget = 'body',
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::modal');
    }
}
