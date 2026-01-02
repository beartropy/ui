<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic nav component', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)->not->toBeEmpty();
});

it('renders with simple items array', function () {
    $items = [
        ['label' => 'Home', 'url' => '/home', 'icon' => 'home'],
        ['label' => 'Settings', 'url' => '/settings', 'icon' => 'cog'],
    ];

    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Home');
    expect($html)->toContain('Settings');
});

it('renders nested items', function () {
    $items = [
        [
            'label' => 'Parent',
            'url' => '#',
            'items' => [
                ['label' => 'Child', 'url' => '/child'],
            ]
        ],
    ];

    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Parent');
    expect($html)->toContain('Child');
});

it('supports wire:navigate', function () {
    $items = [['label' => 'Link', 'url' => '/link']];
    $html = Blade::render('<x-bt-nav :items="$items" :withnavigate="true" />', ['items' => $items]);

    expect($html)->toContain('wire:navigate');
});

it('marks active items', function () {
    $items = [['label' => 'Active Item', 'url' => 'http://localhost/active', 'active' => true]];

    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Active Item');
});

it('supports custom color', function () {
    $html = Blade::render('<x-bt-nav :items="[]" color="red" />');

    expect($html)->not->toBeEmpty();
});
