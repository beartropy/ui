<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic toast container', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('$store.toasts');
});

it('initializes Alpine.js toast store', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('Alpine.store(\'toasts\'');
    expect($html)->toContain('items:');
    expect($html)->toContain('add');
    expect($html)->toContain('remove');
});

it('renders different position configurations', function () {
    $html = Blade::render('<x-bt-toast position="top-right" />');

    expect($html)->toContain('top-right');
    expect($html)->toContain('positions:');
});

it('supports all toast positions', function () {
    $positions = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];

    foreach ($positions as $position) {
        $html = Blade::render("<x-bt-toast position=\"{$position}\" />");
        expect($html)->toContain($position);
    }
});

it('renders mobile and desktop layouts', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('md:hidden'); // Mobile
    expect($html)->toContain('hidden md:flex'); // Desktop
});

it('handles toast types with icons', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('toast.type === \'success\'');
    expect($html)->toContain('toast.type === \'error\'');
    expect($html)->toContain('toast.type === \'warning\'');
    expect($html)->toContain('toast.type === \'info\'');
});

it('renders toast dismiss button', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('@click="show');
    expect($html)->toContain('Alpine.store(\'toasts\').remove');
    expect($html)->toContain('aria-label');
});

it('includes progress bar functionality', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('x-ref="progress"');
    expect($html)->toContain('toast.duration');
});

it('handles pause and resume on hover', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('@mouseenter="pause"');
    expect($html)->toContain('@mouseleave="resume"');
});

it('supports custom bottom offset', function () {
    $html = Blade::render('<x-bt-toast bottomOffset="4rem" />');

    expect($html)->toContain('4rem');
    expect($html)->toContain('--bt-prop-bottom-offset');
});

it('listens to Livewire toast events', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('beartropy-add-toast');
    expect($html)->toContain('Livewire.on');
});

it('auto-detects bottom bar', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('measureBottomBar');
    expect($html)->toContain('[data-bottom-bar]');
});

it('renders with transitions', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('x-transition');
    expect($html)->toContain('opacity');
    expect($html)->toContain('scale');
});

it('uses safe area insets for mobile', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('env(safe-area-inset-bottom)');
});

it('renders toast title and message', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('toast.title');
    expect($html)->toContain('toast.message');
});

it('supports single line toasts', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('toast.single');
});

it('generates unique toast IDs', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('toast.id');
    expect($html)->toContain('randomUUID');
});

it('handles sticky toasts with zero duration', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('isSticky');
    expect($html)->toContain('toast.duration <= 0');
});

it('renders progress bar with color based on type', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('bg-blue');
    expect($html)->toContain('bg-green');
    expect($html)->toContain('bg-red');
    expect($html)->toContain('bg-yellow');
});

it('groups toasts by position', function () {
    $html = Blade::render('<x-bt-toast />');

    expect($html)->toContain('grouped()');
    expect($html)->toContain('toast.position');
});
