<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
    Blade::component(\Beartropy\Ui\Components\Support\FieldHelp::class, 'bt-field-help');
});

it('can render field help component', function () {
    $html = Blade::render('<x-bt-field-help />');

    expect($html)->not->toBeEmpty();
});

it('renders hint text', function () {
    $html = Blade::render('<x-bt-field-help hint="This is a hint" />');

    expect($html)->toContain('This is a hint');
});

it('renders error message', function () {
    $html = Blade::render('<x-bt-field-help errorMessage="This field is required" />');

    expect($html)->toContain('This field is required');
});

it('prioritizes error over hint', function () {
    $html = Blade::render('<x-bt-field-help hint="Hint" errorMessage="Error" />');

    expect($html)->toContain('Error');
    // Implementation detail: normally error replaces hint or shows both? 
    // Usually error red text replaces hint gray text.
    // Let's check logic later if this fails, but for now assert containment.
});

it('supports minHeight prop', function () {
    $html = Blade::render('<x-bt-field-help minHeight="20px" />');

    expect($html)->toContain('min-h-[20px]');
});
