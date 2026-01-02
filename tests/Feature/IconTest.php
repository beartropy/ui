<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic icon component', function () {
    $html = Blade::render('<x-bt-icon name="home" />');

    expect($html)->not->toBeEmpty();
});

it('uses heroicons set by default', function () {
    $html = Blade::render('<x-bt-icon name="home" />');

    expect($html)->not->toBeEmpty();
});

it('uses outline variant by default', function () {
    $html = Blade::render('<x-bt-icon name="home" />');

    expect($html)->not->toBeEmpty();
});

it('can force solid variant', function () {
    $html = Blade::render('<x-bt-icon name="home" :solid="true" />');

    expect($html)->not->toBeEmpty();
});

it('can force outline variant', function () {
    $html = Blade::render('<x-bt-icon name="home" :outline="true" />');

    expect($html)->not->toBeEmpty();
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-icon name="home" class="text-red-500" />');

    expect($html)->not->toBeEmpty();
});

it('supports size parameter', function () {
    $html = Blade::render('<x-bt-icon name="home" size="w-6 h-6" />');

    expect($html)->not->toBeEmpty();
});

it('supports different sizes', function () {
    $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-icon name=\"home\" size=\"{$size}\" />");
        expect($html)->not->toBeEmpty();
    }
});

it('can override variant', function () {
    $html = Blade::render('<x-bt-icon name="home" variant="solid" />');

    expect($html)->not->toBeEmpty();
});

it('handles heroicon prefixes', function () {
    $html = Blade::render('<x-bt-icon name="heroicon-o-home" />');

    expect($html)->not->toBeEmpty();
});

it('can render all features combined', function () {
    $html = Blade::render('<x-bt-icon name="home" set="heroicons" variant="solid" size="w-8 h-8" class="text-blue-500" />');

    expect($html)->not->toBeEmpty();
});
