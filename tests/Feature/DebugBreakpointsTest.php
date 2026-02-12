<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);

    $this->app['env'] = 'local';
});

// --- Environment gating ---

it('renders in local environment by default', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('x-data')
        ->toContain('fixed');
});

it('does not render in production environment', function () {
    $this->app['env'] = 'production';
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect(trim($html))->toBeEmpty();
});

it('renders in a custom environment when env prop matches', function () {
    $this->app['env'] = 'staging';
    $html = Blade::render('<x-bt-debug-breakpoints env="staging" />');

    expect($html)->toContain('x-data');
});

it('does not render when env prop does not match current environment', function () {
    $this->app['env'] = 'production';
    $html = Blade::render('<x-bt-debug-breakpoints env="local" />');

    expect(trim($html))->toBeEmpty();
});

// --- Alpine.js state ---

it('has Alpine x-data with expanded, width, and toggle function', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('expanded')
        ->toContain('width: window.innerWidth')
        ->toContain('toggle()');
});

it('persists expanded state to localStorage', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain("localStorage.getItem('debug_breakpoints_expanded')")
        ->toContain("localStorage.setItem('debug_breakpoints_expanded'");
});

it('defaults expanded to false', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    // The ternary defaults to false when no localStorage value exists
    expect($html)->toContain(': false');
});

it('can start expanded via prop', function () {
    $html = Blade::render('<x-bt-debug-breakpoints :expanded="true" />');

    expect($html)->toContain(': true');
});

it('listens to window resize events', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('@resize.window="width = window.innerWidth"');
});

// --- Positioning ---

it('is fixed to bottom-right with high z-index', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('fixed')
        ->toContain('bottom-0')
        ->toContain('right-0')
        ->toContain('z-[100]');
});

it('starts hidden and Alpine reveals it to prevent FOUC', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('style="display: none;"')
        ->toContain('x-show="true"');
});

// --- Expanded bar ---

it('renders expanded bar with x-show and transitions', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('x-show="expanded"')
        ->toContain('x-transition:enter="transition ease-out duration-200"')
        ->toContain('x-transition:leave="transition ease-in duration-150"');
});

it('displays all six Tailwind breakpoint labels', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('>XS</span>')
        ->toContain('>SM</span>')
        ->toContain('>MD</span>')
        ->toContain('>LG</span>')
        ->toContain('>XL</span>')
        ->toContain('>2XL</span>');
});

it('uses responsive visibility classes for breakpoint labels', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('block sm:hidden')
        ->toContain('hidden sm:block md:hidden')
        ->toContain('hidden md:block lg:hidden')
        ->toContain('hidden lg:block xl:hidden')
        ->toContain('hidden xl:block 2xl:hidden')
        ->toContain('hidden 2xl:block');
});

it('shows current width in pixels via x-text', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('x-text="width"')
        ->toContain('>px</span>');
});

it('uses red color scheme and monospace font', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('bg-red-600')
        ->toContain('border-red-400')
        ->toContain('font-mono');
});

// --- Minimized button ---

it('renders minimized floating button with x-show !expanded', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('x-show="!expanded"')
        ->toContain('rounded-full');
});

it('minimized button has enter transitions', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)
        ->toContain('opacity-0 scale-50')
        ->toContain('opacity-75 scale-100');
});

// --- Accessibility ---

it('has aria-label on minimize button', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('aria-label="Minimize"');
});

it('has aria-label on minimized button', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect($html)->toContain('aria-label="Show debug info"');
});

it('has aria-expanded on both toggle buttons', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    // Both buttons bind aria-expanded to the Alpine state
    expect(substr_count($html, ':aria-expanded="expanded.toString()"'))->toBe(2);
});

it('has aria-hidden on decorative SVG icons', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect(substr_count($html, 'aria-hidden="true"'))->toBe(2);
});

// --- Toggle interaction ---

it('both buttons call toggle() on click', function () {
    $html = Blade::render('<x-bt-debug-breakpoints />');

    expect(substr_count($html, '@click="toggle()"'))->toBe(2);
});
