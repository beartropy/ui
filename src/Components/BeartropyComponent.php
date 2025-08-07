<?php

namespace Beartropy\Ui\Components;

use Beartropy\Ui\Traits\HasErrorBag;
use Beartropy\Ui\Traits\HasPresets;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\Throw_;

abstract class BeartropyComponent extends Component
{

    use
        HasErrorBag
        ;

    public function __construct() {}

/*     public function getExtraRenderData()
    {
        return $this->getErrorState();
    } */

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

        return [$hasWireModel, $wireModelValue];

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

    public function getComponentPresets($componentName = null, $attributes = null, $defaultSize = 'md', $defaultColor = null, $defaultVariant = null)
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

        // --- COMPONENT PRESETS ---
        $componentPresets = config('beartropyui.presets.' . $componentName);

        // --- Detect variantes ---
        $colorsArray = $componentPresets['colors'] ?? [];
        $firstValue = reset($colorsArray);
        $hasVariants = is_array($firstValue) && is_array(reset($firstValue)); // <--- Robusto

        // --- VARIANT (solo si hay variantes) ---
        $variant = property_exists($this, 'variant') ? $this->variant ?? null : null;
        if ($hasVariants) {
            $allVariants = array_keys($colorsArray);
            if (!$variant) {
                foreach ($magicProps as $maybeVariant) {
                    if (in_array($maybeVariant, $allVariants)) {
                        $variant = $maybeVariant;
                        break;
                    }
                }
            }
            if (!$variant) {
                $variant = $componentPresets['default_variant'] ?? $allVariants[0] ?? null;
            }
        } else {
            $variant = null;
        }

        // --- COLOR ---
        $color = $this->color ?? null;
        if ($hasVariants) {
            // Colores válidos dentro de la variante actual
            $validColors = $variant && isset($colorsArray[$variant])
                ? array_keys($colorsArray[$variant])
                : [];

            if (!$color) {
                foreach ($magicProps as $maybeColor) {
                    if (in_array($maybeColor, $validColors)) {
                        $color = $maybeColor;
                        break;
                    }
                }
            }
            $defaultColor = $componentPresets['default_color'] ?? $validColors[0] ?? null;
            if (!$color || !in_array($color, $validColors)) {
                $color = $defaultColor;
            }

            // Resuelve el preset final
            try {
                $colorPreset = $colorsArray[$variant][$color] ?? $colorsArray[$componentPresets['default_variant']][$componentPresets['default_color']];
            } catch (\Throwable $th) {
                throw new \Exception("Color preset for component '{$componentName}' not found. Variant: {$variant}, Color: {$color}");
            }
        } else {
            // Sin variantes: colores de primer nivel
            $validColors = array_keys($colorsArray);

            if (!$color) {
                foreach ($magicProps as $maybeColor) {
                    if (in_array($maybeColor, $validColors)) {
                        $color = $maybeColor;
                        break;
                    }
                }
            }
            $defaultColor = $componentPresets['default_color'] ?? $validColors[0] ?? null;
            if (!$color || !in_array($color, $validColors)) {
                $color = $defaultColor;
            }

            // Resuelve el preset final
            $colorPreset = $colorsArray[$color] ?? reset($colorsArray);
        }

        // --- Opcional: Sincronizá los valores resueltos a la instancia ---
        $this->size = $size;
        if (property_exists($this, 'variant')) $this->variant = $variant;
        $this->color = $color;

        $presetNames = [
            'size' => $size,
            'variant' => $variant,
            'color' => $color
        ];
        return [$colorPreset, $sizePreset, $presetNames];
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
