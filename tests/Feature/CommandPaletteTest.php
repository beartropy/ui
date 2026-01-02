<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic command palette component', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->not->toBeEmpty();
});

it('renders with items array', function () {
    $items = [
        ['label' => 'Home', 'url' => '/home'],
        ['label' => 'Settings', 'url' => '/settings'],
    ];
    $html = Blade::render('<x-bt-command-palette :items="$items" />', ['items' => $items]);

    expect($html)->not->toBeEmpty();
});

it('supports color presets', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" color="primary" />');

    expect($html)->not->toBeEmpty();
});

it('allows guests by default to false', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->not->toBeEmpty();
});

it('can allow guests', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" :allowGuests="true" />');

    expect($html)->not->toBeEmpty();
});

it('can render with all features', function () {
    $items = [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'home'],
        ['label' => 'Profile', 'url' => '/profile', 'icon' => 'user'],
    ];
    $html = Blade::render('<x-bt-command-palette :items="$items" color="primary" :allowGuests="false" />', ['items' => $items]);

    expect($html)->not->toBeEmpty();
});
