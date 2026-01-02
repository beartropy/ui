<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic dialog component', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('role="dialog"');
    expect($html)->toContain('aria-modal="true"');
});

it('initializes Alpine.js dialog function', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('btDialog');
    expect($html)->toContain('x-data');
});

it('renders with default medium size', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('max-w-md');
});

it('can render with different sizes', function () {
    $sizes = ['sm', 'md', 'lg', 'xl', '2xl'];

    foreach ($sizes as $size) {
        $html = Blade::render("<x-bt-dialog size=\"{$size}\" />");
        expect($html)->toContain('max-w');
    }
});

it('listens to bt-dialog custom event', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-on:bt-dialog.window');
    expect($html)->toContain('openDialog($event.detail)');
});

it('renders backdrop with blur', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('backdrop-blur');
    expect($html)->toContain('bg-black');
});

it('renders close button when applicable', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('@click="close()"');
    expect($html)->toContain('type="button"');
});

it('supports different dialog types', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('typeStyles');
    expect($html)->toContain('info');
    expect($html)->toContain('success');
    expect($html)->toContain('warning');
    expect($html)->toContain('error');
    expect($html)->toContain('confirm');
    expect($html)->toContain('danger');
});

it('renders icons based on type', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('check-circle');
    expect($html)->toContain('x-circle');
    expect($html)->toContain('exclamation-triangle');
    expect($html)->toContain('information-circle');
    expect($html)->toContain('question-mark-circle');
});

it('renders title and description', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-html="title"');
    expect($html)->toContain('x-html="description"');
});

it('renders accept and reject buttons', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('clickAccept');
    expect($html)->toContain('clickReject');
    expect($html)->toContain('accept.label');
    expect($html)->toContain('reject.label');
});

it('handles busy states for buttons', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('acceptBusy');
    expect($html)->toContain('rejectBusy');
    expect($html)->toContain(':disabled');
});

it('renders loading spinners for busy states', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('animate-spin');
    expect($html)->toContain('x-show="acceptBusy"');
    expect($html)->toContain('x-show="rejectBusy');
});

it('supports single button mode', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('isSingleButton');
    expect($html)->toContain('buttonColors');
});

it('handles backdrop click', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('@click="backdropClick()"');
});

it('renders with proper z-index', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('z-[9999]');
});

it('implements focus trap', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-trap');
});

it('renders with transitions', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-transition');
});

it('positions dialog at top third on desktop', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('md:pt-[18vh]');
});

it('renders with rounded corners and shadow', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('rounded');
    expect($html)->toContain('shadow');
});

it('uses x-show for visibility', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-show="isOpen"');
});

it('includes x-cloak for Alpine initialization', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('x-cloak');
});

it('renders danger type with different button color', function () {
    $html = Blade::render('<x-bt-dialog />');

    expect($html)->toContain('type === \'danger\'');
    expect($html)->toContain('bg-rose');
});
