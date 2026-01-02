<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic slider component', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('Content');
    expect($html)->toContain('x-data');
});

it('renders with Alpine.js data', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('show:');
    expect($html)->toContain('x-data');
});

it('can render on right side by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('right');
});

it('can render on left side', function () {
    $html = Blade::render('<x-bt-slider side="left">Content</x-bt-slider>');

    expect($html)->toContain('left');
});

it('can render with backdrop by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('backdrop');
});

it('can render with blur by default', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('backdrop-blur');
});

it('can render without blur', function () {
    $html = Blade::render('<x-bt-slider :blur="false">Content</x-bt-slider>');

    expect($html)->not->toContain('backdrop-blur');
});

it('can render with default max width', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('max-w-xl');
});

it('can render with custom max width', function () {
    $html = Blade::render('<x-bt-slider max-width="max-w-7xl">Content</x-bt-slider>');

    expect($html)->toContain('max-w-7xl');
});

it('can render with footer slot', function () {
    $html = Blade::render('
        <x-bt-slider>
            Body Content
            <x-slot:footer>
                Footer Actions
            </x-slot:footer>
        </x-bt-slider>
    ');

    expect($html)->toContain('Body Content');
    expect($html)->toContain('Footer Actions');
});

it('can render with title', function () {
    $html = Blade::render('<x-bt-slider title="Settings">Content</x-bt-slider>');

    expect($html)->toContain('Settings');
});

it('renders close button', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('x-on:click');
    expect($html)->toContain('show = false');
});

it('can render with custom header padding', function () {
    $html = Blade::render('<x-bt-slider header-padding="p-8">Content</x-bt-slider>');

    expect($html)->toContain('p-8');
});

it('handles ESC key to close', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('keydown.escape');
});

it('renders with slide animation classes', function () {
    $html = Blade::render('<x-bt-slider side="right">Content</x-bt-slider>');

    expect($html)->toContain('translate-x');
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-slider class="custom-slider">Content</x-bt-slider>');

    expect($html)->toContain('custom-slider');
});

it('renders as dialog with proper ARIA', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('role="dialog"');
    expect($html)->toContain('aria-modal="true"');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-slider 
            side="left"
            title="User Settings"
            max-width="max-w-2xl"
            :backdrop="true"
            :blur="true"
            header-padding="p-6"
            class="custom-settings-slider"
        >
            <x-slot:footer>
                <button>Save</button>
                <button>Cancel</button>
            </x-slot:footer>
            
            Settings content here
        </x-bt-slider>
    ');

    expect($html)->toContain('User Settings');
    expect($html)->toContain('Settings content here');
    expect($html)->toContain('Save');
    expect($html)->toContain('Cancel');
    expect($html)->toContain('max-w-2xl');
    expect($html)->toContain('custom-settings-slider');
});

it('can render simple slider with minimal props', function () {
    $html = Blade::render('<x-bt-slider>Minimal Content</x-bt-slider>');

    expect($html)->toContain('Minimal Content');
    expect($html)->toContain('x-data');
});

it('renders transition classes', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('transition');
});

it('renders with proper z-index', function () {
    $html = Blade::render('<x-bt-slider>Content</x-bt-slider>');

    expect($html)->toContain('z-50');
});
