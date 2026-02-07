<?php

namespace Beartropy\Ui\Components\Base;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\Contracts\View\View;

/**
 * Base class for Button logic.
 *
 * Handles properties common to all button implementations, including icon, spinner,
 * tag type (a/button), and wire:target.
 */
class ButtonBase extends BeartropyComponent
{
    public function __construct(
        public ?string $iconStart = null,
        public ?string $iconEnd = null,
        public ?bool $spinner = null,
        public ?string $tag = null,
        public ?string $type = null,
        public ?string $href = null,
        public ?bool $disabled = false,
        public ?string $size = null,
        public ?string $color = null,
        public ?string $variant = null,
        public ?string $iconSet = null,
        public ?string $iconVariant = null,
    ) {
        $this->iconSet = $iconSet ?? config('beartropyui.icons.set', 'heroicons');
        $this->iconVariant = $iconVariant ?? config('beartropyui.icons.variant', 'outline');
    }

    /**
     * Determine the HTML tag to use.
     *
     * Returns 'a' if href attribute is present, otherwise 'button'.
     */
    public function getTag(): string
    {
        if ($this->attributes->has('href')) {
            return 'a';
        }

        return 'button';
    }

    /**
     * Get the Livewire target for loading states.
     *
     * Infers target from wire:target or wire:click if not explicitly set.
     */
    public function getWireTarget(): ?string
    {
        $wireTarget = $this->attributes->get('wire:target');
        if (!$wireTarget && $this->attributes->has('wire:click')) {
            $wireClick = $this->attributes->get('wire:click');
            if (preg_match('/^\s*([a-zA-Z0-9_]+)/', $wireClick, $matches)) {
                $wireTarget = $matches[1];
            }
        }

        return $wireTarget;
    }

    public function render(): View
    {
        return view('beartropy-ui::base.button-base');
    }
}
