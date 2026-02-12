<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders default structure with Alpine state and dialog role', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->toContain('x-data=')
        ->toContain('x-cloak')
        ->toContain('x-modelable="show"')
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('class="relative z-50"');
});

it('renders aria-labelledby linked to title id', function () {
    $html = Blade::render('<x-bt-slider name="settings">Content</x-bt-slider>');

    expect($html)
        ->toContain('aria-labelledby="slider-settings-title"')
        ->toContain('id="slider-settings-title"');
});

it('generates unique slider id when no name is given', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toMatch('/aria-labelledby="slider-[a-f0-9]+-title"/');
});

it('renders focus trap directive', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('x-trap.noscroll="show"');
});

// --- Alpine State ---

it('initializes show as false by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('show:');
});

it('registers event listeners when name is set', function () {
    $html = Blade::render('<x-bt-slider name="my-panel">Content</x-bt-slider>');

    expect($html)
        ->toContain("sliderName: 'my-panel'")
        ->toContain("window.addEventListener('open-slider'")
        ->toContain("window.addEventListener('close-slider'")
        ->toContain("window.addEventListener('toggle-slider'");
});

it('cleans up event listeners on destroy', function () {
    $html = Blade::render('<x-bt-slider name="my-panel">Content</x-bt-slider>');

    expect($html)
        ->toContain('destroy()')
        ->toContain("window.removeEventListener('open-slider'")
        ->toContain("window.removeEventListener('close-slider'")
        ->toContain("window.removeEventListener('toggle-slider'");
});

it('does not register event listeners when name is not set', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->not->toContain("window.addEventListener('open-slider'")
        ->not->toContain("window.addEventListener('close-slider'");
});

// --- Backdrop ---

it('renders backdrop by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->toContain('bg-gray-500/75')
        ->toContain('dark:bg-gray-900/80')
        ->toContain('transition-opacity');
});

it('renders backdrop with blur by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('backdrop-blur-sm');
});

it('renders backdrop without blur when disabled', function () {
    $html = Blade::render('<x-bt-slider :blur="false">Content</x-bt-slider>');

    expect($html)->not->toContain('backdrop-blur-sm');
});

it('hides backdrop when disabled', function () {
    $html = Blade::render('<x-bt-slider :backdrop="false">Content</x-bt-slider>');

    expect($html)->not->toContain('bg-gray-500/75');
});

it('allows backdrop click to close by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    // Backdrop div should have click handler
    expect($html)->toMatch('/bg-gray-500\/75.*x-on:click="show = false"/s');
});

it('prevents backdrop click close in static mode', function () {
    $html = Blade::render('<x-bt-slider :static="true">Content</x-bt-slider>');

    // Backdrop should exist but without click handler on it
    expect($html)
        ->toContain('bg-gray-500/75')
        ->not->toMatch('/bg-gray-500\/75[^<]*x-on:click="show = false"/s');
});

// --- Side ---

it('slides from right by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->toContain('right-0 pl-10')
        ->toContain('x-transition:enter-start="translate-x-full"')
        ->toContain('x-transition:leave-end="translate-x-full"');
});

it('slides from left when side is left', function () {
    $html = Blade::render('<x-bt-slider side="left">Content</x-bt-slider>');

    expect($html)
        ->toContain('left-0 pr-10')
        ->toContain('x-transition:enter-start="-translate-x-full"')
        ->toContain('x-transition:leave-end="-translate-x-full"');
});

// --- Panel ---

it('renders default max width', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('max-w-xl 2xl:max-w-4xl');
});

it('renders custom max width', function () {
    $html = Blade::render('<x-bt-slider max-width="max-w-7xl">Content</x-bt-slider>');

    expect($html)->toContain('max-w-7xl');
});

it('renders panel with slide transitions', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->toContain('transform transition ease-in-out duration-500 sm:duration-700')
        ->toContain('translate-x-0');
});

it('renders panel container with shadow', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('bg-gray-50 dark:bg-gray-900 shadow-xl');
});

// --- Header ---

it('renders title in header', function () {
    $html = Blade::render('<x-bt-slider title="Settings">Content</x-bt-slider>');

    expect($html)->toContain('Settings');
});

it('renders default header padding', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('px-4 py-3 sm:px-6');
});

it('renders custom header padding', function () {
    $html = Blade::render('<x-bt-slider header-padding="p-8">Content</x-bt-slider>');

    expect($html)->toContain('p-8');
});

it('renders close button with localized sr-only text', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)
        ->toContain('x-on:click="show = false"')
        ->toContain('<span class="sr-only">Close</span>');
});

// --- Body ---

it('renders body content in scrollable area', function () {
    $html = Blade::render('<x-bt-slider>Body content here</x-bt-slider>');

    expect($html)
        ->toContain('Body content here')
        ->toContain('flex-1 overflow-y-auto');
});

it('renders default body padding', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    // Body div should include p-4 (default bodyPadding)
    expect($html)->toMatch('/flex-1 overflow-y-auto dark:text-gray-300 p-4/');
});

it('renders custom body padding', function () {
    $html = Blade::render('<x-bt-slider body-padding="p-6 sm:p-8">Content</x-bt-slider>');

    expect($html)->toContain('p-6 sm:p-8');
});

// --- Footer ---

it('renders footer slot when provided', function () {
    $html = Blade::render('
        <x-bt-slider>
            Body
            <x-slot:footer>
                <button>Save</button>
            </x-slot:footer>
        </x-bt-slider>
    ');

    expect($html)
        ->toContain('<button>Save</button>')
        ->toContain('border-t border-gray-200 dark:border-gray-700');
});

it('does not render footer when slot is not provided', function () {
    $html = Blade::render('<x-bt-slider>Body only</x-bt-slider>');

    // Footer has a specific shadow style — should not be present
    expect($html)->not->toContain('shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]');
});

it('renders footer with dark mode styles', function () {
    $html = Blade::render('
        <x-bt-slider>
            Body
            <x-slot:footer>Footer</x-slot:footer>
        </x-bt-slider>
    ');

    expect($html)->toContain('dark:bg-gray-800');
});

// --- Keyboard ---

it('closes on ESC key', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('x-on:keydown.escape.window="show = false"');
});

// --- Custom Attributes ---

it('passes through custom classes', function () {
    $html = Blade::render('<x-bt-slider class="custom-slider">Content</x-bt-slider>');

    expect($html)->toContain('custom-slider');
});

// --- Combined ---

it('renders with all features combined', function () {
    $html = Blade::render('
        <x-bt-slider
            name="full"
            side="left"
            title="Full Example"
            max-width="max-w-2xl"
            :backdrop="true"
            :blur="true"
            header-padding="p-6"
            body-padding="p-8"
        >
            Body content
            <x-slot:footer>
                <button>Cancel</button>
                <button>Save</button>
            </x-slot:footer>
        </x-bt-slider>
    ');

    expect($html)
        ->toContain('Full Example')
        ->toContain('Body content')
        ->toContain('Cancel')
        ->toContain('Save')
        ->toContain('max-w-2xl')
        ->toContain('left-0 pr-10')
        ->toContain('p-6')
        ->toContain('p-8')
        ->toContain('x-trap.noscroll="show"')
        ->toContain('aria-labelledby="slider-full-title"')
        ->toContain('id="slider-full-title"');
});
