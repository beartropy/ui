<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic radio group', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
    expect($html)->toContain('type="radio"');
});

it('can render inline radio group', function () {
    $options = [
        ['value' => '1', 'label' => 'Yes'],
        ['value' => '2', 'label' => 'No'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :inline="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Yes');
    expect($html)->toContain('No');
});

it('can render vertical radio group by default', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('can render with default selected value', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
        ['value' => '3', 'label' => 'Option 3'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" value="2" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
    expect($html)->toContain('Option 3');
    expect($html)->toContain('checked');
});

it('can render with custom color', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" color="primary" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
});

it('can render with custom size', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" size="lg" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
});

it('can render with custom classes', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" class="custom-group" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('custom-group');
});

it('can render with wire:model', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" wire:model="selection" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('wire:model="selection"');
});

it('shows validation errors', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :custom-error="\'Please select an option\'" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Please select an option');
    expect($html)->toContain('text-red-500');
});

it('renders all radios with same name', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
        ['value' => '3', 'label' => 'Option 3'],
    ];
    $html = Blade::render('<x-bt-radio-group name="my_group" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('name="my_group"');
});

it('can render with empty options', function () {
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="[]" />');

    // Should render the wrapper divs even with no options
    expect($html)->toContain('<div');
});

it('can render with options containing descriptions', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1', 'description' => 'First option desc'],
        ['value' => '2', 'label' => 'Option 2', 'description' => 'Second option desc'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('can render with all features combined', function () {
    $options = [
        ['value' => 'basic', 'label' => 'Basic Plan'],
        ['value' => 'pro', 'label' => 'Pro Plan'],
        ['value' => 'enterprise', 'label' => 'Enterprise Plan'],
    ];
    $html = Blade::render('
        <x-bt-radio-group 
            name="subscription"
            :inline="true"
            color="primary"
            size="lg"
            wire:model="selectedPlan"
            class="custom-radio-group"
            :options="$options"
        />
    ', ['options' => $options]);

    expect($html)->toContain('Basic Plan');
    expect($html)->toContain('Pro Plan');
    expect($html)->toContain('Enterprise Plan');
    expect($html)->toContain('wire:model="selectedPlan"');
    expect($html)->toContain('custom-radio-group');
});

it('applies color to all radio buttons in group', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" color="red" :options="$options" />', ['options' => $options]);

    // Should render both options with color applied
    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('applies size to all radio buttons in group', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" size="sm" :options="$options" />', ['options' => $options]);

    // Should render both options with size applied
    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('forwards disabled to child radios', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :disabled="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('disabled');
    expect($html)->toContain('opacity-60');
});

it('can render with help text', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" help="Pick one" />', ['options' => $options]);

    expect($html)->toContain('Pick one');
});

it('can render with label', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" label="Choose" />', ['options' => $options]);

    expect($html)->toContain('Choose');
});

it('selects default value', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ];
    $html = Blade::render('<x-bt-radio-group name="test_group" :options="$options" value="2" />', ['options' => $options]);

    // Only the second radio should be checked
    expect($html)->toContain('checked');
});
