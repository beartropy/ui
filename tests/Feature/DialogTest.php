<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders the default dialog container', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('btDialog(')
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('x-cloak')
        ->toContain('x-show="isOpen"');
});

it('passes the size prop to Alpine globalSize', function () {
    $html = Blade::render('<x-bt-dialog size="lg" />');

    expect($html)->toContain("globalSize: 'lg'");
});

it('defaults globalSize to md when no size prop given', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain("globalSize: 'md'");
});

it('passes all six type styles as JSON to Alpine', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('typeStyles:')
        ->toContain('info')
        ->toContain('success')
        ->toContain('warning')
        ->toContain('error')
        ->toContain('confirm')
        ->toContain('danger');
});

it('renders the backdrop with blur and click handler', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('backdrop-blur-sm')
        ->toContain('bg-black/40')
        ->toContain('backdropClick()');
});

it('renders the close button with SVG', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('x-show="canCloseViaButton"')
        ->toContain('@click="close()"')
        ->toContain('<svg')
        ->toContain('M6 18L18 6M6 6l12 12');
});

it('enables focus trap with noscroll and inert', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-trap.noscroll.inert="isOpen"');
});

it('renders icon SVG paths for all five icon types', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('icon === \'check-circle\'')
        ->toContain('icon === \'x-circle\'')
        ->toContain('icon === \'exclamation-triangle\'')
        ->toContain('icon === \'information-circle\'')
        ->toContain('icon === \'question-mark-circle\'');
});

it('renders title and description with x-html bindings', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('x-html="title"')
        ->toContain('x-html="description"');
});

it('renders the accept button with busy state and spinner', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('clickAccept()')
        ->toContain(':disabled="acceptBusy"')
        ->toContain('x-show="acceptBusy"')
        ->toContain('animate-spin');
});

it('renders the reject button with translated cancel label and spinner', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('clickReject()')
        ->toContain(':disabled="rejectBusy"')
        ->toContain('x-show="rejectBusy && reject.method"')
        ->toContain(__('beartropy-ui::ui.cancel'));
});

it('renders single button mode with translated OK text', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('x-if="isSingleButton"')
        ->toContain('buttonColors[type]')
        ->toContain(__('beartropy-ui::ui.ok'));
});

it('renders accept button fallback label as translated OK', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain("accept.label ?? '" . __('beartropy-ui::ui.ok') . "'");
});

it('applies danger styling conditionally on the accept button', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain("type === 'danger'")
        ->toContain('bg-rose-700 hover:bg-rose-600')
        ->toContain('bg-beartropy-700 hover:bg-beartropy-600');
});

it('listens to the bt-dialog.window event', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-on:bt-dialog.window="openDialog($event.detail)"');
});

it('renders with z-[9999] stacking order', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('z-[9999]');
});

it('applies desktop top offset', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('md:pt-[18vh]');
});

it('renders panel with rounded corners, shadow, and transitions', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)
        ->toContain('rounded-2xl')
        ->toContain('shadow-2xl')
        ->toContain('x-transition');
});

it('applies panel size class via Alpine binding', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain(':class="panelSizeClass"');
});
