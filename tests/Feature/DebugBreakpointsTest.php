<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);

    // Set environment to local for testing
    $this->app['env'] = 'local';
});

it('renders only in local environment', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('x-data');
});

it('does not render in production', function () {
    $this->app['env'] = 'production';
    $html = Blade::render('<x-bt-debug-breakpoints env="local" />');

    expect($html)->toBeEmpty();
});

it('renders with Alpine.js x-data', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('expanded');
});

it('uses localStorage for expanded state', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('localStorage');
    expect($html)->toContain('debug_breakpoints_expanded');
});

it('can start expanded', function () {
    $html = Blade::render('<x-bt-debug-breakpoints :expanded="true" />');

    expect($html)->toContain('true');
});

it('starts collapsed by default', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('false');
});

it('renders with fixed positioning', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('fixed');
    expect($html)->toContain('bottom-0');
    expect($html)->toContain('right-0');
});

it('has high z-index', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('z-[100]');
});

it('displays all Tailwind breakpoints', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('XS');
    expect($html)->toContain('SM');
    expect($html)->toContain('MD');
    expect($html)->toContain('LG');
    expect($html)->toContain('XL');
    expect($html)->toContain('2XL');
});

it('shows current window width', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('window.innerWidth');
    expect($html)->toContain('x-text="width"');
});

it('listens to window resize', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('@resize.window');
});

it('renders toggle button', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('@click="toggle()"');
});

it('renders expanded state with transitions', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('x-show="expanded"');
    expect($html)->toContain('x-transition');
});

it('renders minimized floating button', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('x-show="!expanded"');
});

it('uses red color scheme', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('bg-red-600');
    expect($html)->toContain('border-red-400');
});

it('renders with monospace font', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('font-mono');
});

it('can render in custom environment', function () {
    $this->app['env'] = 'staging';
    $html = Blade::render('<x-bt-debug-breakpoints env="staging" />');

    expect($html)->toContain('x-data');
});
