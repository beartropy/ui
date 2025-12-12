<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Traits\HasErrorBag;
use Beartropy\Ui\Traits\HasPresets;
use Illuminate\View\Component;

abstract class BeartropyComponent extends Component
{

    use
        HasPresets,
        HasErrorBag
        ;

    public function __construct() {}

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

    public function getComponentSizePreset($componentName = null, $attributes = null) {

        $magicProps = array_keys($attributes);

        $sizePreset = false;

        if(!is_null($this->size) && $this->size !== '') {
            $sizePreset = config('beartropyui.presets.sizes.'.$this->size);
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
        if(!$sizePreset) {
            $sizePreset = $sizes['md'] ??  config('beartropyui.presets.sizes.md');
            $this->size = 'md';
        }

        return $sizePreset;
    }

    public function getSizePreset($componentName = null, $attributes = null, $defaultSize = 'md') {
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
