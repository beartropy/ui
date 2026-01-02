<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic alert component', function () {
    $html = Blade::render('<x-bt-alert>This is an alert</x-bt-alert>');

    expect($html)->toContain('x-data');
    expect($html)->toContain('This is an alert');
    expect($html)->toContain('role="alert"');
});

it('renders with Alpine.js state management', function () {
    $html = Blade::render('<x-bt-alert>Alert message</x-bt-alert>');

    expect($html)->toContain('open:');
    expect($html)->toContain('x-show="open"');
});

it('can render with title', function () {
    $html = Blade::render('<x-bt-alert title="Warning Title">This is a warning</x-bt-alert>');

    expect($html)->toContain('Warning Title');
    expect($html)->toContain('This is a warning');
});

it('can render without title', function () {
    $html = Blade::render('<x-bt-alert>Just a message</x-bt-alert>');

    expect($html)->toContain('Just a message');
});

it('can render with custom icon', function () {
    $html = Blade::render('<x-bt-alert icon="check-circle">Success message</x-bt-alert>');

    expect($html)->toContain('x-data');
    expect($html)->toContain('Success message');
});

it('can render without icon using noIcon prop', function () {
    $html = Blade::render('<x-bt-alert :noIcon="true">No icon here</x-bt-alert>');

    expect($html)->toContain('No icon here');
});

it('can render as dismissible', function () {
    $html = Blade::render('<x-bt-alert :dismissible="true">Dismissible alert</x-bt-alert>');

    expect($html)->toContain('Dismissible alert');
    expect($html)->toContain('@click="open = false"');
});

it('can render without dismiss button by default', function () {
    $html = Blade::render('<x-bt-alert>Non-dismissible</x-bt-alert>');

    expect($html)->not->toContain('@click="open = false"');
});

it('renders dismiss button with proper accessibility', function () {
    $html = Blade::render('<x-bt-alert :dismissible="true">Message</x-bt-alert>');

    expect($html)->toContain('aria-label');
    expect($html)->toContain('type="button"');
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-alert class="custom-alert-class">Message</x-bt-alert>');

    expect($html)->toContain('custom-alert-class');
});

it('supports different color presets', function () {
    $htmlPrimary = Blade::render('<x-bt-alert color="primary">Primary</x-bt-alert>');
    expect($htmlPrimary)->toContain('x-data');

    $htmlDanger = Blade::render('<x-bt-alert color="danger">Danger</x-bt-alert>');
    expect($htmlDanger)->toContain('x-data');
});

it('renders with opacity transition', function () {
    $html = Blade::render('<x-bt-alert>Transitioning</x-bt-alert>');

    expect($html)->toContain('x-transition');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-alert 
            title="Important Notice"
            icon="exclamation-triangle"
            :dismissible="true"
            class="custom-combined-alert"
            color="warning"
        >
            This is a complete alert with all features enabled.
        </x-bt-alert>
    ');

    expect($html)->toContain('Important Notice');
    expect($html)->toContain('This is a complete alert with all features enabled.');
    expect($html)->toContain('@click="open = false"');
    expect($html)->toContain('custom-combined-alert');
    expect($html)->toContain('role="alert"');
});

it('renders with proper semantic HTML structure', function () {
    $html = Blade::render('<x-bt-alert>Semantic alert</x-bt-alert>');

    expect($html)->toContain('role="alert"');
});

it('has unique content wrapper structure', function () {
    $html = Blade::render('<x-bt-alert title="Title">Content</x-bt-alert>');

    expect($html)->toContain('<div');
    expect($html)->toContain('Title');
    expect($html)->toContain('Content');
});
