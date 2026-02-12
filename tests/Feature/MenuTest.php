<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders as a semantic ul with role list', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('<ul')
        ->toContain('role="list"');
});

it('does not render bare x-data on the ul', function () {
    $items = [['url' => '/a', 'label' => 'A']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->not->toContain(' x-data');
});

it('renders links with wire:navigate', function () {
    $items = [['url' => '/dashboard', 'label' => 'Dashboard']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('wire:navigate')
        ->toContain('href="/dashboard"')
        ->toContain('Dashboard');
});

it('renders multiple items as li elements', function () {
    $items = [
        ['url' => '/a', 'label' => 'Alpha'],
        ['url' => '/b', 'label' => 'Beta'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('href="/a"')
        ->toContain('Alpha')
        ->toContain('href="/b"')
        ->toContain('Beta')
        ->toContain('<li');
});

// --- Active State ---

it('marks active item with aria-current and active class', function () {
    $items = [['url' => '/', 'label' => 'Home']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('aria-current="page"')
        ->toContain('font-semibold');
});

it('renders sr-only current text for active items', function () {
    $items = [['url' => '/', 'label' => 'Home']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('sr-only')
        ->toContain('(current)');
});

it('does not mark inactive items with aria-current', function () {
    $items = [['url' => '/never-match-this-path', 'label' => 'Other']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->not->toContain('aria-current')
        ->not->toContain('sr-only');
});

it('supports custom route pattern for active detection', function () {
    $items = [['url' => '/test', 'label' => 'Test', 'route' => '/']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('aria-current="page"');
});

// --- Section Titles ---

it('renders section titles as h2 elements', function () {
    $items = [['title' => 'Settings']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('<h2')
        ->toContain('Settings')
        ->toContain('mt-2 mb-1');
});

it('renders nested section titles with smaller styling', function () {
    $items = [
        ['title' => 'Parent', 'items' => [
            ['title' => 'Child Section'],
        ]],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Child Section')
        ->toContain('text-xs uppercase tracking-widest');
});

// --- Nested Menus ---

it('renders nested submenus recursively', function () {
    $items = [
        ['title' => 'Group', 'items' => [
            ['url' => '/child', 'label' => 'Child Item'],
        ]],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Child Item')
        ->toContain('href="/child"');
});

it('adds left border and indent to nested levels', function () {
    $items = [
        ['items' => [
            ['url' => '/nested', 'label' => 'Nested'],
        ]],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('border-l')
        ->toContain('border-slate-200')
        ->toContain('ml-4')
        ->toContain('pl-2');
});

// --- Icons ---

it('renders icons through the Icon component', function () {
    $items = [['url' => '/test', 'label' => 'Test', 'icon' => 'heroicon-o-home']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('<svg');
});

it('renders raw SVG icons passed as HTML', function () {
    $items = [['url' => '/test', 'label' => 'Test', 'icon' => '<svg class="custom">x</svg>']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->toContain('<svg class="custom">x</svg>');
});

it('skips icon rendering when no icon is set', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)->not->toContain('<svg');
});

// --- Badges ---

it('renders badges with text and class', function () {
    $items = [['url' => '/test', 'label' => 'Inbox', 'badge' => ['text' => '12', 'class' => 'bg-red-100 text-red-600']]];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('12')
        ->toContain('bg-red-100')
        ->toContain('text-red-600')
        ->toContain('<span');
});

it('does not render badge span when no badge is set', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    // Only the <a> tag content, no <span> for badge
    $linkSection = explode('</a>', explode('wire:navigate', $html)[1] ?? '')[0] ?? '';
    expect($linkSection)->not->toContain('<span class="bg-');
});

// --- Custom Classes ---

it('applies custom ul class', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" ul-class="my-custom-ul" />', ['items' => $items]);

    expect($html)->toContain('my-custom-ul');
});


it('applies custom li class', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" li-class="my-li" />', ['items' => $items]);

    expect($html)->toContain('my-li');
});

// --- Mobile ---

it('adds p-2 padding in mobile mode', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" :mobile="true" />', ['items' => $items]);

    expect($html)->toContain('p-2');
});

it('does not add p-2 when not in mobile mode', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    // Root ul (level 0, not mobile) should not have p-2
    expect($html)->not->toMatch('/<ul[^>]*class="[^"]*\bp-2\b/');
});

// --- Color Presets ---

it('uses default beartropy color preset', function () {
    $items = [
        ['title' => 'Section'],
        ['url' => '/', 'label' => 'Active'],
    ];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('text-beartropy-500')
        ->toContain('dark:text-beartropy-400')
        ->toContain('hover:text-beartropy-500');
});

it('applies blue color preset via magic attribute', function () {
    $items = [
        ['title' => 'Section'],
        ['url' => '/', 'label' => 'Active'],
    ];
    $html = Blade::render('<x-bt-menu blue :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('text-blue-500')
        ->toContain('dark:text-blue-400')
        ->toContain('hover:text-blue-500');
});

it('applies color preset via color prop', function () {
    $items = [
        ['title' => 'Section'],
        ['url' => '/', 'label' => 'Active'],
    ];
    $html = Blade::render('<x-bt-menu color="emerald" :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('text-emerald-500')
        ->toContain('dark:text-emerald-400')
        ->toContain('hover:text-emerald-500');
});

// --- Default Classes ---

it('uses default spacing classes on ul', function () {
    $items = [['url' => '/test', 'label' => 'Test']];
    $html = Blade::render('<x-bt-menu :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('space-y-2')
        ->toContain('lg:space-y-4');
});
