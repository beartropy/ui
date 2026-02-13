<?php

use Beartropy\Ui\Components\Lookup;
use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders with beartropyLookup Alpine module', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)
        ->toContain('x-data="beartropyLookup(')
        ->toContain('data-options');
});

it('does not contain inline Alpine state in root x-data', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    // The root x-data should use the module, not inline state
    expect($html)
        ->toContain('x-data="beartropyLookup(')
        ->not->toContain('open: false,')
        ->not->toContain('highlighted: -1,');
});

it('auto-generates id with beartropy-lookup prefix', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)->toMatch('/id="beartropy-lookup-[a-f0-9]+"/');
});

it('uses custom id when provided', function () {
    $html = Blade::render('<x-bt-lookup id="my-lookup" :options="[]" />');

    expect($html)->toContain('id="my-lookup"');
});

it('generates unique ids for multiple instances', function () {
    $html1 = Blade::render('<x-bt-lookup :options="[]" />');
    $html2 = Blade::render('<x-bt-lookup :options="[]" />');

    preg_match('/id="(beartropy-lookup-[a-f0-9]+)"/', $html1, $m1);
    preg_match('/id="(beartropy-lookup-[a-f0-9]+)"/', $html2, $m2);

    expect($m1[1])->not->toBe($m2[1]);
});

it('defaults name to id when not provided', function () {
    $component = new Lookup(id: 'test-id');

    expect($component->name)->toBe('test-id');
});

it('uses explicit name when provided', function () {
    $component = new Lookup(id: 'test-id', name: 'custom-name');

    expect($component->name)->toBe('custom-name');
});

it('renders label with for attribute', function () {
    $html = Blade::render('<x-bt-lookup id="my-lookup" label="Country" :options="[]" />');

    expect($html)
        ->toContain('<label')
        ->toContain('for="my-lookup"')
        ->toContain('Country');
});

it('omits label element when not provided', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)->not->toContain('<label');
});

it('renders placeholder on input', function () {
    $html = Blade::render('<x-bt-lookup placeholder="Search countries..." :options="[]" />');

    expect($html)->toContain('placeholder="Search countries..."');
});

it('renders options in data-options attribute', function () {
    $options = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)
        ->toContain('data-options=')
        ->toContain('First')
        ->toContain('Second');
});

it('normalizes simple scalar array options', function () {
    $options = ['Apple', 'Banana', 123];
    $html = Blade::render('<x-bt-lookup :options="$options" />', ['options' => $options]);

    expect($html)
        ->toContain('Apple')
        ->toContain('Banana')
        ->toContain('123');
});

it('normalizes object array options', function () {
    $options = [
        ['id' => 1, 'name' => 'First'],
        ['id' => 2, 'name' => 'Second'],
    ];
    $component = new Lookup(options: $options);

    expect($component->options)->toBe([
        ['id' => '1', 'name' => 'First'],
        ['id' => '2', 'name' => 'Second'],
    ]);
});

it('normalizes key-value pair options', function () {
    $options = [
        ['ar' => 'Argentina'],
        ['br' => 'Brasil'],
    ];
    $component = new Lookup(options: $options);

    expect($component->options)->toBe([
        ['id' => 'ar', 'name' => 'Argentina'],
        ['id' => 'br', 'name' => 'Brasil'],
    ]);
});

it('normalizes with custom optionLabel and optionValue', function () {
    $options = [
        ['code' => 'ar', 'title' => 'Argentina'],
        ['code' => 'br', 'title' => 'Brasil'],
    ];
    $component = new Lookup(options: $options, optionLabel: 'title', optionValue: 'code');

    expect($component->options)->toBe([
        ['code' => 'ar', 'title' => 'Argentina'],
        ['code' => 'br', 'title' => 'Brasil'],
    ]);
});

it('forwards custom optionLabel and optionValue to Alpine config', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" option-label="title" option-value="code" />');

    expect($html)
        ->toContain("labelKey: 'title'")
        ->toContain("valueKey: 'code'");
});

it('renders disabled state', function () {
    $html = Blade::render('<x-bt-lookup :disabled="true" :options="[]" />');

    expect($html)->toContain('disabled');
});

it('renders readonly state', function () {
    $html = Blade::render('<x-bt-lookup :readonly="true" :options="[]" />');

    expect($html)->toContain('readonly');
});

it('renders clearable button with clearBoth', function () {
    $html = Blade::render('<x-bt-lookup :clearable="true" :options="[]" />');

    expect($html)->toContain('clearBoth()');
});

it('omits clear button when clearable is false', function () {
    $html = Blade::render('<x-bt-lookup :clearable="false" :options="[]" />');

    expect($html)->not->toContain('clearBoth');
});

it('renders icon-start slot', function () {
    $html = Blade::render('<x-bt-lookup icon-start="magnifying-glass" :options="[]" />');

    // Icon renders as SVG; verify the start slot wrapper is present
    expect($html)->toContain('beartropy-inputbase-start-slot');
});

it('renders icon-end slot', function () {
    $html = Blade::render('<x-bt-lookup icon-end="chevron-down" :options="[]" />');

    // Icon renders as SVG; verify the end slot wrapper is present
    expect($html)->toContain('beartropy-inputbase-end-slot');
});

it('renders help text via field-help', function () {
    $html = Blade::render('<x-bt-lookup help="Select your country" :options="[]" />');

    expect($html)->toContain('Select your country');
});

it('renders hint text via field-help', function () {
    $html = Blade::render('<x-bt-lookup hint="Type to search" :options="[]" />');

    expect($html)->toContain('Type to search');
});

it('renders custom error via field-help', function () {
    $html = Blade::render('<x-bt-lookup custom-error="Selection required" :options="[]" />');

    expect($html)->toContain('Selection required');
});

it('applies error label class when error is present', function () {
    $html = Blade::render('<x-bt-lookup label="Country" custom-error="Required" :options="[]" />');

    // The label should exist and the error-related styles should be applied
    expect($html)
        ->toContain('<label')
        ->toContain('Country')
        ->toContain('Required');
});

it('renders keyboard navigation handlers', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)
        ->toContain('keydown.down')
        ->toContain('keydown.up')
        ->toContain('keydown.enter')
        ->toContain('keydown.escape');
});

it('renders start slot content', function () {
    $html = Blade::render('
        <x-bt-lookup :options="[]">
            <x-slot:start>
                <span class="custom-start">Start</span>
            </x-slot:start>
        </x-bt-lookup>
    ');

    expect($html)->toContain('custom-start');
});

it('renders end slot content', function () {
    $html = Blade::render('
        <x-bt-lookup :options="[]">
            <x-slot:end>
                <span class="custom-end">End</span>
            </x-slot:end>
        </x-bt-lookup>
    ');

    expect($html)->toContain('custom-end');
});

it('renders dropdown with listbox and option ARIA roles', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)
        ->toContain('role="listbox"')
        ->toContain('role="option"');
});

it('applies custom classes to wrapper', function () {
    $html = Blade::render('<x-bt-lookup class="custom-lookup mt-4" :options="[]" />');

    expect($html)->toContain('custom-lookup mt-4');
});

it('forwards extra attributes', function () {
    $html = Blade::render('<x-bt-lookup data-testid="country-lookup" :options="[]" />');

    expect($html)->toContain('data-testid="country-lookup"');
});

it('renders with empty options array', function () {
    $html = Blade::render('<x-bt-lookup :options="[]" />');

    expect($html)
        ->toContain("data-options='[]'")
        ->toContain('beartropyLookup(');
});

it('renders clear button aria-label with translation', function () {
    $html = Blade::render('<x-bt-lookup :clearable="true" :options="[]" />');

    expect($html)->toContain('aria-label="Clear"');
});

it('renders combined features correctly', function () {
    $options = [
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'uk', 'name' => 'United Kingdom'],
        ['id' => 'ca', 'name' => 'Canada'],
    ];
    $html = Blade::render('
        <x-bt-lookup
            id="country-lookup"
            label="Select Country"
            placeholder="Search countries..."
            hint="Type to filter the list"
            :options="$options"
            :clearable="true"
            class="custom-country-lookup"
        />
    ', ['options' => $options]);

    expect($html)
        ->toContain('id="country-lookup"')
        ->toContain('Select Country')
        ->toContain('placeholder="Search countries..."')
        ->toContain('Type to filter the list')
        ->toContain('United States')
        ->toContain('custom-country-lookup')
        ->toContain('beartropyLookup(')
        ->toContain('clearBoth()');
});

it('extends BeartropyComponent not Input', function () {
    $component = new Lookup();

    expect($component)->toBeInstanceOf(\Beartropy\Ui\Components\BeartropyComponent::class)
        ->and($component)->not->toBeInstanceOf(\Beartropy\Ui\Components\Input::class);
});
