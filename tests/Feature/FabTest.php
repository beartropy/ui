<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic fab component', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('fixed');
    expect($html)->toContain('rounded-full');
});

it('renders with default icon', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('<svg');
});

it('can render with custom icon', function () {
    $html = Blade::render('<x-bt-fab icon="star" />');

    expect($html)->toContain('<svg');
});

it('renders as button by default', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('<button');
});

it('renders as link when href provided', function () {
    $html = Blade::render('<x-bt-fab href="/create" />');

    expect($html)->toContain('<a');
    expect($html)->toContain('href="/create"');
});

it('can render with custom slot content', function () {
    $html = Blade::render('<x-bt-fab>Custom Content</x-bt-fab>');

    expect($html)->toContain('Custom Content');
});

it('renders with shadow and transitions', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('shadow-lg');
    expect($html)->toContain('transition');
});

it('can be hidden on desktop with onlyMobile', function () {
    $html = Blade::render('<x-bt-fab :onlyMobile="true" />');

    expect($html)->toContain('md:hidden');
});

it('renders on all screens by default', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->not->toContain('md:hidden');
});

it('uses inline styles for positioning', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('right:');
    expect($html)->toContain('bottom:');
    expect($html)->toContain('z-index:');
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

it('uses default z-index of 50', function () {
    $html = Blade::render('<x-bt-fab />');

    expect($html)->toContain('z-index: 50');
});

it('supports color presets', function () {
    $htmlPrimary = Blade::render('<x-bt-fab color="primary" />');
    expect($htmlPrimary)->toContain('fixed');

    $htmlDanger = Blade::render('<x-bt-fab color="danger" />');
    expect($htmlDanger)->toContain('fixed');
});

it('supports size presets', function () {
    $htmlSm = Blade::render('<x-bt-fab size="sm" />');
    expect($htmlSm)->toContain('fixed');

    $htmlLg = Blade::render('<x-bt-fab size="lg" />');
    expect($htmlLg)->toContain('fixed');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-fab 
            icon="pencil"
            href="/edit"
            :onlyMobile="false"
            right="2rem"
            bottom="2rem"
            :zIndex="75"
            color="success"
            size="lg"
        />
    ');

    expect($html)->toContain('<a');
    expect($html)->toContain('href="/edit"');
    expect($html)->toContain('right: 2rem');
    expect($html)->toContain('bottom: 2rem');
    expect($html)->toContain('z-index: 75');
});
