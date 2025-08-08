<?php

use Illuminate\Support\Facades\Config;
use Illuminate\View\ComponentAttributeBag;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});


class TestableComponent extends \Beartropy\Ui\Components\BeartropyComponent
{
    // NO redeclarar $componentName ni $attributes (los trae el padre)

    // Solo declaramos las que tu base usa pero no existen en el padre
    public ?string $size = null;
    public ?string $color = null;
    public ?string $variant = null;
    public $outline = null; // bool|null
    public $fill = null;    // bool|null

    public function __construct(string $componentName, array|ComponentAttributeBag $attrs = [])
    {
        // usamos las propiedades heredadas
        $this->componentName = $componentName;
        $this->attributes = $attrs instanceof ComponentAttributeBag
            ? $attrs
            : new ComponentAttributeBag($attrs);
    }

    public function render()
    {
        return '';
    }
}

beforeEach(function () {
    // Presets globales de sizes (mínimos para test)
    Config::set('beartropyui.presets.sizes', [
        'sm' => ['font' => 'text-sm'],
        'md' => ['font' => 'text-base'],
        'lg' => ['font' => 'text-lg'],
    ]);

    // Preset SIN variants (ej: input)
    Config::set('beartropyui.presets.input', [
        'default_color' => 'beartropy',
        'colors' => [
            'beartropy' => [
                'bg' => 'bg-white',
                'text' => 'text-black',
            ],
            'red' => [
                'bg' => 'bg-red-100',
                'text' => 'text-red-900',
            ],
        ],
    ]);

    // Preset CON variants (ej: button)
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

it('resuelve shouldFill desde outline en config para componente sin variants', function () {
    // outline=true en config => shouldFill=false
    Config::set('beartropyui.component_defaults.input', [
        'color' => 'beartropy',
        'size' => 'md',
        'outline' => true,
    ]);

    $c = new TestableComponent('input');
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $c->getComponentPresets('input');

    expect($shouldFill)->toBeFalse()
        ->and($presetNames['color'])->toBe('beartropy')
        ->and($presetNames['size'])->toBe('md')
        ->and($colorPreset['bg'])->toBe('bg-white');
});

it('prioriza fill prop sobre outline/config', function () {
    // Config tiene outline=true, pero prop fill=true debe ganar => shouldFill=true
    Config::set('beartropyui.component_defaults.input', [
        'color' => 'beartropy',
        'size' => 'md',
        'outline' => true,
    ]);

    $c = new TestableComponent('input', ['fill' => true]);
    [$colorPreset, $sizePreset, $shouldFill] = $c->getComponentPresets('input');

    expect($shouldFill)->toBeTrue();
});

it('outline prop false invierte shouldFill incluso si config dice outline=true', function () {
    Config::set('beartropyui.component_defaults.input', [
        'color' => 'beartropy',
        'size' => 'md',
        'outline' => true,
    ]);

    $c = new TestableComponent('input', ['outline' => false]);
    [$colorPreset, $sizePreset, $shouldFill] = $c->getComponentPresets('input');

    // outline=false => shouldFill=true
    expect($shouldFill)->toBeTrue();
});

it('por defecto (sin fill/outline en props ni config) shouldFill=false', function () {
    // Sin defaults para input
    Config::set('beartropyui.component_defaults.input', []);

    $c = new TestableComponent('input');
    [$colorPreset, $sizePreset, $shouldFill] = $c->getComponentPresets('input');

    expect($shouldFill)->toBeFalse();
});

it('resuelve variant/color en componente con variants y respeta fill prop', function () {
    // Config por si acaso, pero fill prop manda
    Config::set('beartropyui.component_defaults.button', [
        'variant' => 'solid',
        'color'   => 'red',
        'size'    => 'md',
        'outline' => true,
    ]);

    // Pasamos fill => shouldFill=true sin importar outline
    $c = new TestableComponent('button', ['fill' => true]);
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $c->getComponentPresets('button');

    expect($shouldFill)->toBeTrue()
        ->and($presetNames['variant'])->toBe('solid')        // default_variant de presets
        ->and($presetNames['color'])->toBe('red')            // de config por defecto (válido)
        ->and($colorPreset['bg'])->toBe('bg-red-600');       // solid/red
});

it('detecta variant por magic prop y elige color válido dentro de esa variant', function () {
    // Forzamos que config pida otra cosa, pero el magic prop de variant manda primero
    Config::set('beartropyui.component_defaults.button', [
        'variant' => 'solid',
        'color'   => 'red',
        'size'    => 'md',
        'outline' => true,
    ]);

    // magic prop: ghost (existe en presets); color no se pasa => usa default_color del componente (beartropy)
    $c = new TestableComponent('button', ['ghost' => true]);
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $c->getComponentPresets('button');

    expect($presetNames['variant'])->toBe('ghost')
        ->and($presetNames['color'])->toBe('beartropy')
        ->and($colorPreset['text'])->toBe('text-bear-600'); // ghost/beartropy
});

it('size puede venir de magic prop cuando coincide con presets.sizes', function () {
    Config::set('beartropyui.component_defaults.input', [
        'outline' => true,
        'size' => 'md',
    ]);

    // magic prop "lg" debe ganar
    $c = new TestableComponent('input', ['lg' => true]);
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $c->getComponentPresets('input');

    expect($presetNames['size'])->toBe('lg')
        ->and($sizePreset['font'])->toBe('text-lg');
});

it('no explota si el componente no tiene defaults definidos en config', function () {
    // Borramos cualquier default para un componente ficticio
    Config::set('beartropyui.component_defaults.unknown', null);
    Config::set('beartropyui.presets.unknown', []); // sin presets

    $c = new TestableComponent('unknown');
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $c->getComponentPresets('unknown');
    
    expect($colorPreset)->toBeArray()->toBeEmpty()
        ->and($sizePreset)->toBeArray() // puede ser vacío o 'md' si lo definís global
        ->and($shouldFill)->toBeFalse()
        ->and($presetNames)->toBeArray()->toBeEmpty();
});
