<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders an SVG for heroicon', function () {
    $html = Blade::render('<x-bt-icon name="home" />');

    expect($html)->toContain('<svg');
});

it('uses outline variant by default', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'home');
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('outline');
    expect($data->iconComponent)->toBe('heroicon-o-home');
});

it('forces solid variant via solid prop', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'home', solid: true);
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('solid');
    expect($data->iconComponent)->toBe('heroicon-s-home');
});

it('forces outline variant via outline prop', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'home', outline: true);
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('outline');
    expect($data->iconComponent)->toBe('heroicon-o-home');
});

it('overrides variant via variant prop', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'home', variant: 'solid');
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('solid');
    expect($data->iconComponent)->toBe('heroicon-s-home');
});

it('parses heroicon-o- prefix as outline', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'heroicon-o-home');
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('outline');
    expect($data->name)->toBe('home');
    expect($data->iconComponent)->toBe('heroicon-o-home');
});

it('parses heroicon-s- prefix as solid', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'heroicon-s-home');
    $data = $component->getClasses('w-5 h-5');

    expect($data->variant)->toBe('solid');
    expect($data->name)->toBe('home');
    expect($data->iconComponent)->toBe('heroicon-s-home');
});

it('applies custom class to output', function () {
    $html = Blade::render('<x-bt-icon name="home" class="text-red-500" />');

    expect($html)->toContain('text-red-500');
});

it('merges iconSize and class into allClasses', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'home', class: 'text-blue-500');
    $data = $component->getClasses('w-5 h-5');

    expect($data->allClasses)->toContain('w-5 h-5');
    expect($data->allClasses)->toContain('text-blue-500');
});

it('uses default md size preset producing w-5 h-5', function () {
    $html = Blade::render('<x-bt-icon name="home" />');

    expect($html)->toContain('w-5');
    expect($html)->toContain('h-5');
});

it('applies sm size preset via magic attribute', function () {
    $html = Blade::render('<x-bt-icon name="home" sm />');

    expect($html)->toContain('w-4');
    expect($html)->toContain('h-4');
});

it('applies lg size preset via magic attribute', function () {
    $html = Blade::render('<x-bt-icon name="home" lg />');

    expect($html)->toContain('w-6');
    expect($html)->toContain('h-6');
});

it('renders fontawesome icon as i tag', function () {
    $html = Blade::render('<x-bt-icon name="fa-solid fa-house" set="fontawesome" />');

    expect($html)->toContain('<i');
    expect($html)->toContain('fa-solid fa-house');
});

it('renders beartropy icon as SVG', function () {
    $html = Blade::render('<x-bt-icon name="search" set="beartropy" />');

    expect($html)->toContain('<svg');
});

it('renders unknown set as ? fallback with text-red-600', function () {
    $html = Blade::render('<x-bt-icon name="foo" set="unknown-set" />');

    expect($html)->toContain('?');
    expect($html)->toContain('text-red-600');
});

it('overrides set via set prop', function () {
    $component = new \Beartropy\Ui\Components\Icon(name: 'search', set: 'beartropy');
    $data = $component->getClasses('w-5 h-5');

    expect($data->set)->toBe('beartropy');
    expect($data->iconComponent)->toBe('beartropy-ui-svg::beartropy-search');
});

it('passes custom attributes through', function () {
    $html = Blade::render('<x-bt-icon name="home" data-testid="icon-home" />');

    expect($html)->toContain('data-testid="icon-home"');
});

it('combines variant, class, and size', function () {
    $html = Blade::render('<x-bt-icon name="home" :solid="true" class="text-blue-500" lg />');

    expect($html)->toContain('<svg');
    expect($html)->toContain('text-blue-500');
    expect($html)->toContain('w-6');
    expect($html)->toContain('h-6');
});
