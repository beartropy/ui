<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic avatar component', function () {
    $html = Blade::render('<x-bt-avatar />');

    expect($html)->toContain('rounded-full');
});

it('renders default SVG when no image or initials', function () {
    $html = Blade::render('<x-bt-avatar />');

    expect($html)->toContain('<svg');
    expect($html)->toContain('<circle');
});

it('can render with image source', function () {
    $html = Blade::render('<x-bt-avatar src="/avatar.jpg" />');

    expect($html)->toContain('<img');
    expect($html)->toContain('src="/avatar.jpg"');
    expect($html)->toContain('object-cover');
});

it('can render with alt text', function () {
    $html = Blade::render('<x-bt-avatar src="/avatar.jpg" alt="User Avatar" />');

    expect($html)->toContain('alt="User Avatar"');
});

it('can render with initials', function () {
    $html = Blade::render('<x-bt-avatar initials="JD" />');

    expect($html)->toContain('JD');
});

it('prefers image over initials', function () {
    $html = Blade::render('<x-bt-avatar src="/avatar.jpg" initials="JD" />');

    expect($html)->toContain('<img');
    expect($html)->not->toContain('JD');
});

it('prefers initials over default SVG', function () {
    $html = Blade::render('<x-bt-avatar initials="AB" />');

    expect($html)->toContain('AB');
    expect($html)->not->toContain('<svg');
});

it('can render with custom slot content', function () {
    $html = Blade::render('<x-bt-avatar>Custom</x-bt-avatar>');

    expect($html)->toContain('Custom');
});

it('renders with inline-flex', function () {
    $html = Blade::render('<x-bt-avatar />');

    expect($html)->toContain('inline-flex');
    expect($html)->toContain('items-center');
    expect($html)->toContain('justify-center');
});

it('renders with relative positioning for status', function () {
    $html = Blade::render('<x-bt-avatar />');

    expect($html)->toContain('relative');
});

it('supports status slot', function () {
    $html = Blade::render('
        <x-bt-avatar>
            <x-slot:status>
                <span class="status-indicator"></span>
            </x-slot:status>
        </x-bt-avatar>
    ');

    expect($html)->toContain('status-indicator');
    expect($html)->toContain('absolute');
    expect($html)->toContain('bottom-0');
    expect($html)->toContain('right-0');
});

it('supports different sizes', function () {
    $sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-avatar size=\"{$size}\" />");
        expect($html)->toContain('rounded-full');
    }
});

it('supports different colors', function () {
    $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];

    foreach ($colors as $color) {
        $html = Blade::render("<x-bt-avatar color=\"{$color}\" />");
        expect($html)->toContain('rounded-full');
    }
});

it('can render with custom size class', function () {
    $html = Blade::render('<x-bt-avatar customSize="h-20 w-20" />');

    expect($html)->toContain('h-20 w-20');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-avatar 
            src="/user.jpg"
            alt="John Doe"
            size="lg"
            color="primary"
        >
            <x-slot:status>
                <span class="online"></span>
            </x-slot:status>
        </x-bt-avatar>
    ');

    expect($html)->toContain('src="/user.jpg"');
    expect($html)->toContain('alt="John Doe"');
    expect($html)->toContain('online');
});
