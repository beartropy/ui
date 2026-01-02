<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic toggle theme component', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('dark');
});

it('renders with Alpine.js dark state', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('localStorage');
    expect($html)->toContain('theme');
});

it('includes theme initialization script', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('<script>');
    expect($html)->toContain('__setTheme');
    expect($html)->toContain('computeDark');
});

it('toggles dark class on documentElement', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('document.documentElement.classList.toggle(\'dark\'');
});

it('uses localStorage for theme persistence', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('localStorage.theme');
});

it('renders in icon mode by default', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('<svg');
});

it('can render in button mode', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" />');

    expect($html)->toContain('type="button"');
});

it('can render in square-button mode', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="square-button" />');

    expect($html)->toContain('type="button"');
});

it('renders light mode icon', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('x-show="!dark"');
});

it('renders dark mode icon', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('x-show="dark"');
});

it('has rotating animation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('theme-rotatable');
    expect($html)->toContain('theme-rotate');
    expect($html)->toContain('rotating');
});

it('includes CSS for rotation animation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('<style>');
    expect($html)->toContain('transform: rotate');
});

it('dispatches custom theme-change event', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('theme-change');
    expect($html)->toContain('CustomEvent');
});

it('handles Livewire navigation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('livewire:navigated');
});

it('supports different sizes', function () {
    $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-toggle-theme size=\"{$size}\" />");
        expect($html)->toContain('x-data');
    }
});

it('can render with custom icon colors', function () {
    $html = Blade::render('
        <x-bt-toggle-theme 
            iconColorLight="text-yellow-500"
            iconColorDark="text-indigo-500"
        />
    ');

    expect($html)->toContain('x-data');
});

it('can render with custom icons', function () {
    $html = Blade::render('
        <x-bt-toggle-theme 
            iconLight="sun"
            iconDark="moon"
        />
    ');

    expect($html)->toContain('x-data');
});

it('supports icon slots', function () {
    $html = Blade::render('
        <x-bt-toggle-theme>
            <x-slot:icon-light>
                <span>Light</span>
            </x-slot:icon-light>
            <x-slot:icon-dark>
                <span>Dark</span>
            </x-slot:icon-dark>
        </x-bt-toggle-theme>
    ');

    expect($html)->toContain('x-data');
});

it('has cursor pointer for click interaction', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('cursor:pointer');
});

it('uses @click.stop to prevent propagation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('@click.stop="toggle()"');
});

it('respects system prefers-color-scheme', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('prefers-color-scheme');
    expect($html)->toContain('matchMedia');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-toggle-theme label="Theme Toggle" />');

    expect($html)->toContain('x-data');
});

it('has aria-pressed for accessibility', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" />');

    expect($html)->toContain(':aria-pressed="dark"');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-toggle-theme 
            size="lg"
            mode="button"
            iconColorLight="text-orange-600"
            iconColorDark="text-blue-400"
            label="Switch Theme"
            labelPosition="left"
        />
    ');

    expect($html)->toContain('x-data');
    expect($html)->toContain('type="button"');
    expect($html)->toContain('dark');
});
