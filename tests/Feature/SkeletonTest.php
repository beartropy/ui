<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a div with wire:loading and animate-pulse by default', function () {
    $html = Blade::render('<x-bt-skeleton />');

    expect($html)
        ->toContain('<div')
        ->toContain('wire:loading')
        ->toContain('animate-pulse')
        ->toContain('relative');
});

it('renders wire:init when init is provided', function () {
    $html = Blade::render('<x-bt-skeleton init="loadData" />');

    expect($html)->toContain('wire:init="loadData"');
});

it('does not render wire:init when init is null', function () {
    $html = Blade::render('<x-bt-skeleton />');

    expect($html)->not->toContain('wire:init');
});

it('does not render wire:init when init is empty string', function () {
    $html = Blade::render('<x-bt-skeleton init="" />');

    expect($html)->not->toContain('wire:init');
});

it('renders card shape with h3 title and body lines by default', function () {
    $html = Blade::render('<x-bt-skeleton />');

    expect($html)
        ->toContain('<h3')
        ->toContain('animate-pulse')
        ->toContain('p-3');
});

it('renders card shape with custom line count', function () {
    $html = Blade::render('<x-bt-skeleton :lines="3" />');

    expect($html)
        ->toContain('<h3')
        ->toContain('w-full')
        ->toContain('w-4/5')
        ->toContain('w-3/5');
});

it('renders card shape with default 3 body lines when lines=1', function () {
    $html = Blade::render('<x-bt-skeleton shape="card" :lines="1" />');

    expect($html)
        ->toContain('<h3')
        ->toContain('w-3/4')
        ->toContain('w-4/5')
        ->toContain('w-full');
});

it('renders rectangle shape as a solid block', function () {
    $html = Blade::render('<x-bt-skeleton shape="rectangle" />');

    expect($html)
        ->toContain('animate-pulse')
        ->toContain('bg-slate-200/90')
        ->not->toContain('<h3')
        ->not->toContain('<svg');
});

it('renders image shape with SVG icon', function () {
    $html = Blade::render('<x-bt-skeleton shape="image" />');

    expect($html)
        ->toContain('<svg')
        ->toContain('viewBox="0 0 24 24"')
        ->toContain('aspect-[4/3]');
});

it('renders table shape with header and rows', function () {
    $html = Blade::render('<x-bt-skeleton shape="table" :rows="3" :cols="4" />');

    // Header row has mb-1 class
    expect($html)->toContain('mb-1');

    // Count flex-1 cells: 4 cols in header + 4 cols * 3 rows = 16 total
    $cellCount = substr_count($html, 'flex-1');
    expect($cellCount)->toBe(16);
});

it('renders none shape with multiple lines of varying widths', function () {
    $html = Blade::render('<x-bt-skeleton shape="none" :lines="3" />');

    expect($html)
        ->toContain('space-y-2')
        ->toContain('w-full')
        ->toContain('w-4/5')
        ->toContain('w-3/5')
        ->not->toContain('<h3')
        ->not->toContain('p-3');
});

it('renders none shape with single line as fallback block with min-h', function () {
    $html = Blade::render('<x-bt-skeleton shape="none" :lines="1" />');

    expect($html)
        ->toContain('min-h-[0.75rem]')
        ->toContain('animate-pulse')
        ->not->toContain('space-y-2')
        ->not->toContain('<h3');
});

it('suppresses fallback min-h when height class is present', function () {
    $html = Blade::render('<x-bt-skeleton shape="none" :lines="1" class="h-20" />');

    expect($html)->not->toContain('min-h-[0.75rem]');
});

it('uses rounded-lg by default', function () {
    $html = Blade::render('<x-bt-skeleton />');

    expect($html)->toContain('rounded-lg');
});

it('supports all rounded variants', function () {
    $variants = [
        'none' => 'rounded-none',
        'sm'   => 'rounded-sm',
        'md'   => 'rounded-md',
        'lg'   => 'rounded-lg',
        'xl'   => 'rounded-xl',
        'full' => 'rounded-full',
    ];

    foreach ($variants as $input => $expected) {
        $html = Blade::render("<x-bt-skeleton rounded=\"{$input}\" />");
        expect($html)->toContain($expected);
    }
});

it('renders a custom tag', function () {
    $html = Blade::render('<x-bt-skeleton tag="span" />');

    expect($html)
        ->toContain('<span')
        ->toContain('</span>');
});

it('renders slot content in wire:loading.remove div', function () {
    $html = Blade::render('
        <x-bt-skeleton init="load">
            <p>Real content</p>
        </x-bt-skeleton>
    ');

    expect($html)
        ->toContain('wire:loading.remove')
        ->toContain('<p>Real content</p>');
});

it('merges custom classes via attributes', function () {
    $html = Blade::render('<x-bt-skeleton class="w-64 h-32" />');

    expect($html)
        ->toContain('w-64')
        ->toContain('h-32')
        ->toContain('relative');
});

it('merges custom attributes', function () {
    $html = Blade::render('<x-bt-skeleton id="my-skeleton" data-test="true" />');

    expect($html)
        ->toContain('id="my-skeleton"')
        ->toContain('data-test="true"');
});
