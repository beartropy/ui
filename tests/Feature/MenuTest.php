<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic menu component', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test Item'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Test Item');
    expect($html)->toContain('role="list"');
});

it('renders with Alpine.js x-data', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('x-data');
});

it('can render menu items with URLs', function () {
    $items = [
        ['url' => '/dashboard', 'label' => 'Dashboard'],
        ['url' => '/settings', 'label' => 'Settings'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Dashboard');
    expect($html)->toContain('Settings');
    expect($html)->toContain('href="/dashboard"');
    expect($html)->toContain('href="/settings"');
});

it('renders with wire:navigate on links', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('wire:navigate');
});

it('highlights active items', function () {
    $items = [
        ['url' => '/', 'label' => 'Home'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Home');
});

it('can render section titles', function () {
    $items = [
        ['title' => 'Main Section'],
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Main Section');
    expect($html)->toContain('<h2');
});

it('can render nested menus', function () {
    $items = [
        [
            'title' => 'Parent',
            'items' => [
                ['url' => '/child', 'label' => 'Child Item'],
            ],
        ],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Child Item');
});

it('can render icons on items', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test', 'icon' => 'fas fa-home'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('fas fa-home');
});

it('can render badges on items', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test', 'badge' => ['text' => '5', 'class' => 'bg-red-500']],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('5');
    expect($html)->toContain('bg-red-500');
});

it('renders with custom ul class', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" ulClass="custom-ul-class" />', ['items' => $items]);

    expect($html)->toContain('custom-ul-class');
});

it('renders with custom title class', function () {
    $items = [
        ['title' => 'Section'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" titleClass="custom-title" />', ['items' => $items]);

    expect($html)->toContain('custom-title');
});

it('renders with custom item class', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" itemClass="custom-item" />', ['items' => $items]);

    expect($html)->toContain('custom-item');
});

it('renders with custom active class', function () {
    $items = [
        ['url' => '/', 'label' => 'Active'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" activeClass="custom-active" />', ['items' => $items]);

    expect($html)->toContain('Active');
});

it('supports mobile mode', function () {
    $items = [
        ['url' => '/test', 'label' => 'Test'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" :mobile="true" />', ['items' => $items]);

    expect($html)->toContain('p-2');
});

it('handles nested levels with borders', function () {
    $items = [
        [
            'items' => [
                ['url' => '/child', 'label' => 'Child'],
            ],
        ],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Child');
});

it('renders screen reader text for current page', function () {
    $items = [
        ['url' => '/', 'label' => 'Current'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Current');
});
