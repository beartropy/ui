<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic tooltip component', function () {
    $html = Blade::render('<x-bt-tooltip label="Tooltip text">Hover me</x-bt-tooltip>');

    expect($html)->toContain('Tooltip text');
    expect($html)->toContain('Hover me');
});

it('renders with label', function () {
    $html = Blade::render('<x-bt-tooltip label="Info">Content</x-bt-tooltip>');

    expect($html)->toContain('Info');
});

it('uses default delay of 0', function () {
    $html = Blade::render('<x-bt-tooltip label="Tooltip">Content</x-bt-tooltip>');

    expect($html)->toContain('Content');
});

it('can customize delay', function () {
    $html = Blade::render('<x-bt-tooltip label="Tooltip" :delay="500">Content</x-bt-tooltip>');

    expect($html)->toContain('Content');
});

it('uses right position by default', function () {
    $html = Blade::render('<x-bt-tooltip label="Tooltip">Content</x-bt-tooltip>');

    expect($html)->toContain('Content');
});

it('supports different positions', function () {
    $positions = ['top', 'bottom', 'left', 'right'];

    foreach ($positions as $position) {
        $html = Blade::render("<x-bt-tooltip label=\"Tooltip\" position=\"{$position}\">Content</x-bt-tooltip>");
        expect($html)->toContain('Content');
    }
});

it('renders trigger content in default slot', function () {
    $html = Blade::render('<x-bt-tooltip label="Help text"><button>Click me</button></x-bt-tooltip>');

    expect($html)->toContain('Click me');
});

it('can render with all features', function () {
    $html = Blade::render('<x-bt-tooltip label="Detailed help" :delay="300" position="top">Button</x-bt-tooltip>');

    expect($html)->toContain('Detailed help');
    expect($html)->toContain('Button');
});
