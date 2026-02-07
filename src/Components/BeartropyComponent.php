<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Traits\HasErrorBag;
use Beartropy\Ui\Traits\HasPresets;
use Illuminate\View\Component;

/**
 * Base component class for all Beartropy UI components.
 *
 * Provides common functionality for:
 * - Error handling and validation state retrieval.
 * - Livewire `wire:model` state parsing.
 * - Size and Color preset resolution from configuration.
 *
 * @property string|null $size Component size (md, lg, etc.) used for preset resolution.
 */
abstract class BeartropyComponent extends Component
{
    use HasPresets;
    use HasErrorBag;

    /**
     * Analyze `wire:model` attributes to determine binding state.
     *
     * Detects if `wire:model` is present and if it uses dot notation (e.g., `user.name`).
     *
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes Component attributes.
     *
     * @return array{0: bool, 1: string|null, 2: bool, 3: string|null} [hasWireModel, wireModelValue, hasDotNotation, parsedDotNotationValue]
     */
    public function getWireModelState(?\Illuminate\View\ComponentAttributeBag $attributes = null): array
    {
        $attributes = $attributes ?: $this->attributes;
        $wireModelValue = $attributes->wire('model')->value();

        $hasWireModel = (bool) $wireModelValue;
        $hasDotNotation = false;
        $parsedDotNotationValue = null;

        if ($hasWireModel && preg_match('/\.(\d+)/', $wireModelValue)) {
            $hasDotNotation = true;
            $parsedDotNotationValue = preg_replace('/\.(\d+)/', '[$1]', $wireModelValue);
        }

        return [$hasWireModel, $wireModelValue, $hasDotNotation, $parsedDotNotationValue];
    }

    /**
     * Resolve the size preset for the component.
     *
     * Checks:
     * 1. Explicit `$this->size`.
     * 2. Magic attributes (e.g., `<x-button sm />`).
     * 3. Default size.
     *
     * @param string|null                                          $componentName Component name (used for context).
     * @param \Illuminate\View\ComponentAttributeBag|array<string, mixed>|null $attributes    Attributes array or bag.
     * @param string                                               $defaultSize   Default size key.
     *
     * @return array<string, mixed> The resolved size preset configuration.
     */
    public function getSizePreset(?string $componentName = null, array|\Illuminate\View\ComponentAttributeBag|null $attributes = null, string $defaultSize = 'md'): array
    {
        $componentName = $componentName ?: $this->componentName;
        $attributes = $attributes ?: $this->attributes->getAttributes();

        if ($attributes instanceof \Illuminate\View\ComponentAttributeBag) {
            $attributes = $attributes->getAttributes();
        }

        $magicProps = array_keys($attributes);

        $sizes = config('beartropyui.presets.sizes');
        $size = $this->size ?? null;
        if (!$size) {
            foreach ($magicProps as $maybeSize) {
                if (isset($sizes[$maybeSize])) {
                    $size = $maybeSize;
                    break;
                }
            }
        }
        $size = $size ?: $defaultSize;
        $sizePreset = $sizes[$size] ?? $sizes['md'];

        return $sizePreset;
    }
}
