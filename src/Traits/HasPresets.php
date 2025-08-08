<?php

namespace Beartropy\Ui\Traits;

trait HasPresets
{

    public function getComponentPresets($componentName = null, $attributes = null)
    {
        $componentName = $componentName ?: $this->componentName;
        $attributes    = $attributes ?: $this->attributes->getAttributes();
        $magicProps    = array_keys($attributes);

        #static $cache = [];

        // 1) Cargar presets y defaults (robusto ante null)
        [$sizes, $componentPresets, $colorsArray, $hasVariants] = $this->loadPresets($componentName);
        $defaults = $this->loadComponentDefaults($componentName);

        // 2) Heurísticas (sin hardcode)
        $supports = $this->computeSupports($sizes, $colorsArray, $defaults, $magicProps);

        // 3) Resolver size / variant / color / fill
        [$size, $sizePreset, $sizeOrigin]           = $this->resolveSize($supports, $sizes, $defaults, $magicProps);
        [$variant, $variantOrigin]                  = $this->resolveVariant($supports, $colorsArray, $componentPresets, $defaults, $magicProps, $hasVariants);
        [$color, $colorPreset, $colorOrigin]        = $this->resolveColor($supports, $colorsArray, $componentPresets, $defaults, $magicProps, $variant, $hasVariants);
        [$shouldFill, $fillOrigin]                  = $this->resolveFill($supports, $attributes, $defaults);

        // 4) Cache key
        #$cacheKey = $this->buildCacheKey($componentName, $supports, $size, $variant, $color, $shouldFill);
        #if (isset($cache[$cacheKey])) return $cache[$cacheKey];

        // 5) Sincronizar en la instancia (solo si existen las props)
        $this->syncInstanceProps($supports, $size, $variant, $color);

        // 6) Names para debug / slots
        $presetNames = $this->buildPresetNames($supports, $size, $variant, $color, $shouldFill, $sizeOrigin, $variantOrigin, $colorOrigin, $fillOrigin);

        // 7) Warnings (solo debug)
        $this->debugWarnings($supports, $sizePreset, $colorPreset, $componentName, $variant, $color);

        #return $cache[$cacheKey] = [$colorPreset, $sizePreset, $shouldFill, $presetNames];
        return [$colorPreset, $sizePreset, $shouldFill, $presetNames];
    }

    /* ========================
    |       Helpers
    ======================== */

    protected function loadPresets(string $componentName): array
    {
        $sizes            = (array) config('beartropyui.presets.sizes', []);
        $componentPresets = (array) config("beartropyui.presets.$componentName", []);
        $colorsArray      = (isset($componentPresets['colors']) && is_array($componentPresets['colors']))
            ? $componentPresets['colors'] : [];
        $hasVariants      = $this->detectHasVariants($colorsArray);

        return [$sizes, $componentPresets, $colorsArray, $hasVariants];
    }

    protected function loadComponentDefaults(string $componentName): array
    {
        $componentDefaults = config("beartropyui.component_defaults.$componentName");
        if (!is_array($componentDefaults)) $componentDefaults = [];

        return [
            'color'   => $componentDefaults['color']   ?? null,
            'size'    => $componentDefaults['size']    ?? null,
            'variant' => $componentDefaults['variant'] ?? null,
            'outline' => array_key_exists('outline', $componentDefaults) ? (bool) $componentDefaults['outline'] : null,
        ];
    }

    protected function detectHasVariants(array $colors): bool
    {
        if (empty($colors)) return false;
        foreach ($colors as $maybeVariant) {
            if (!is_array($maybeVariant) || empty($maybeVariant)) return false;
            foreach ($maybeVariant as $maybeColor) {
                if (!is_array($maybeColor) || empty($maybeColor)) return false;
            }
        }
        return true;
    }

    protected function computeSupports(array $sizes, array $colorsArray, array $defaults, array $magicProps): array
    {
        $magicHasSizeToken = !empty(array_intersect(array_keys($sizes), $magicProps));

        return [
            'size'     => $magicHasSizeToken || ($defaults['size'] !== null) || property_exists($this, 'size'),
            'variant'  => $this->detectHasVariants($colorsArray)
                        || ($defaults['variant'] !== null)
                        || in_array('variant', $magicProps, true)
                        || property_exists($this, 'variant'),
            'color'    => !empty($colorsArray),
            'outline'  => array_key_exists('outline', array_flip($magicProps)) // rápido
                        || ($defaults['outline'] !== null)
                        || property_exists($this, 'outline'),
            'fill'     => in_array('fill', $magicProps, true) || property_exists($this, 'fill'),
        ];
    }

    protected function resolveSize(array $supports, array $sizes, array $defaults, array $magicProps): array
    {
        $size = null; $origin = 'default';
        if ($supports['size']) {
            if (property_exists($this, 'size') && $this->size !== null) { $size = $this->size; $origin = 'prop'; }
            if (!$size) {
                foreach ($magicProps as $maybeSize) {
                    if (isset($sizes[$maybeSize])) { $size = $maybeSize; $origin = 'attr'; break; }
                }
            }
            if (!$size && $defaults['size']) { $size = $defaults['size']; $origin = 'config'; }
            if (!$size) { $size = 'md'; $origin = 'fallback'; }
        }
        $sizePreset = ($supports['size'] && $size && isset($sizes[$size])) ? $sizes[$size] : ($sizes['md'] ?? []);
        return [$size, $sizePreset, $origin];
    }

    protected function resolveVariant(array $supports, array $colorsArray, array $componentPresets, array $defaults, array $magicProps, bool $hasVariants): array
    {
        $variant = null; $origin = 'default';

        if (!$supports['variant']) return [null, $origin];

        if (property_exists($this, 'variant') && $this->variant !== null) { $variant = $this->variant; $origin = 'prop'; }

        if ($hasVariants) {
            $all = array_keys($colorsArray);
            if (!$variant) {
                foreach ($magicProps as $maybe) {
                    if (in_array($maybe, $all, true)) { $variant = $maybe; $origin = 'attr'; break; }
                }
            }
            if (!$variant && $defaults['variant'] && in_array($defaults['variant'], $all, true)) {
                $variant = $defaults['variant']; $origin = 'config';
            }
            if (!$variant) { $variant = $componentPresets['default_variant'] ?? ($all[0] ?? null); $origin = 'fallback'; }
        } else {
            if (!$variant && $defaults['variant'] !== null) { $variant = $defaults['variant']; $origin = 'config'; }
            elseif (!$variant && isset($componentPresets['default_variant'])) { $variant = $componentPresets['default_variant']; $origin = 'fallback'; }
        }

        return [$variant, $origin];
    }

    protected function resolveColor(array $supports, array $colorsArray, array $componentPresets, array $defaults, array $magicProps, ?string $variant, bool $hasVariants): array
    {
        $color = null; $origin = 'default'; $colorPreset = [];

        if (!$supports['color']) return [null, [], $origin];

        if (property_exists($this, 'color') && $this->color !== null) { $color = $this->color; $origin = 'prop'; }

        if ($hasVariants) {
            $valid = ($variant && isset($colorsArray[$variant])) ? array_keys($colorsArray[$variant]) : [];
            if (!$color) {
                foreach ($magicProps as $maybe) {
                    if (in_array($maybe, $valid, true)) { $color = $maybe; $origin = 'attr'; break; }
                }
            }
            if (!$color && $defaults['color'] && in_array($defaults['color'], $valid, true)) {
                $color = $defaults['color']; $origin = 'config';
            }
            $presetDefault = $componentPresets['default_color'] ?? ($valid[0] ?? null);
            if (!$color || !in_array($color, $valid, true)) { $color = $presetDefault; $origin = $origin === 'default' ? 'fallback' : $origin; }

            $colorPreset = $colorsArray[$variant][$color]
                ?? (isset($componentPresets['default_variant'], $componentPresets['default_color'])
                        ? ($colorsArray[$componentPresets['default_variant']][$componentPresets['default_color']] ?? [])
                        : []);
        } else {
            $valid = array_keys($colorsArray);
            if (!$color) {
                foreach ($magicProps as $maybe) {
                    if (in_array($maybe, $valid, true)) { $color = $maybe; $origin = 'attr'; break; }
                }
            }
            if (!$color && $defaults['color'] && in_array($defaults['color'], $valid, true)) {
                $color = $defaults['color']; $origin = 'config';
            }
            $presetDefault = $componentPresets['default_color'] ?? ($valid[0] ?? null);
            if (!$color || !in_array($color, $valid, true)) { $color = $presetDefault; $origin = $origin === 'default' ? 'fallback' : $origin; }

            $colorPreset = $colorsArray[$color] ?? (is_array($colorsArray) ? reset($colorsArray) : []);
        }

        return [$color, $colorPreset, $origin];
    }

    protected function resolveFill(array $supports, array $attributes, array $defaults): array
    {
        // Precedencia:
        // 1) fill prop/attr → true/valor
        // 2) outline prop/attr → !outline
        // 3) outline config → !outline
        // 4) default false
        $shouldFill = null; $origin = 'default';

        if ($supports['fill']) {
            if (property_exists($this, 'fill') && $this->fill !== null) {
                $shouldFill = (bool) $this->fill; $origin = 'prop';
            } elseif (array_key_exists('fill', $attributes)) {
                $val = filter_var($attributes['fill'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                $shouldFill = $val === null ? true : $val; $origin = 'attr';
            }
        }

        if ($shouldFill === null && $supports['outline']) {
            if (property_exists($this, 'outline') && $this->outline !== null) {
                $shouldFill = !(bool) $this->outline; $origin = 'prop_outline';
            } elseif (array_key_exists('outline', $attributes)) {
                $val = filter_var($attributes['outline'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                $val = $val === null ? true : $val;
                $shouldFill = !$val; $origin = 'attr_outline';
            }
        }

        if ($shouldFill === null && $defaults['outline'] !== null) {
            $shouldFill = !(bool) $defaults['outline']; $origin = 'config_outline';
        }

        if ($shouldFill === null) { $shouldFill = false; /* origin default */ }

        return [$shouldFill, $origin];
    }

    protected function buildCacheKey(string $component, array $supports, ?string $size, ?string $variant, ?string $color, bool $shouldFill): string
    {
        $parts = [$component];
        $parts[] = $supports['size']    ? "size:".($size ?? '-') : 'size:-';
        $parts[] = $supports['variant'] ? "variant:".($variant ?? '-') : 'variant:-';
        $parts[] = $supports['color']   ? "color:".($color ?? '-') : 'color:-';
        $parts[] = ($supports['fill'] || $supports['outline']) ? 'fill:'.($shouldFill ? '1' : '0') : 'fill:-';
        return implode('|', $parts);
    }

    protected function syncInstanceProps(array $supports, ?string $size, ?string $variant, ?string $color): void
    {
        if ($supports['size']    && property_exists($this, 'size'))    { $this->size = $size; }
        if ($supports['variant'] && property_exists($this, 'variant')) { $this->variant = $variant; }
        if ($supports['color']   && property_exists($this, 'color'))   { $this->color = $color; }
    }

    protected function buildPresetNames(array $supports, ?string $size, ?string $variant, ?string $color, bool $shouldFill, string $sizeOrigin, string $variantOrigin, string $colorOrigin, string $fillOrigin): array
    {
        $names = [];
        if ($supports['size']    && $sizeOrigin   !== 'fallback' && $size    !== null) { $names['size']    = $size; }
        if ($supports['variant'] && $variant                  !== null) { $names['variant'] = $variant; }
        if ($supports['color']   && $color                    !== null) { $names['color']   = $color; }
        if (($supports['fill'] || $supports['outline']) && $fillOrigin !== 'default') { $names['fill'] = $shouldFill; }
        return $names;
    }

    protected function debugWarnings(array $supports, array $sizePreset, array $colorPreset, string $componentName, ?string $variant, ?string $color): void
    {
        if (!config('app.debug')) return;
        if ($supports['color'] && empty($colorPreset)) {
            logger()->warning("Beartropy: color preset '".($color ?? '—')."' no encontrado para '$componentName' (variant: ".($variant ?? '—').").");
        }
        if ($supports['size'] && empty($sizePreset)) {
            logger()->warning("Beartropy: size preset '".($size ?? '—')."' no encontrado para '$componentName'.");
        }
    }

}