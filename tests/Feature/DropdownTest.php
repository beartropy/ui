<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ── Portal mode (default) ──────────────────────────────────────────

it('renders portal mode by default with teleport and Alpine state', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)
        ->toContain('x-teleport="body"')
        ->toContain('open: false')
        ->toContain('role="menu"')
        ->toContain('Content');
});

it('renders trigger slot inside clickable wrapper with aria attributes', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-slot:trigger>
                <button>Open Menu</button>
            </x-slot:trigger>
            Items
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('Open Menu')
        ->toContain('x-ref="trigger"')
        ->toContain('aria-haspopup="true"')
        ->toContain(':aria-expanded="open"')
        ->toContain('@click="open = !open"');
});

it('renders default slot body inside py-1 wrapper', function () {
    $html = Blade::render('<x-bt-dropdown>Dropdown Body</x-bt-dropdown>');

    expect($html)
        ->toContain('Dropdown Body')
        ->toContain('py-1');
});

it('closes on escape key and click away', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)
        ->toContain('@keydown.escape.window="open = false"')
        ->toContain('@click.away="open = false"')
        ->toContain('@bt-dropdown-close.window="open = false"');
});

it('renders transitions in portal mode', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)
        ->toContain('x-transition:enter="transition ease-out duration-150"')
        ->toContain('x-transition:enter-start="opacity-0 scale-95"')
        ->toContain('x-transition:enter-end="opacity-100 scale-100"')
        ->toContain('x-transition:leave="transition ease-in duration-100"')
        ->toContain('x-transition:leave-start="opacity-100 scale-100"')
        ->toContain('x-transition:leave-end="opacity-0 scale-95"');
});

it('renders color preset classes in portal mode', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)
        ->toContain('bg-white dark:bg-gray-900')
        ->toContain('shadow-lg');
});

// ── Portal config ──────────────────────────────────────────────────

it('injects portal config into Alpine x-data', function () {
    $html = Blade::render('
        <x-bt-dropdown
            :autoFit="true"
            :autoFlip="false"
            :maxHeight="300"
            :flipAt="120"
            :minPanel="200"
            zIndex="z-50"
            overflowMode="scroll"
        >Content</x-bt-dropdown>
    ');

    expect($html)
        ->toContain('autoFit: true')
        ->toContain('autoFlip: false')
        ->toContain('maxHeight: 300')
        ->toContain('flipAt: 120')
        ->toContain('minPanel: 200')
        ->toContain("zIndex: 'z-50'")
        ->toContain("overflowMode: 'scroll'");
});

it('sets allowOverflow true when maxHeight is provided', function () {
    $html = Blade::render('<x-bt-dropdown :maxHeight="400">Content</x-bt-dropdown>');

    expect($html)->toContain('allowOverflow: true');
});

it('sets allowOverflow false when maxHeight is null', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)->toContain('allowOverflow: false');
});

it('defaults zIndex to z-[99999999]', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)->toContain("zIndex: 'z-[99999999]'");
});

// ── Classic mode ───────────────────────────────────────────────────

it('renders classic mode with dropdown-base when usePortal is false', function () {
    $html = Blade::render('<x-bt-dropdown :usePortal="false">Classic</x-bt-dropdown>');

    // Portal mode has inline _computeLeft / _reposition; classic mode does not
    expect($html)
        ->not->toContain('_computeLeft')
        ->toContain('Classic');
});

// ── Placement ──────────────────────────────────────────────────────

it('embeds placement in _computeLeft logic for center', function () {
    $html = Blade::render('<x-bt-dropdown placement="center">Content</x-bt-dropdown>');

    expect($html)->toContain("'center'");
});

it('embeds placement in _computeLeft logic for right', function () {
    $html = Blade::render('<x-bt-dropdown placement="right">Content</x-bt-dropdown>');

    expect($html)->toContain("'right'");
});

// ── Side ───────────────────────────────────────────────────────────

it('sets sideLocal to top when side is top', function () {
    $html = Blade::render('<x-bt-dropdown side="top">Content</x-bt-dropdown>');

    expect($html)->toContain("sideLocal: 'top'");
});

it('sets sideLocal to bottom by default', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    expect($html)->toContain("sideLocal: 'bottom'");
});

// ── Width ──────────────────────────────────────────────────────────

it('uses custom width class when provided', function () {
    $html = Blade::render('<x-bt-dropdown width="w-64">Content</x-bt-dropdown>');

    expect($html)->toContain("widthClass: 'w-64'");
});

it('uses preset default width when width is null', function () {
    $html = Blade::render('<x-bt-dropdown>Content</x-bt-dropdown>');

    // Width comes from preset's dropdownWidth or fallback
    preg_match("/widthClass: '([^']+)'/", $html, $m);
    expect($m)->not->toBeEmpty();
    expect($m[1])->not->toBeEmpty();
});

// ── Dropdown Item (as link) ────────────────────────────────────────

it('renders item as link by default with role menuitem', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item href="/settings">Settings</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('<a')
        ->toContain('role="menuitem"')
        ->toContain('Settings');
});

it('renders item with icon', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item icon="heroicon-o-cog-6-tooth" href="/settings">Settings</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('w-4 h-4 mr-2')
        ->toContain('Settings');
});

it('dispatches close event on item click by default', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item href="/test">Test</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)->toContain("bt-dropdown-close");
});

it('omits close dispatch when closeOnClick is false', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item href="/test" :closeOnClick="false">Test</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    // The item should NOT contain the dispatch
    preg_match_all('/<a[^>]*>/', $html, $anchors);
    $itemAnchor = collect($anchors[0])->first(fn ($a) => str_contains($a, 'menuitem'));
    expect($itemAnchor)->not->toContain('bt-dropdown-close');
});

// ── Dropdown Item (as button) ──────────────────────────────────────

it('renders item as button when as=button', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item as="button">Delete</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('role="menuitem"')
        ->toContain('Delete');
});

// ── Dropdown Item disabled ─────────────────────────────────────────

it('renders disabled item with opacity and aria-disabled', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item href="/test" :disabled="true">Disabled</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('aria-disabled=true')
        ->toContain('opacity-50')
        ->toContain('cursor-not-allowed');
});

it('renders disabled button item with disabled attribute', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item as="button" :disabled="true">Disabled</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('disabled')
        ->toContain('opacity-50');
});

// ── Dropdown Header ────────────────────────────────────────────────

it('renders header partial with role presentation', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.header>Section Title</x-bt-dropdown.header>
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('role="presentation"')
        ->toContain('uppercase')
        ->toContain('Section Title');
});

// ── Dropdown Separator ─────────────────────────────────────────────

it('renders separator as hr with border classes', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.separator />
        </x-bt-dropdown>
    ');

    expect($html)
        ->toContain('<hr')
        ->toContain('border-gray-200 dark:border-gray-700');
});

// ── wire:navigate ──────────────────────────────────────────────────

it('adds wire:navigate on items when withnavigate is true', function () {
    $html = Blade::render('
        <x-bt-dropdown :withnavigate="true">
            <x-bt-dropdown.item href="/page">Link</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)->toContain('wire:navigate');
});

it('does not add wire:navigate by default', function () {
    $html = Blade::render('
        <x-bt-dropdown>
            <x-bt-dropdown.item href="/page">Link</x-bt-dropdown.item>
        </x-bt-dropdown>
    ');

    expect($html)->not->toContain('wire:navigate');
});

// ── Custom ID ──────────────────────────────────────────────────────

it('uses custom id when provided', function () {
    $html = Blade::render('<x-bt-dropdown id="my-dd">Content</x-bt-dropdown>');

    expect($html)->toContain("x-id=\"['dropdown-my-dd']\"");
});

it('generates unique dropdown id when none given', function () {
    $html1 = Blade::render('<x-bt-dropdown>A</x-bt-dropdown>');
    $html2 = Blade::render('<x-bt-dropdown>B</x-bt-dropdown>');

    preg_match("/x-id=\"\\['dropdown-([^']+)'\\]\"/", $html1, $m1);
    preg_match("/x-id=\"\\['dropdown-([^']+)'\\]\"/", $html2, $m2);

    expect($m1[1])->not->toBe($m2[1]);
});
