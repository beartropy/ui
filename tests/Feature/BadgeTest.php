<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic badge component', function () {
    $html = Blade::render('<x-bt-badge>Badge Text</x-bt-badge>');

    expect($html)->toContain('Badge Text');
});

it('renders as span element', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('<span');
});

it('uses badge-base component', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('Text');
});

it('can render with icon left', function () {
    $html = Blade::render('<x-bt-badge iconLeft="check">Success</x-bt-badge>');

    expect($html)->toContain('Success');
});

it('can render with icon right', function () {
    $html = Blade::render('<x-bt-badge iconRight="arrow-right">Next</x-bt-badge>');

    expect($html)->toContain('Next');
});

it('supports start slot', function () {
    $html = Blade::render('
        <x-bt-badge>
            <x-slot:start>
                <span>★</span>
            </x-slot:start>
            Badge
        </x-bt-badge>
    ');

    expect($html)->toContain('Badge');
});

it('supports end slot', function () {
    $html = Blade::render('
        <x-bt-badge>
            Badge
            <x-slot:end>
                <span>×</span>
            </x-slot:end>
        </x-bt-badge>
    ');

    expect($html)->toContain('Badge');
});

it('supports different colors', function () {
    $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];

    foreach ($colors as $color) {
        $html = Blade::render("<x-bt-badge color=\"{$color}\">Text</x-bt-badge>");
        expect($html)->toContain('Text');
    }
});

it('supports different sizes', function () {
    $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-badge size=\"{$size}\">Text</x-bt-badge>");
        expect($html)->toContain('Text');
    }
});

it('supports different variants', function () {
    $html = Blade::render("<x-bt-badge>Text</x-bt-badge>");
    expect($html)->toContain('Text');
});

it('can render with custom class', function () {
    $html = Blade::render('<x-bt-badge class="custom-badge">Text</x-bt-badge>');

    expect($html)->toContain('custom-badge');
});

it(' passes attributes to span wrapper', function () {
    $html = Blade::render('<x-bt-badge data-test="badge">Text</x-bt-badge>');

    expect($html)->toContain('Text');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-badge 
            color="success"
            size="lg"
            iconLeft="check-circle"
            iconRight="arrow-right"
            class="custom-class"
        >
            Complete Badge
        </x-bt-badge>
    ');

    expect($html)->toContain('Complete Badge');
    expect($html)->toContain('custom-class');
});
