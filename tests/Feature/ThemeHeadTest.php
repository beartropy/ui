<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
});

it('renders an inline script tag', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain('<script>');
    expect($html)->toContain('</script>');
});

it('reads localStorage theme', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain("localStorage.getItem('theme')");
});

it('checks prefers-color-scheme media query', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain('prefers-color-scheme:dark');
});

it('toggles dark class on document element', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain("classList.toggle('dark'");
});

it('sets colorScheme style', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain('colorScheme');
});

it('registers livewire:navigated listener', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain('livewire:navigated');
});

it('sets __btThemeNavigated flag to prevent duplicate listeners', function () {
    $html = Blade::render('<x-beartropy-ui::theme-head />');

    expect($html)->toContain('__btThemeNavigated');
});
