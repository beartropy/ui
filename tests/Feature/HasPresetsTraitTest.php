<?php

use Illuminate\Support\Facades\Config;
use Illuminate\View\ComponentAttributeBag;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

trait UsesHasPresetsTrait
{
    use Beartropy\Ui\Traits\HasPresets; // <= TODO: cambia al namespace real del trait
}

class HasPresetsStub
{
    use UsesHasPresetsTrait;

    // Props opcionales: simulamos componentes que podrían o no declararlas
    public ?string $size = null;
    public ?string $color = null;
    public ?string $variant = null;
    public $outline = null;
    public $fill = null;

    // ——— Wrappers públicos para helpers protegidos ———
    public function callLoadPresets(string $name): array
    {
        return $this->loadPresets($name);
    }
    public function callLoadComponentDefaults(string $name): array
    {
        return $this->loadComponentDefaults($name);
    }
    public function callDetectHasVariants(array $colors): bool
    {
        return $this->detectHasVariants($colors);
    }
    public function callComputeSupports(array $sizes, array $colorsArray, array $defaults, array $magicProps): array
    {
        return $this->computeSupports($sizes, $colorsArray, $defaults, $magicProps);
    }
    public function callResolveSize(array $supports, array $sizes, array $defaults, array $magicProps): array
    {
        return $this->resolveSize($supports, $sizes, $defaults, $magicProps);
    }
    public function callResolveVariant(array $supports, array $colorsArray, array $componentPresets, array $defaults, array $magicProps, bool $hasVariants): array
    {
        return $this->resolveVariant($supports, $colorsArray, $componentPresets, $defaults, $magicProps, $hasVariants);
    }
    public function callResolveColor(array $supports, array $colorsArray, array $componentPresets, array $defaults, array $magicProps, ?string $variant, bool $hasVariants): array
    {
        return $this->resolveColor($supports, $colorsArray, $componentPresets, $defaults, $magicProps, $variant, $hasVariants);
    }
    public function callResolveFill(array $supports, array $attributes, array $defaults): array
    {
        return $this->resolveFill($supports, $attributes, $defaults);
    }
    public function callBuildCacheKey(string $component, array $supports, ?string $size, ?string $variant, ?string $color, bool $shouldFill): string
    {
        return $this->buildCacheKey($component, $supports, $size, $variant, $color, $shouldFill);
    }
    public function callSyncInstanceProps(array $supports, ?string $size, ?string $variant, ?string $color): void
    {
        $this->syncInstanceProps($supports, $size, $variant, $color);
    }
    public function callBuildPresetNames(array $supports, ?string $size, ?string $variant, ?string $color, bool $shouldFill, string $sizeOrigin, string $variantOrigin, string $colorOrigin, string $fillOrigin): array
    {
        return $this->buildPresetNames($supports, $size, $variant, $color, $shouldFill, $sizeOrigin, $variantOrigin, $colorOrigin, $fillOrigin);
    }
    public function callGetComponentPresets(?string $name = null, $attrs = null): array
    {
        // Para una prueba end-to-end del método público
        // Simulamos la API: setear 'componentName' y 'attributes' como en el componente real.
        $this->componentName = $name ?? 'input';
        $this->attributes = $attrs instanceof ComponentAttributeBag ? $attrs : new ComponentAttributeBag($attrs ?? []);

        return $this->getComponentPresets($name, $this->attributes->getAttributes());
    }
}

beforeEach(function () {
    // Reset de config
    Config::set('beartropyui', []);

    // Presets globales básicos
    Config::set('beartropyui.presets.sizes', [
        'sm' => ['font' => 'text-sm'],
        'md' => ['font' => 'text-base'],
        'lg' => ['font' => 'text-lg'],
    ]);

    // Presets sin variants (input)
    Config::set('beartropyui.presets.input', [
        'default_color' => 'beartropy',
        'colors' => [
            'beartropy' => ['bg' => 'bg-white', 'text' => 'text-black'],
            'red'       => ['bg' => 'bg-red-100', 'text' => 'text-red-900'],
        ],
    ]);

    // Presets con variants (button)
    Config::set('beartropyui.presets.button', [
        'default_variant' => 'solid',
        'default_color'   => 'beartropy',
        'colors' => [
            'solid' => [
                'beartropy' => ['bg' => 'bg-bear-600', 'text' => 'text-white'],
                'red'       => ['bg' => 'bg-red-600',  'text' => 'text-white'],
            ],
            'ghost' => [
                'beartropy' => ['bg' => 'bg-transparent', 'text' => 'text-bear-600'],
            ],
        ],
    ]);
});

it('detectHasVariants distingue entre estructuras con y sin variants', function () {
    $stub = new HasPresetsStub();

    $with = [
        'solid' => [
            'beartropy' => ['bg' => 'x', 'text' => 'y'],
        ],
    ];
    $without = [
        'beartropy' => ['bg' => 'x', 'text' => 'y'],
    ];

    expect($stub->callDetectHasVariants($with))->toBeTrue()
        ->and($stub->callDetectHasVariants($without))->toBeFalse()
        ->and($stub->callDetectHasVariants([]))->toBeFalse();
});

it('computeSupports activa flags sin hardcodear props', function () {
    $stub = new HasPresetsStub();
    $sizes = Config::get('beartropyui.presets.sizes');
    $colors = Config::get('beartropyui.presets.input.colors');

    $supports = $stub->callComputeSupports($sizes, $colors, ['size' => null, 'variant' => null, 'color' => null, 'outline' => null], ['md', 'fill']);

    expect($supports['size'])->toBeTrue()     // por magic prop md
        ->and($supports['color'])->toBeTrue() // porque hay colors en presets
        ->and($supports['fill'])->toBeTrue()  // por magic prop fill
        ->and($supports['variant'])->toBeTrue(); // input no tiene variants
});

it('resolveSize respeta precedencia: prop > attr > config > fallback', function () {
    $stub = new HasPresetsStub();
    $sizes = Config::get('beartropyui.presets.sizes');

    // Caso 1: prop
    $stub->size = 'lg';
    [$size, $preset, $origin] = $stub->callResolveSize(['size' => true], $sizes, ['size' => 'sm'], []);
    expect($size)->toBe('lg')->and($origin)->toBe('prop')->and($preset['font'])->toBe('text-lg');

    // Caso 2: attr
    $stub = new HasPresetsStub();
    [$size, $preset, $origin] = $stub->callResolveSize(['size' => true], $sizes, ['size' => 'sm'], ['md']);
    expect($size)->toBe('md')->and($origin)->toBe('attr')->and($preset['font'])->toBe('text-base');

    // Caso 3: config
    $stub = new HasPresetsStub();
    [$size, $preset, $origin] = $stub->callResolveSize(['size' => true], $sizes, ['size' => 'sm'], []);
    expect($size)->toBe('sm')->and($origin)->toBe('config');

    // Caso 4: fallback
    $stub = new HasPresetsStub();
    [$size, $preset, $origin] = $stub->callResolveSize(['size' => true], $sizes, ['size' => null], []);
    expect($size)->toBe('md')->and($origin)->toBe('fallback');
});

it('resolveVariant funciona con y sin variants y con magic prop', function () {
    $stub = new HasPresetsStub();
    $buttonColors = Config::get('beartropyui.presets.button.colors');

    // con variants: magic prop
    [$variant, $origin] = $stub->callResolveVariant(['variant' => true], $buttonColors, Config::get('beartropyui.presets.button'), ['variant' => null], ['ghost'], true);
    expect($variant)->toBe('ghost')->and($origin)->toBe('attr');

    // sin variants: conserva config/default
    $inputColors = Config::get('beartropyui.presets.input.colors');
    [$variant, $origin] = $stub->callResolveVariant(['variant' => true], $inputColors, Config::get('beartropyui.presets.input'), ['variant' => 'alt'], [], false);
    expect($variant)->toBe('alt')->and($origin)->toBe('config');
});

it('resolveColor elige color válido (con y sin variants) y respeta precedencia', function () {
    $stub = new HasPresetsStub();

    // button (con variants): variant=ghost, color fallback -> beartropy
    $buttonPresets = Config::get('beartropyui.presets.button');
    [$color, $preset, $origin] = $stub->callResolveColor(['color' => true], $buttonPresets['colors'], $buttonPresets, ['color' => null], [], 'ghost', true);
    expect($color)->toBe('beartropy')->and($preset['text'])->toBe('text-bear-600');

    // input (sin variants): magic prop red gana
    $inputPresets = Config::get('beartropyui.presets.input');
    [$color, $preset, $origin] = $stub->callResolveColor(['color' => true], $inputPresets['colors'], $inputPresets, ['color' => null], ['red'], null, false);
    expect($color)->toBe('red')->and($preset['bg'])->toBe('bg-red-100')->and($origin)->toBe('attr');
});

it('resolveFill: fill prop/attr > outline prop/attr > config outline > default', function () {
    $stub = new HasPresetsStub();

    // 1) fill prop
    $stub->fill = true;
    [$fill, $origin] = $stub->callResolveFill(['fill' => true, 'outline' => true], [], ['outline' => false]);
    expect($fill)->toBeTrue()->and($origin)->toBe('prop');

    // 2) outline attr (gana si no hay fill)
    $stub = new HasPresetsStub();
    [$fill, $origin] = $stub->callResolveFill(['fill' => true, 'outline' => true], ['outline' => false], ['outline' => true]);
    expect($fill)->toBeTrue()->and($origin)->toBe('attr_outline');

    // 3) outline config
    $stub = new HasPresetsStub();
    [$fill, $origin] = $stub->callResolveFill(['fill' => false, 'outline' => true], [], ['outline' => false]);
    expect($fill)->toBeTrue()->and($origin)->toBe('config_outline');

    // 4) default false
    $stub = new HasPresetsStub();
    [$fill, $origin] = $stub->callResolveFill(['fill' => false, 'outline' => false], [], ['outline' => null]);
    expect($fill)->toBeFalse()->and($origin)->toBe('default');
});

it('buildCacheKey refleja solo lo que aplica', function () {
    $stub = new HasPresetsStub();
    $key = $stub->callBuildCacheKey('button', ['size'=>true,'variant'=>true,'color'=>true,'fill'=>true,'outline'=>true], 'md', 'solid', 'beartropy', true);
    expect($key)->toBe('button|size:md|variant:solid|color:beartropy|fill:1');

    $key = $stub->callBuildCacheKey('input', ['size'=>false,'variant'=>false,'color'=>true,'fill'=>false,'outline'=>false], null, null, 'red', false);
    expect($key)->toBe('input|size:-|variant:-|color:red|fill:-');
});

it('syncInstanceProps solo asigna si la prop existe', function () {
    $stub = new HasPresetsStub();
    $stub->callSyncInstanceProps(['size'=>true,'variant'=>true,'color'=>true], 'lg', 'ghost', 'red');
    expect($stub->size)->toBe('lg')
        ->and($stub->variant)->toBe('ghost')
        ->and($stub->color)->toBe('red');
});

it('buildPresetNames aplica reglas: color/variant siempre, size solo si no es fallback, fill solo si no es default', function () {
    $stub = new HasPresetsStub();

    $names = $stub->callBuildPresetNames(
        ['size'=>true,'variant'=>true,'color'=>true,'fill'=>true,'outline'=>true],
        'md', 'solid', 'beartropy', true,
        'config', 'attr', 'fallback', 'prop' // size from config, variant from attr, color fallback, fill from prop
    );

    expect($names)->toMatchArray([
        'size' => 'md',
        'variant' => 'solid',
        'color' => 'beartropy',
        'fill' => true,
    ]);

    // Si size fuera fallback, no se incluye
    $names = $stub->callBuildPresetNames(
        ['size'=>true,'variant'=>true,'color'=>true,'fill'=>false,'outline'=>false],
        'md', 'solid', 'beartropy', false,
        'fallback', 'fallback', 'fallback', 'default'
    );
    expect($names)->not()->toHaveKey('size')
        ->and($names)->toHaveKeys(['variant','color'])
        ->and($names)->not()->toHaveKey('fill');
});

it('getComponentPresets e2e: button con magic variant y color fallback + fill prop', function () {
    // defaults
    Config::set('beartropyui.component_defaults.button', [
        'variant' => 'solid',
        'color'   => 'red',
        'size'    => 'md',
        'outline' => true,
    ]);

    $stub = new HasPresetsStub();
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $stub->callGetComponentPresets('button', ['ghost' => true, 'fill' => true]);

    expect($shouldFill)->toBeTrue()
        ->and($presetNames['variant'])->toBe('ghost')
        ->and($presetNames['color'])->toBe('beartropy') // fallback dentro de ghost
        ->and($sizePreset['font'])->toBe('text-base')
        ->and($colorPreset['text'])->toBe('text-bear-600');
});
