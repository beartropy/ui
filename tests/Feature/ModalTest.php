<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders with default props', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)
        ->toContain('x-teleport="body"')
        ->toContain('x-cloak')
        ->toContain('x-show="localOpen"')
        ->toContain('max-w-3xl')
        ->toContain('z-30')
        ->toContain('backdrop-blur-none')
        ->toContain('bg-white dark:bg-gray-900')
        ->toContain('items-start')
        ->toContain('mt-24 sm:mt-32')
        ->toContain('Content');
});

it('generates unique modal ID when none given', function () {
    $html1 = Blade::render('<x-bt-modal>Test 1</x-bt-modal>');
    $html2 = Blade::render('<x-bt-modal>Test 2</x-bt-modal>');

    preg_match('/id="(modal-[^"]+)"/', $html1, $m1);
    preg_match('/id="(modal-[^"]+)"/', $html2, $m2);

    expect($m1[1])->toStartWith('modal-');
    expect($m2[1])->toStartWith('modal-');
    expect($m1[1])->not->toBe($m2[1]);
});

it('uses custom ID in element and event names', function () {
    $html = Blade::render('<x-bt-modal id="my-modal">Content</x-bt-modal>');

    expect($html)
        ->toContain('id="my-modal"')
        ->toContain('x-ref="my-modal"')
        ->toContain('open-modal-my-modal')
        ->toContain('close-modal-my-modal');
});

it('uses wire:model value as modal ID', function () {
    $html = Blade::render('<x-bt-modal wire:model="showModal">Content</x-bt-modal>');

    expect($html)
        ->toContain('id="showModal"')
        ->toContain('open-modal-showModal')
        ->toContain('close-modal-showModal')
        ->toContain('$wire.showModal');
});

it('renders with teleport by default', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('x-teleport="body"');
});

it('can disable teleport', function () {
    $html = Blade::render('<x-bt-modal :teleport="false">Content</x-bt-modal>');

    expect($html)
        ->not->toContain('x-teleport')
        ->toContain('x-show="localOpen"');
});

it('supports custom teleport target', function () {
    $html = Blade::render('<x-bt-modal teleportTarget="#app">Content</x-bt-modal>');

    expect($html)->toContain('x-teleport="#app"');
});

it('renders correct max-width class for each size', function () {
    $sizes = [
        'sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg',
        'xl' => 'max-w-xl', '2xl' => 'max-w-2xl', '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl', '5xl' => 'max-w-5xl', '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl', 'full' => 'max-w-full',
    ];

    foreach ($sizes as $key => $class) {
        $html = Blade::render("<x-bt-modal maxWidth=\"{$key}\">Content</x-bt-modal>");
        expect($html)->toContain($class);
    }
});

it('renders default 3xl max width', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('max-w-3xl');
});

it('renders default backdrop-blur-none', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('backdrop-blur-none');
});

it('renders correct blur class for each level', function () {
    $blurs = [
        'none' => 'backdrop-blur-none', 'sm' => 'backdrop-blur-sm',
        'md' => 'backdrop-blur-md', 'lg' => 'backdrop-blur-lg',
        'xl' => 'backdrop-blur-xl', '2xl' => 'backdrop-blur-2xl',
        '3xl' => 'backdrop-blur-3xl',
    ];

    foreach ($blurs as $key => $class) {
        $html = Blade::render("<x-bt-modal blur=\"{$key}\">Content</x-bt-modal>");
        expect($html)->toContain($class);
    }
});

it('renders custom z-index', function () {
    $html = Blade::render('<x-bt-modal zIndex="50">Content</x-bt-modal>');

    expect($html)->toContain('z-50');
});

it('renders default z-30', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('z-30');
});

it('shows close button by default (SVG present)', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)
        ->toContain('<svg')
        ->toContain('M6 18L18 6M6 6l12 12');
});

it('hides close button when showCloseButton=false and not styled', function () {
    $html = Blade::render('<x-bt-modal :showCloseButton="false" :styled="false">Content</x-bt-modal>');

    expect($html)->not->toContain('M6 18L18 6M6 6l12 12');
});

it('shows close button in styled mode even when showCloseButton=false', function () {
    $html = Blade::render('<x-bt-modal :showCloseButton="false" :styled="true">Content</x-bt-modal>');

    expect($html)->toContain('M6 18L18 6M6 6l12 12');
});

it('closes on click outside by default', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('@click="close()"');
});

it('disables close on click outside', function () {
    $html = Blade::render('<x-bt-modal :closeOnClickOutside="false">Content</x-bt-modal>');

    // The overlay div should NOT have @click="close()"
    // Extract the overlay div (first inner div with absolute inset-0)
    preg_match('/<div x-show="localOpen"\s+class="absolute inset-0[^"]*"[^>]*>/', $html, $overlay);
    expect($overlay[0])->not->toContain('@click="close()"');
});

it('renders centered positioning', function () {
    $html = Blade::render('<x-bt-modal :centered="true">Content</x-bt-modal>');

    expect($html)
        ->toContain('items-center')
        ->not->toContain('items-start');
});

it('renders non-centered (default) with top margin', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)
        ->toContain('items-start')
        ->toContain('mt-24 sm:mt-32');
});

it('centered mode has no top margin', function () {
    $html = Blade::render('<x-bt-modal :centered="true">Content</x-bt-modal>');

    // The modal container div should not have mt-24
    expect($html)->toContain('items-center');
    // mt-24 is conditionally applied only when not centered
    preg_match('/class="relative w-full[^"]*"/', $html, $container);
    expect($container[0])->not->toContain('mt-24');
});

it('renders custom bgColor', function () {
    $html = Blade::render('<x-bt-modal bgColor="bg-gray-800">Content</x-bt-modal>');

    expect($html)
        ->toContain('bg-gray-800')
        ->not->toContain('bg-white dark:bg-gray-900');
});

it('enables styled mode with wrapper classes', function () {
    $html = Blade::render('
        <x-bt-modal :styled="true">
            <x-slot:title>My Title</x-slot:title>
            Content
            <x-slot:footer>Footer</x-slot:footer>
        </x-bt-modal>
    ');

    expect($html)
        ->toContain('text-xl font-semibold')
        ->toContain('border-b border-gray-200')
        ->toContain('My Title')
        ->toContain('my-4 text-gray-800')
        ->toContain('flex justify-end items-center')
        ->toContain('border-t border-gray-200')
        ->toContain('Footer');
});

it('renders title slot without styled classes when not styled', function () {
    $html = Blade::render('
        <x-bt-modal>
            <x-slot:title>My Title</x-slot:title>
            Content
        </x-bt-modal>
    ');

    expect($html)
        ->toContain('My Title')
        ->not->toContain('text-xl font-semibold');
});

it('renders footer slot without styled classes when not styled', function () {
    $html = Blade::render('
        <x-bt-modal>
            <x-slot:footer>Footer</x-slot:footer>
            Content
        </x-bt-modal>
    ');

    expect($html)
        ->toContain('Footer')
        ->not->toContain('flex justify-end items-center border-t');
});

it('binds escape key to close', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('x-on:keydown.escape.window="close()"');
});

it('includes overflow-hidden x-effect', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('overflow-hidden');
    expect($html)->toContain('x-effect');
});

it('generates open and close event listeners', function () {
    $html = Blade::render('<x-bt-modal id="test-modal">Content</x-bt-modal>');

    expect($html)
        ->toContain('x-on:open-modal-test-modal.window="openModal()"')
        ->toContain('x-on:close-modal-test-modal.window="close()"');
});

it('passes extra attributes through', function () {
    $html = Blade::render('<x-bt-modal data-test="modal" class="extra">Content</x-bt-modal>');

    expect($html)->toContain('data-test="modal"');
});

it('supports kebab-case close-on-click-outside attribute', function () {
    $html = Blade::render('<x-bt-modal :close-on-click-outside="false">Content</x-bt-modal>');

    preg_match('/<div x-show="localOpen"\s+class="absolute inset-0[^"]*"[^>]*>/', $html, $overlay);
    expect($overlay[0])->not->toContain('@click="close()"');
});
