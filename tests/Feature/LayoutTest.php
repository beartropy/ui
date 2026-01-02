<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic layout component', function () {
    $html = Blade::render('<x-bt-layout>Content</x-bt-layout>');

    expect($html)->toContain('Content');
});

it('renders with default slot', function () {
    $html = Blade::render('<x-bt-layout>Main Content</x-bt-layout>');

    expect($html)->toContain('Main Content');
});

it('supports sidebar slot', function () {
    $html = Blade::render('
        <x-bt-layout>
            <x-slot:sidebar>
                Sidebar Content
            </x-slot:sidebar>
            Main
        </x-bt-layout>
    ');

    expect($html)->toContain('Sidebar Content');
    expect($html)->toContain('Main');
});

it('supports header slot', function () {
    $html = Blade::render('
        <x-bt-layout>
            <x-slot:header>
                Header Content
            </x-slot:header>
            Main
        </x-bt-layout>
    ');

    expect($html)->toContain('Main');
});

it('supports footer slot', function () {
    $html = Blade::render('
        <x-bt-layout>
            <x-slot:footer>
                Footer Content
            </x-slot:footer>
            Main
        </x-bt-layout>
    ');

    expect($html)->toContain('Main');
});

it('can render with all slots', function () {
    $html = Blade::render('
        <x-bt-layout>
            <x-slot:sidebar>
                Sidebar
            </x-slot:sidebar>
            <x-slot:header>
                Header
            </x-slot:header>
            <x-slot:footer>
                Footer
            </x-slot:footer>
            Main Content
        </x-bt-layout>
    ');

    expect($html)->toContain('Sidebar');
    expect($html)->toContain('Main Content');
});
