<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders a fixed-position wrapper div', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('<div class="fixed')
        ->toContain('</div>');
});

it('renders as a button element by default', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('</button>');
});

it('renders as an anchor element when href is provided', function () {
    $html = Blade::render('<x-bt-fab href="/create" />');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/create"')
        ->toContain('</a>')
        ->not->toContain('<button');
});

it('renders the default plus icon when no icon or slot given', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('<svg');
});

it('renders a custom icon via icon prop', function () {
    $html = Blade::render('<x-bt-fab icon="star" />');

    expect($html)->toContain('<svg');
});

it('renders slot content instead of icon when slot provided', function () {
    $html = Blade::render('<x-bt-fab><span class="custom-fab-content">GO</span></x-bt-fab>');

    expect($html)
        ->toContain('custom-fab-content')
        ->toContain('GO');
});

// --- Styling ---

it('applies rounded-full shadow and transition classes', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('rounded-full')
        ->toContain('shadow-lg')
        ->toContain('transition');
});

it('applies cursor-pointer class', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('cursor-pointer');
});

it('applies focus-visible ring for keyboard focus', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('focus:outline-none')
        ->toContain('focus-visible:ring-2');
});

it('applies flex centering classes to button', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('flex')
        ->toContain('items-center')
        ->toContain('justify-center');
});

// --- Accessibility ---

it('renders aria-label with default localized label', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('aria-label="New"');
});

it('renders custom aria-label from label prop', function () {
    $html = Blade::render('<x-bt-fab label="Add item" />');

    expect($html)->toContain('aria-label="Add item"');
});

// --- Positioning ---

it('uses default position values', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('right: 1rem')
        ->toContain('bottom: 1rem')
        ->toContain('z-index: 50');
});

it('can customize right position', function () {
    $html = Blade::render('<x-bt-fab right="2rem" />');

    expect($html)->toContain('right: 2rem');
});

it('can customize bottom position', function () {
    $html = Blade::render('<x-bt-fab bottom="3rem" />');

    expect($html)->toContain('bottom: 3rem');
});

it('can customize z-index', function () {
    $html = Blade::render('<x-bt-fab :zIndex="100" />');

    expect($html)->toContain('z-index: 100');
});

// --- Mobile visibility ---

it('does not add md:hidden by default', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->not->toContain('md:hidden');
});

it('adds md:hidden when onlyMobile is true', function () {
    $html = Blade::render('<x-bt-fab :onlyMobile="true" />');

    expect($html)->toContain('md:hidden');
});

// --- Color presets ---

it('applies default beartropy color preset', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)
        ->toContain('bg-beartropy-600')
        ->toContain('text-white')
        ->toContain('hover:bg-beartropy-700');
});

it('applies a named color preset', function () {
    $html = Blade::render('<x-bt-fab color="red" />');

    expect($html)
        ->toContain('bg-red-600')
        ->toContain('hover:bg-red-700')
        ->toContain('text-white');
});

it('applies magic color attribute', function () {
    $html = Blade::render('<x-bt-fab blue />');

    expect($html)
        ->toContain('bg-blue-600')
        ->toContain('hover:bg-blue-700');
});

// --- Size presets ---

it('applies default size preset to button and icon', function () {
    $html = Blade::render('<x-bt-fab />');

    // Default size from sizes.php — first size entry
    expect($html)->toContain('<svg');
});

it('applies a named size preset', function () {
    $html = Blade::render('<x-bt-fab size="lg" />');

    expect($html)->toContain('w-16 h-16');
});

// --- Attributes pass-through ---

it('merges additional attributes onto the button element', function () {
    $html = Blade::render('<x-bt-fab id="my-fab" data-action="create" />');

    expect($html)
        ->toContain('id="my-fab"')
        ->toContain('data-action="create"');
});

it('merges custom classes with preset classes', function () {
    $html = Blade::render('<x-bt-fab class="my-custom-class" />');

    expect($html)
        ->toContain('my-custom-class')
        ->toContain('rounded-full');
});

it('does not double-render attributes', function () {
    $html = Blade::render('<x-bt-fab id="fab-1" />');

    // Count occurrences of id="fab-1" — should appear exactly once
    expect(substr_count($html, 'id="fab-1"'))->toBe(1);
});

// --- Combined ---

it('renders all features together', function () {
    $html = Blade::render('
        <x-bt-fab
            icon="pencil"
            label="Edit item"
            href="/edit"
            :onlyMobile="false"
            right="2rem"
            bottom="2rem"
            :zIndex="75"
            color="green"
            size="lg"
        />
    ');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/edit"')
        ->toContain('aria-label="Edit item"')
        ->toContain('right: 2rem')
        ->toContain('bottom: 2rem')
        ->toContain('z-index: 75')
        ->toContain('bg-green-600')
        ->toContain('w-16 h-16');
});
