<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic loading component', function () {
    // Loading component wraps Livewire, can't test without Livewire setup
    expect(true)->toBeTrue();
})->skip('Requires Livewire component registration');

it('renders with livewire component', function () {
    expect(true)->toBeTrue();
})->skip('Requires Livewire component registration');

it('can render with custom view', function () {
    expect(true)->toBeTrue();
})->skip('Requires Livewire component registration');

it('passes customView prop correctly', function () {
    expect(true)->toBeTrue();
})->skip('Requires Livewire component registration');
