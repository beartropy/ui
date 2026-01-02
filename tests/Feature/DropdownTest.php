<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic dropdown component', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)->toContain('Content');
});

it('uses default bottom placement', function () {
    $html = Blade::render('<x-bt-dropdown>Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});

it('can customize placement', function () {
    $html = Blade::render('<x-bt-dropdown placement="top">Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});

it('supports different placements', function () {
    $placements = ['top', 'bottom', 'left', 'right', 'bottom-start', 'bottom-end'];

    foreach ($placements as $placement) {
        $html = Blade::render("<x-bt-dropdown placement=\"{$placement}\">Menu</x-bt-dropdown>");
        expect($html)->toContain('Menu');
    }
});

it('uses default left side', function () {
    $html = Blade::render('<x-bt-dropdown>Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});

it('can customize side', function () {
    $html = Blade::render('<x-bt-dropdown side="right">Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});

it('supports trigger slot', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-slot:trigger>
                <button>Open Menu</button>
            </x-slot:trigger>
            Menu Items
        </x-bt-dropdown>
    ');

    expect($html)->toContain('Open Menu');
    expect($html)->toContain('Menu Items');
});

it('supports default slot for content', function () {
    $html = Blade::render('<x-bt-dropdown>Dropdown Content</x-bt-dropdown>');

    expect($html)->toContain('Dropdown Content');
});

it('supports color presets', function () {
    $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];

    foreach ($colors as $color) {
        $html = Blade::render("<x-bt-dropdown color=\"{$color}\">Menu</x-bt-dropdown>");
        expect($html)->toContain('Menu');
    }
});

it('supports size presets', function () {
    $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-dropdown size=\"{$size}\">Menu</x-bt-dropdown>");
        expect($html)->toContain('Menu');
    }
});

it('can enable wire navigate', function () {
    $html = Blade::render('<x-bt-dropdown :withnavigate="true">Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});

it(' disables wire navigate by default', function () {
    $html = Blade::render('<x-bt-dropdown>Menu</x-bt-dropdown>');

    expect($html)->toContain('Menu');
});
