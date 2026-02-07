<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);

    Blade::component(\Beartropy\Ui\Components\Base\BadgeBase::class, 'bt-badge-base');
    Blade::component(\Beartropy\Ui\Components\Base\ButtonBase::class, 'bt-button-base');
    Blade::component(\Beartropy\Ui\Components\Base\CheckboxBase::class, 'bt-checkbox-base');
    Blade::component(\Beartropy\Ui\Components\Base\InputBase::class, 'bt-input-base');
    Blade::component(\Beartropy\Ui\Components\Base\SelectBase::class, 'bt-select-base');
});

it('can render badge base', function () {
    $html = Blade::render('<x-bt-badge-base>Content</x-bt-badge-base>');
    expect($html)->toContain('Content');
});

it('can render button base', function () {
    $html = Blade::render('<x-bt-button-base>Button</x-bt-button-base>');
    expect($html)->toContain('Button');
});

it('can render checkbox base', function () {
    $html = Blade::render('<x-bt-checkbox-base />');
    expect($html)->toContain('input');
});

it('can render input base', function () {
    $html = Blade::render('<x-bt-input-base />');
    expect($html)->toContain('input');
});

it('can render select base', function () {
    $html = Blade::render('<x-bt-select-base />');
    expect($html)->toContain('select');
});
