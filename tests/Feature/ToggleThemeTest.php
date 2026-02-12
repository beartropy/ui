<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders wrapper with Alpine x-data btToggleTheme', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('x-data="btToggleTheme()"')
        ->toContain('inline-flex');
});

it('renders rotation animation CSS with keyframes', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('<style>')
        ->toContain('@keyframes theme-spin')
        ->toContain('.theme-rotate')
        ->toContain('animation: theme-spin');
});

it('does not render inline script tag', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->not->toContain('<script>');
});

// --- Icon mode (default) ---

it('renders icon mode with a button element by default', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('type="button"')
        ->toContain('@click.stop="toggle()"');
});

it('renders sun SVG for light mode', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('x-show="!dark"')
        ->toContain('<circle cx="12" cy="12" r="5"/>');
});

it('renders moon SVG for dark mode', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('x-show="dark"')
        ->toContain('M21 12.79A9 9 0 1111.21 3');
});

it('renders aria-label and aria-pressed in icon mode', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('aria-label="Toggle theme"')
        ->toContain(':aria-pressed="dark"');
});

// --- Button mode ---

it('renders button mode with rounded border and padding', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" />');

    expect($html)
        ->toContain('type="button"')
        ->toContain('rounded-full')
        ->toContain('border-2')
        ->toContain(':aria-pressed="dark"');
});

it('renders button mode with default border colors', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" />');

    expect($html)
        ->toContain('border-orange-300')
        ->toContain('border-orange-400');
});

it('renders button mode with label on the right', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" label="Dark Mode" />');

    expect($html)->toContain('Dark Mode');
});

it('renders button mode with label on the left', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" label="Theme" label-position="left" />');

    expect($html)->toContain('Theme');
});

// --- Square-button mode ---

it('renders square-button mode with fixed dimensions', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="square-button" />');

    expect($html)
        ->toContain('type="button"')
        ->toContain('w-10 h-10')
        ->toContain('rounded-lg')
        ->toContain(':aria-pressed="dark"');
});

// --- Sizes ---

it('applies xs size classes', function () {
    $html = Blade::render('<x-bt-toggle-theme size="xs" />');

    expect($html)->toContain('w-2 h-2');
});

it('applies lg size classes', function () {
    $html = Blade::render('<x-bt-toggle-theme size="lg" />');

    expect($html)->toContain('w-5 h-5');
});

it('applies 2xl size classes', function () {
    $html = Blade::render('<x-bt-toggle-theme size="2xl" />');

    expect($html)->toContain('w-8 h-8');
});

it('applies size to square-button dimensions', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="square-button" size="lg" />');

    expect($html)->toContain('w-12 h-12');
});

it('applies size to button padding', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" size="lg" />');

    expect($html)->toContain('p-3');
});

// --- Custom colors ---

it('applies custom icon colors', function () {
    $html = Blade::render('<x-bt-toggle-theme icon-color-light="text-yellow-500" icon-color-dark="text-indigo-400" />');

    expect($html)
        ->toContain('text-yellow-500')
        ->toContain('text-indigo-400');
});

it('uses default icon colors when not specified', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)
        ->toContain('text-orange-600')
        ->toContain('text-blue-400');
});

it('applies custom border colors in button mode', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" border-color-light="border-red-300" border-color-dark="border-red-500" />');

    expect($html)
        ->toContain('border-red-300')
        ->toContain('border-red-500');
});

// --- Custom icons ---

it('renders custom heroicon names', function () {
    $html = Blade::render('<x-bt-toggle-theme icon-light="sun" icon-dark="moon" />');

    expect($html)
        ->toContain('x-show="!dark"')
        ->toContain('x-show="dark"');
});

// --- Animation ---

it('includes theme-spin keyframe animation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('@keyframes theme-spin');
});

it('uses theme-rotate class for rotation', function () {
    $html = Blade::render('<x-bt-toggle-theme />');

    expect($html)->toContain('theme-rotate');
});

// --- Accessibility ---

it('renders custom aria-label', function () {
    $html = Blade::render('<x-bt-toggle-theme aria-label="Switch dark mode" />');

    expect($html)->toContain('aria-label="Switch dark mode"');
});

it('uses label as aria-label when provided', function () {
    $html = Blade::render('<x-bt-toggle-theme mode="button" label="Night Mode" />');

    expect($html)->toContain('aria-label="Night Mode"');
});

// --- Custom class ---

it('passes custom class to wrapper', function () {
    $html = Blade::render('<x-bt-toggle-theme class="my-toggle" />');

    expect($html)->toContain('my-toggle');
});

// --- Icon slots ---

it('renders custom icon-light slot content', function () {
    $html = Blade::render('
        <x-bt-toggle-theme>
            <x-slot:icon-light><span class="custom-sun">SUN</span></x-slot:icon-light>
            <x-slot:icon-dark><span class="custom-moon">MOON</span></x-slot:icon-dark>
        </x-bt-toggle-theme>
    ');

    expect($html)
        ->toContain('custom-sun')
        ->toContain('custom-moon');
});

// --- JS Module ---

it('has toggle-theme JS module with initTheme and btToggleTheme exports', function () {
    $path = realpath(__DIR__ . '/../../resources/js/modules/toggle-theme.js');
    $content = file_get_contents($path);

    expect($content)
        ->toContain('export function initTheme()')
        ->toContain('export function btToggleTheme()')
        ->toContain('__setTheme')
        ->toContain('computeDark')
        ->toContain('livewire:navigated')
        ->toContain('colorScheme')
        ->toContain('theme-change')
        ->toContain('$nextTick');
});
