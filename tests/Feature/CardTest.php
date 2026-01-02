<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic card component', function () {
    $html = Blade::render('<x-bt-card>Card content</x-bt-card>');

    expect($html)->toContain('Card content');
    expect($html)->toContain('x-data');
});

it('renders without title by default', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->toContain('Content');
});

it('can render with title', function () {
    $html = Blade::render('<x-bt-card title="Card Title">Content</x-bt-card>');

    expect($html)->toContain('Card Title');
    expect($html)->toContain('Content');
});

it('renders title with border when not collapsable', function () {
    $html = Blade::render('<x-bt-card title="Title">Content</x-bt-card>');

    expect($html)->toContain('border-b');
});

it('can render with footer', function () {
    $html = Blade::render('<x-bt-card footer="Card Footer">Content</x-bt-card>');

    expect($html)->toContain('Card Footer');
    expect($html)->toContain('border-t');
});

it('renders with border by default', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->toContain('border');
});

it('can render without border', function () {
    $html = Blade::render('<x-bt-card :noBorder="true">Content</x-bt-card>');

    expect($html)->toContain('border-0');
    expect($html)->toContain('shadow-none');
});

it('renders as non-collapsable by default', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->not->toContain('@click="open = !open"');
});

it('can be collapsable', function () {
    $html = Blade::render('<x-bt-card title="Title" :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('open:');
    expect($html)->toContain('@click="open = !open"');
});

it('collapsable cards default to open', function () {
    $html = Blade::render('<x-bt-card :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('open: true');
});

it('can start collapsed', function () {
    $html = Blade::render('<x-bt-card :collapsable="true" :defaultOpen="false">Content</x-bt-card>');

    expect($html)->toContain('open: false');
});

it('renders collapse icon when collapsable', function () {
    $html = Blade::render('<x-bt-card title="Title" :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('<svg');
    expect($html)->toContain('rotate-180');
});

it('uses x-collapse for smooth transitions', function () {
    $html = Blade::render('<x-bt-card :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('x-collapse');
    expect($html)->toContain('x-transition');
});

it('hides content with x-show when collapsable', function () {
    $html = Blade::render('<x-bt-card :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('x-show="open"');
});

it('title is clickable when collapsable', function () {
    $html = Blade::render('<x-bt-card title="Title" :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('cursor-pointer');
    expect($html)->toContain('select-none');
});

it('supports wire:target for loading state', function () {
    $html = Blade::render('<x-bt-card wire:target="save">Content</x-bt-card>');

    expect($html)->toContain('wire:loading');
    expect($html)->toContain('wire:target="save"');
});

it('renders loading spinner with wire:target', function () {
    $html = Blade::render('<x-bt-card wire:target="submit">Content</x-bt-card>');

    expect($html)->toContain('animate-spin');
});

it('renders with relative positioning', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->toContain('relative');
});

it('supports color presets', function () {
    $htmlPrimary = Blade::render('<x-bt-card color="primary">Content</x-bt-card>');
    expect($htmlPrimary)->toContain('x-data');

    $htmlDanger = Blade::render('<x-bt-card color="danger">Content</x-bt-card>');
    expect($htmlDanger)->toContain('x-data');
});

it('supports size presets', function () {
    $htmlSm = Blade::render('<x-bt-card size="sm">Content</x-bt-card>');
    expect($htmlSm)->toContain('x-data');

    $htmlLg = Blade::render('<x-bt-card size="lg">Content</x-bt-card>');
    expect($htmlLg)->toContain('x-data');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-card 
            title="Full Featured Card"
            footer="Card Footer"
            :collapsable="true"
            :defaultOpen="true"
            :noBorder="false"
            color="primary"
            size="lg"
            wire:target="action"
        >
            Card content here
        </x-bt-card>
    ');

    expect($html)->toContain('Full Featured Card');
    expect($html)->toContain('Card Footer');
    expect($html)->toContain('Card content here');
    expect($html)->toContain('open: true');
    expect($html)->toContain('wire:target="action"');
});
