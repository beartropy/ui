<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a basic card with x-data', function () {
    $html = Blade::render('<x-bt-card>Card content</x-bt-card>');

    expect($html)
        ->toContain('x-data="{}"')
        ->toContain('Card content');
});

it('renders slot content', function () {
    $html = Blade::render('<x-bt-card><p>Inner paragraph</p></x-bt-card>');

    expect($html)->toContain('<p>Inner paragraph</p>');
});

it('renders title with border-b', function () {
    $html = Blade::render('<x-bt-card title="My Title">Content</x-bt-card>');

    expect($html)
        ->toContain('My Title')
        ->toContain('border-b');
});

it('omits title div when no title is provided', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->not->toContain('border-b');
});

it('renders footer with border-t', function () {
    $html = Blade::render('<x-bt-card footer="Footer text">Content</x-bt-card>');

    expect($html)
        ->toContain('Footer text')
        ->toContain('border-t');
});

it('omits footer when not provided', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->not->toContain('border-t');
});

it('renders footer as named slot', function () {
    $html = Blade::render('
        <x-bt-card>
            Content
            <x-slot:footer>
                <button>Save</button>
            </x-slot:footer>
        </x-bt-card>
    ');

    expect($html)
        ->toContain('<button>Save</button>')
        ->toContain('border-t');
});

it('removes border and shadow with noBorder', function () {
    $html = Blade::render('<x-bt-card :noBorder="true">Content</x-bt-card>');

    expect($html)
        ->toContain('border-0')
        ->toContain('shadow-none');
});

it('has border by default', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)
        ->toContain('border border-gray-200')
        ->not->toContain('border-0');
});

it('is non-collapsable by default', function () {
    $html = Blade::render('<x-bt-card title="Title">Content</x-bt-card>');

    expect($html)
        ->toContain('x-data="{}"')
        ->not->toContain('cursor-pointer')
        ->not->toContain('x-show');
});

it('adds click handler and chevron when collapsable', function () {
    $html = Blade::render('<x-bt-card title="Title" :collapsable="true">Content</x-bt-card>');

    expect($html)
        ->toContain('@click="open = !open"')
        ->toContain('<svg');
});

it('defaults to open when collapsable', function () {
    $html = Blade::render('<x-bt-card :collapsable="true">Content</x-bt-card>');

    expect($html)->toContain('open: true');
});

it('can start collapsed with defaultOpen false', function () {
    $html = Blade::render('<x-bt-card :collapsable="true" :defaultOpen="false">Content</x-bt-card>');

    expect($html)->toContain('open: false');
});

it('uses x-show x-collapse and x-cloak when collapsable', function () {
    $html = Blade::render('<x-bt-card :collapsable="true">Content</x-bt-card>');

    expect($html)
        ->toContain('x-show="open"')
        ->toContain('x-collapse')
        ->toContain('x-cloak');
});

it('adds cursor-pointer and select-none to collapsable title', function () {
    $html = Blade::render('<x-bt-card title="Title" :collapsable="true">Content</x-bt-card>');

    expect($html)
        ->toContain('cursor-pointer')
        ->toContain('select-none');
});

it('shows loading spinner with wire:target', function () {
    $html = Blade::render('<x-bt-card wire:target="save">Content</x-bt-card>');

    expect($html)
        ->toContain('wire:loading.flex')
        ->toContain('wire:target="save"')
        ->toContain('animate-spin');
});

it('does not render spinner without wire:target', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)
        ->not->toContain('wire:loading')
        ->not->toContain('animate-spin');
});

it('applies beartropy color preset classes', function () {
    $html = Blade::render('<x-bt-card color="beartropy">Content</x-bt-card>');

    expect($html)
        ->toContain('bg-white')
        ->toContain('rounded-xl')
        ->toContain('shadow-xl');
});

it('applies modal color preset classes', function () {
    $html = Blade::render('<x-bt-card color="modal">Content</x-bt-card>');

    expect($html)
        ->toContain('bg-white')
        ->toContain('rounded-xl')
        ->toContain('shadow-xl');
});

it('applies neutral color preset classes', function () {
    $html = Blade::render('<x-bt-card color="neutral">Content</x-bt-card>');

    expect($html)
        ->toContain('bg-white')
        ->toContain('rounded-xl')
        ->toContain('shadow-xl');
});

it('merges custom attributes', function () {
    $html = Blade::render('<x-bt-card id="my-card" data-section="info">Content</x-bt-card>');

    expect($html)
        ->toContain('id="my-card"')
        ->toContain('data-section="info"');
});

it('combines all features together', function () {
    $html = Blade::render('
        <x-bt-card
            title="Full Card"
            footer="Footer"
            :collapsable="true"
            :defaultOpen="true"
            color="beartropy"
            wire:target="action"
        >
            Card content
        </x-bt-card>
    ');

    expect($html)
        ->toContain('Full Card')
        ->toContain('Footer')
        ->toContain('Card content')
        ->toContain('open: true')
        ->toContain('wire:target="action"')
        ->toContain('cursor-pointer')
        ->toContain('animate-spin');
});

it('always has relative positioning', function () {
    $html = Blade::render('<x-bt-card>Content</x-bt-card>');

    expect($html)->toContain('relative');
});
