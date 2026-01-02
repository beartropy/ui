<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic sidebar component', function () {
    $html = Blade::render('<x-bt-sidebar>Nav Items</x-bt-sidebar>');

    expect($html)->toContain('Nav Items');
});

it('renders with default slot', function () {
    $html = Blade::render('<x-bt-sidebar>Navigation</x-bt-sidebar>');

    expect($html)->toContain('Navigation');
});

it('can render with logo', function () {
    $html = Blade::render('<x-bt-sidebar>Nav</x-bt-sidebar>');

    expect($html)->toContain('Nav');
});

it('supports custom background classes', function () {
    $html = Blade::render('<x-bt-sidebar bg="bg-blue-900">Nav</x-bt-sidebar>');

    expect($html)->toContain('Nav');
});

it('supports custom border classes', function () {
    $html = Blade::render('<x-bt-sidebar border="border-blue-500">Nav</x-bt-sidebar>');

    expect($html)->toContain('Nav');
});

it('can render with all features', function () {
    $html = Blade::render('
        <x-bt-sidebar 
            bg="bg-gray-800"
            border="border-gray-700"
        >
            Navigation Items
        </x-bt-sidebar>
    ');

    expect($html)->toContain('Navigation Items');
});
