<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders default structure with Alpine state and teleport', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('x-data=')
        ->toContain('show: false')
        ->toContain('ready: false')
        ->toContain('x-teleport="body"')
        ->toContain('class="inline-block"');
});

it('renders label inside the teleported tooltip panel', function () {
    $html = Blade::render('<x-bt-tooltip label="My tooltip text">Trigger</x-bt-tooltip>');

    expect($html)->toContain('My tooltip text');
});

it('renders slot content inside the trigger wrapper', function () {
    $html = Blade::render('<x-bt-tooltip label="Help"><button>Click me</button></x-bt-tooltip>');

    expect($html)
        ->toContain('<button>Click me</button>')
        ->toContain('x-ref="trigger"');
});

it('uses right position by default', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    // Default position is 'right' — the switch statement uses 'right'
    expect($html)->toContain("case 'right':");
});

it('renders with top position', function () {
    $html = Blade::render('<x-bt-tooltip label="Help" position="top">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain("switch ('top')")
        ->toContain("translateX(-50%) translateY(-100%)");
});

it('renders with bottom position', function () {
    $html = Blade::render('<x-bt-tooltip label="Help" position="bottom">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain("switch ('bottom')")
        ->toContain("translateX(-50%)");
});

it('renders with left position', function () {
    $html = Blade::render('<x-bt-tooltip label="Help" position="left">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain("switch ('left')")
        ->toContain("translateX(-100%) translateY(-50%)");
});

it('renders with right position explicitly', function () {
    $html = Blade::render('<x-bt-tooltip label="Help" position="right">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain("switch ('right')")
        ->toContain("translateY(-50%)");
});

it('renders default delay of 0', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)->toContain('}, 0)');
});

it('renders custom delay value', function () {
    $html = Blade::render('<x-bt-tooltip label="Help" :delay="500">Trigger</x-bt-tooltip>');

    expect($html)->toContain('}, 500)');
});

it('includes hover and scroll event handlers', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('@mouseenter="showTooltip()"')
        ->toContain('@mouseleave="hideTooltip()"')
        ->toContain('@scroll.window="hideTooltip()"');
});

it('includes transition classes on the tooltip panel', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('x-transition:enter="transition ease-out duration-300"')
        ->toContain('x-transition:enter-start="opacity-0 scale-95"')
        ->toContain('x-transition:enter-end="opacity-100 scale-100"')
        ->toContain('x-transition:leave="transition ease-in duration-200"')
        ->toContain('x-transition:leave-start="opacity-100 scale-100"')
        ->toContain('x-transition:leave-end="opacity-0 scale-95"');
});

it('applies tooltip styling classes', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('text-white')
        ->toContain('bg-black/80')
        ->toContain('z-[9999]')
        ->toContain('rounded')
        ->toContain('shadow-lg')
        ->toContain('backdrop-blur-sm');
});

it('includes dark mode styles', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('dark:text-slate-800')
        ->toContain('dark:bg-white/90');
});

it('uses x-show with show and ready state', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)
        ->toContain('x-show="show && ready"')
        ->toContain('x-cloak');
});

it('renders trigger with cursor-help class', function () {
    $html = Blade::render('<x-bt-tooltip label="Help">Trigger</x-bt-tooltip>');

    expect($html)->toContain('cursor-help');
});
