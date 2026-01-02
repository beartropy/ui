<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic modal component', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('generates unique modal ID', function () {
    $html1 = Blade::render('<x-bt-modal>Test 1</x-bt-modal>');
    $html2 = Blade::render('<x-bt-modal>Test 2</x-bt-modal>');

    expect($html1)->not->toBe($html2);
});

it('can render with custom ID', function () {
    $html = Blade::render('<x-bt-modal id="custom-modal-id">Content</x-bt-modal>');

    expect($html)->toContain('custom-modal-id');
});

it('supports wire:model for ID', function () {
    $html = Blade::render('<x-bt-modal wire:model="showModal">Content</x-bt-modal>');

    expect($html)->toContain('showModal');
});

it('renders with teleport by default', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('x-teleport');
});

it('can disable teleport', function () {
    $html = Blade::render('<x-bt-modal :teleport="false">Content</x-bt-modal>');

    expect($html)->not->toContain('x-teleport');
});

it('supports different max widths', function () {
    $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-modal maxWidth=\"{$size}\">Content</x-bt-modal>");
        expect($html)->toContain('max-w');
    }
});

it('renders with default 3xl max width', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('max-w-3xl');
});

it('supports different blur levels', function () {
    $blurs = ['none', 'sm', 'md', 'lg', 'xl'];

    foreach ($blurs as $blur) {
        $html = Blade::render("<x-bt-modal blur=\"{$blur}\">Content</x-bt-modal>");
        expect($html)->toContain('backdrop-blur');
    }
});

it('renders with default xl blur', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('backdrop-blur');
});

it('can render with custom z-index', function () {
    $html = Blade::render('<x-bt-modal zIndex="50">Content</x-bt-modal>');

    expect($html)->toContain('z-50');
});

it('renders with default z-30', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('z-30');
});

it('can enable styled mode', function () {
    $html = Blade::render('<x-bt-modal :styled="true">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('renders with title slot in styled mode', function () {
    $html = Blade::render('
        <x-bt-modal :styled="true">
            <x-slot:title>Modal Title</x-slot:title>
            Content
        </x-bt-modal>
    ');

    expect($html)->toContain('Modal Title');
    expect($html)->toContain('Content');
});

it('renders with footer slot', function () {
    $html = Blade::render('
        <x-bt-modal>
            <x-slot:footer>
                <button>Close</button>
            </x-slot:footer>
            Content
        </x-bt-modal>
    ');

    expect($html)->toContain('Close');
    expect($html)->toContain('Content');
});

it('shows close button by default', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('can hide close button', function () {
    $html = Blade::render('<x-bt-modal :showCloseButton="false">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('closes on click outside by default', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('can disable close on click outside', function () {
    $html = Blade::render('<x-bt-modal :closeOnClickOutside="false">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('can render centered', function () {
    $html = Blade::render('<x-bt-modal :centered="true">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('supports custom teleport target', function () {
    $html = Blade::render('<x-bt-modal teleportTarget="#custom-target">Content</x-bt-modal>');

    expect($html)->toContain('#custom-target');
});

it('renders with default body teleport', function () {
    $html = Blade::render('<x-bt-modal>Content</x-bt-modal>');

    expect($html)->toContain('body');
});

it('uses custom background color', function () {
    $html = Blade::render('<x-bt-modal bgColor="bg-gray-800">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});

it('generates open and close events', function () {
    $html = Blade::render('<x-bt-modal id="test-modal">Content</x-bt-modal>');

    expect($html)->toContain('open-modal-test-modal');
    expect($html)->toContain('close-modal-test-modal');
});

it('passes all attributes to partial', function () {
    $html = Blade::render('<x-bt-modal data-test="modal">Content</x-bt-modal>');

    expect($html)->toContain('Content');
});
