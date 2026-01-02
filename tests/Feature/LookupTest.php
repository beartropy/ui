<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic lookup component', function () {
    $options = [
        ['id' => 1, 'name' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('x-data');
    expect($html)->toContain('data-options');
});

it('can render with label', function () {
    $options = [
        ['id' => 1, 'name' => 'Option 1'],
    ];
    $html = Blade::render('<x-bt-lookup label="Search" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Search');
    expect($html)->toContain('<label');
});

it('can render with simple array options', function () {
    $options = ['Apple', 'Banana', 'Orange'];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('data-options');
    expect($html)->toContain('Apple');
});

it('can render with array of objects options', function () {
    $options = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('First');
    expect($html)->toContain('Second');
});

it('can render with custom option label and value keys', function () {
    $options = [
        ['value' => 'ar', 'label' => 'Argentina'],
        ['value' => 'br', 'label' => 'Brasil'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" option-label="label" option-value="value" />', ['options' => $options]);

    expect($html)->toContain('Argentina');
    expect($html)->toContain('Brasil');
});

it('normalizes simple scalar options correctly', function () {
    $options = ['apple', 'banana', 123];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('apple');
    expect($html)->toContain('banana');
    expect($html)->toContain('123');
});

it('normalizes key-value pair options correctly', function () {
    $options = [
        ['ar' => 'Argentina'],
        ['br' => 'Brasil'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Argentina');
    expect($html)->toContain('Brasil');
});

it('renders with Alpine.js data structure', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('open:');
    expect($html)->toContain('highlighted:');
    expect($html)->toContain('filtered:');
});

it('renders autocomplete off', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('autocomplete');
});

it('can render with placeholder', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup placeholder="Search..." :options="$options" />', ['options' => $options]);

    expect($html)->toContain('placeholder');
});

it('can render with disabled state', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :disabled="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('disabled');
});

it('shows validation errors with custom-error', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup custom-error="Selection required" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Selection required');
});

it('can render with hint text', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup hint="Type to search" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Type to search');
});

it('can render with custom id', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup id="custom-lookup-id" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('id="custom-lookup-id"');
});

it('generates unique id when not provided', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html1 = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);
    $html2 = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html1)->not->toBe($html2);
});

it('can render with custom classes', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup class="custom-lookup" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('custom-lookup');
});

it('can render with custom attributes', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup data-test="lookup" aria-label="Search Field" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('data-test="lookup"');
    expect($html)->toContain('aria-label="Search Field"');
});

it('renders dropdown with filtered options', function () {
    $options = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('x-for');
    expect($html)->toContain('filtered');
});

it('renders clearable button', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :clearable="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('clearBoth');
});

it('handles keyboard navigation with Alpine.js', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('keydown.down');
    expect($html)->toContain('keydown.up');
    expect($html)->toContain('keydown.enter');
    expect($html)->toContain('keydown.escape');
});

it('can render with start slot', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('
        <x-bt-lookup :options="$options">
            <x-slot:start>
                <span>🔍</span>
            </x-slot:start>
        </x-bt-lookup>
    ', ['options' => $options]);

    expect($html)->toContain('🔍');
});

it('can render with end slot', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('
        <x-bt-lookup :options="$options">
            <x-slot:end>
                <span>✓</span>
            </x-slot:end>
        </x-bt-lookup>
    ', ['options' => $options]);

    expect($html)->toContain('✓');
});

it('renders with empty options array', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)->toContain('data-options');
    expect($html)->toContain('x-data');
});

it('can render with all features combined', function () {
    $options = [
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'uk', 'name' => 'United Kingdom'],
        ['id' => 'ca', 'name' => 'Canada'],
    ];
    $html = Blade::render('
        <x-bt-lookup 
            label="Select Country"
            placeholder="Search countries..."
            hint="Type to filter the list"
            :options="$options"
            option-label="name"
            option-value="id"
            :clearable="true"
            class="custom-country-lookup"
        />
    ', ['options' => $options]);

    expect($html)->toContain('Select Country');
    expect($html)->toContain('placeholder');
    expect($html)->toContain('Type to filter the list');
    expect($html)->toContain('United States');
    expect($html)->toContain('United Kingdom');
    expect($html)->toContain('Canada');
    expect($html)->toContain('custom-country-lookup');
});

it('extends Input component functionality', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    // Should have input-base component
    expect($html)->toContain('x-ref="input"');
});

it('renders listbox ARIA role', function () {
    $options = [['id' => 1, 'name' => 'Test']];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('role="listbox"');
});

it('renders option ARIA roles', function () {
    $options = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)->toContain('role="option"');
});
