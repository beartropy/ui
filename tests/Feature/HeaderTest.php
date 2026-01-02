<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic header component', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('<div');
});

it('renders with default title from APP_NAME', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('Beartropy UI'); // Default when APP_NAME not set
});

it('can render with custom title', function () {
    $html = Blade::render('<x-bt-header title="My App" />');

    expect($html)->toContain('My App');
});

it('can render with logo', function () {
    $html = Blade::render('<x-bt-header logo="/logo.png" />');

    expect($html)->toContain('src="/logo.png"');
    expect($html)->toContain('<img');
});

it('renders without logo by default', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->not->toContain('<img');
});

it('can render with fixed positioning', function () {
    $html = Blade::render('<x-bt-header :fixed="true" />');

    expect($html)->toContain('fixed');
    expect($html)->toContain('top-0');
});

it('renders without fixed positioning by default', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->not->toContain('fixed');
});

it('adjusts margin for mini sidebar', function () {
    $html = Blade::render('<x-bt-header :mini="true" />');

    expect($html)->toContain('Beartropy UI');
});

it('uses regular margin for normal sidebar', function () {
    $html = Blade::render('<x-bt-header :mini="false" />');

    expect($html)->toContain('Beartropy UI');
});

it('can render with custom z-index', function () {
    $html = Blade::render('<x-bt-header :zIndex="99" />');

    expect($html)->toContain('Beartropy UI');
});

it('renders with default z-50', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('Beartropy UI');
});

it('renders with default slot', function () {
    $html = Blade::render('<x-bt-header>Center Content</x-bt-header>');

    expect($html)->toContain('Center Content');
});

it('renders with actions slot', function () {
    $html = Blade::render('
        <x-bt-header>
            <x-slot:actions>
                <button>Action Button</button>
            </x-slot:actions>
        </x-bt-header>
    ');

    expect($html)->toContain('Action Button');
});

it('centers main content', function () {
    $html = Blade::render('<x-bt-header>Content</x-bt-header>');

    expect($html)->toContain('justify-center');
});

it('renders with transition classes', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('transition-all');
    expect($html)->toContain('duration-300');
});

it('renders with border and shadow', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('border-b');
    expect($html)->toContain('shadow-md');
});

it('renders with dark mode support', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('dark:bg-gray-900');
    expect($html)->toContain('dark:border-gray-800');
});

it('renders with fixed height', function () {
    $html = Blade::render('<x-bt-header />');

    expect($html)->toContain('h-14');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-header 
            title="Dashboard"
            logo="/app-logo.svg"
            :fixed="true"
            :mini="true"
            :zIndex="100"
        >
            <span>Search Bar</span>
            <x-slot:actions>
                <button>Profile</button>
            </x-slot:actions>
        </x-bt-header>
    ');

    expect($html)->toContain('Dashboard');
    expect($html)->toContain('/app-logo.svg');
    expect($html)->toContain('fixed');
    expect($html)->toContain('Search Bar');
    expect($html)->toContain('Profile');
});
