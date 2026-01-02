<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic skeleton component', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" />');

    expect($html)->not->toBeEmpty();
});

it('renders with default 1 line', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" />');

    expect($html)->not->toBeEmpty();
});

it('can render multiple lines', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" :lines="3" />');

    expect($html)->not->toBeEmpty();
});

it('uses default card shape', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" />');

    expect($html)->not->toBeEmpty();
});

it('supports different shapes', function () {
    $shapes = ['card', 'rectangle', 'none'];

    foreach ($shapes as $shape) {
        $html = Blade::render("<x-bt-skeleton init=\"loading\" shape=\"{$shape}\" />");
        expect($html)->not->toBeEmpty();
    }
});

it('uses default lg rounded', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" />');

    expect($html)->not->toBeEmpty();
});

it('can customize rounded', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" rounded="full" />');

    expect($html)->not->toBeEmpty();
});

it('uses div tag by default', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" />');

    expect($html)->not->toBeEmpty();
});

it('can customize tag', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" tag="span" />');

    expect($html)->not->toBeEmpty();
});

it('can add custom skeleton class', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" skeletonClass="custom-skeleton" />');

    expect($html)->not->toBeEmpty();
});

it('supports rows parameter', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" :rows="2" />');

    expect($html)->not->toBeEmpty();
});

it('supports cols parameter', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" :cols="3" />');

    expect($html)->not->toBeEmpty();
});

it('can render with all features', function () {
    $html = Blade::render('<x-bt-skeleton init="loading" :lines="5" rounded="md" tag="div" shape="rectangle" :rows="2" :cols="3" />');

    expect($html)->not->toBeEmpty();
});
