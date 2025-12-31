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

    use
        HasPresets,
        HasErrorBag;

    public function __construct() {}

    /**
     * Determine the error state for a specific field.
     *
     * Checks both the session error bag and specific error attributes.
     * Often used to style input fields when validation fails.
     *
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes Component attributes.
     * @param \Illuminate\Support\MessageBag|null       $errors     Global error bag.
     * @param string|null                               $error      Specific error message override.
     *
     * @return array{0: bool, 1: string|null} An array containing [hasError, firstErrorMessage].
     */
    public function getErrorState($attributes = null, $errors = null, $error = null)
    {
        $attributes = $attributes ?: $this->attributes;
        $errors = $errors ?: session('errors', app('view')->shared('errors', new \Illuminate\Support\MessageBag));

        // Ejemplo de cómo buscar el wire:model asociado
        $wireModel = $attributes->wire('model')->value();
        $inputName = $attributes->get('name') ?? $wireModel;

        $errorFromBag = $inputName && $errors->has($inputName) ? $errors->first($inputName) : null;
        $finalError = $errorFromBag ?: $error;
        $hasError = !!$finalError;

        return [$hasError, $finalError];
    }

    /**
     * Analyze `wire:model` attributes to determine binding state.
     *
     * Detects if `wire:model` is present and if it uses dot notation (e.g., `user.name`).
     *
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes Component attributes.
     *
     * @return array{0: bool, 1: string|null, 2: bool, 3: string|null} [hasWireModel, wireModelValue, hasDotNotation, parsedDotNotationValue]
     */
    public function getWireModelState($attributes = null)
    {
        $attributes = $attributes ?: $this->attributes;
        $wireModelValue = $attributes->wire('model')->value();

        $hasWireModel = ($wireModelValue) ? true : false;
        $hasDotNotation = false;
        $parsedDotNotationValue = null;

        if ($hasWireModel && preg_match('/\.(\d+)/', $wireModelValue)) {
            $hasDotNotation = true;
            $parsedDotNotationValue = preg_replace('/\.(\d+)/', '[$1]', $wireModelValue);
        }

        return [$hasWireModel, $wireModelValue, $hasDotNotation, $parsedDotNotationValue];
    }

    /**
     * Get the size preset configuration.
     *
     * Legacy method for size resolution. Prioritizes `size` property, then magic attributes.
     *
     * @param string|null                         $componentName UNUSED.
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes    Component attributes to check for magic props.
     *
     * @return array<string, mixed> The size configuration array.
     */
    public function getComponentSizePreset($componentName = null, $attributes = null)
    {

        $magicProps = array_keys($attributes);

        $sizePreset = false;

        if (!is_null($this->size) && $this->size !== '') {
            $sizePreset = config('beartropyui.presets.sizes.' . $this->size);
        } else {
            $sizes = config('beartropyui.presets.sizes');
            foreach ($magicProps as $size) {
                if (isset($sizes[$size])) {
                    $sizePreset = $sizes[$size];
                    $this->size = $size;
                    break;
                }
            }
        }
        if (!$sizePreset) {
            $sizePreset = $sizes['md'] ??  config('beartropyui.presets.sizes.md');
            $this->size = 'md';
        }

        return $sizePreset;
    }

    /**
     * Resolve the size preset for the component.
     *
     * Checks:
     * 1. Explicit `$this->size`.
     * 2. Magic attributes (e.g., `<x-button sm />`).
     * 3. Default size.
     *
     * @param string|null $componentName  Component name (used for context, optionally).
     * @param \Illuminate\View\ComponentAttributeBag|array|null  $attributes     Attributes array or bag.
     * @param string      $defaultSize    Default size key (default: 'md').
     *
     * @return array<string, mixed> The resolved size preset configuration.
     */
    public function getSizePreset($componentName = null, $attributes = null, $defaultSize = 'md')
    {
        $componentName = $componentName ?: $this->componentName;
        $attributes = $attributes ?: $this->attributes->getAttributes();

        $magicProps = array_keys($attributes);

        // --- SIZE ---
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

    /**
     * Resolve the color preset configuration.
     *
     * Handles color resolution based on component config, variants, and defaults.
     *
     * @param string      $component Component key in configuration (e.g., 'button').
     * @param string|null $color     Explicit color name.
     * @param string|null $variant   Variant name (e.g., 'solid', 'outline').
     *
     * @return array<string, mixed> The color configuration/classes.
     */
    public function getColorPreset($component, $color = null, $variant = null)
    {
        $preset = config("beartropyui.presets.{$component}");

        // Para componentes con variante (ej: button)
        if ($variant && isset($preset['colors'][$variant])) {
            $finalVariant = $variant ?? $preset['default_variant'] ?? 'solid';
            $finalColor = $color ?? $preset['default_color'] ?? 'beartropy';

            return $preset['colors'][$finalVariant][$finalColor]
                ?? $preset['colors'][$finalVariant][$preset['default_color']]
                ?? $preset['colors'][$finalVariant]['beartropy']
                ?? $preset['colors']['solid']['beartropy'];
        }

        // Para componentes sin variantes
        $finalColor = $color ?? $preset['default_color'] ?? 'beartropy';
        return $preset['colors'][$finalColor] ?? $preset['colors'][$preset['default_color']];
    }
}
