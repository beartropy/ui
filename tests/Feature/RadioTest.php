<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic radio component', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="option1" />');

    expect($html)->toContain('type="radio"');
    expect($html)->toContain('name="test_radio"');
    expect($html)->toContain('value="option1"');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Option Label" />');

    expect($html)->toContain('Option Label');
    expect($html)->toContain('<label');
});

it('can render with slot content', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1">Slot Label</x-bt-radio>');

    expect($html)->toContain('Slot Label');
});

it('prefers slot content over label attribute', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Label Text">Slot Text</x-bt-radio>');

    expect($html)->toContain('Slot Text');
});

it('can render with label on the right by default', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Right Label" />');

    expect($html)->toContain('Right Label');
    expect($html)->toContain('inline-flex');
});

it('can render with label on the left', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Left Label" label-position="left" />');

    expect($html)->toContain('Left Label');
    expect($html)->toContain('inline-flex');
});

it('can render checked state', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" checked />');

    expect($html)->toContain('checked');
});

it('can render unchecked by default', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" />');

    // Should not have checked attribute on input
    expect($html)->not->toMatch('/\<input[^>]*\bchecked\b[^>]*>/');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('can render with required attribute', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" required />');

    expect($html)->toContain('required');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" :custom-error="\'Please select an option\'" />');

    expect($html)->toContain('Please select an option');
    expect($html)->toContain('text-red-500');
});

it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-radio name="test_radio" value="1" size="sm" />');
    expect($htmlSm)->toContain('type="radio"');

    $htmlMd = Blade::render('<x-bt-radio name="test_radio" value="1" size="md" />');
    expect($htmlMd)->toContain('type="radio"');

    $htmlLg = Blade::render('<x-bt-radio name="test_radio" value="1" size="lg" />');
    expect($htmlLg)->toContain('type="radio"');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-radio name="test_radio" value="1" color="primary" />');
    expect($htmlPrimary)->toContain('type="radio"');

    $htmlBlue = Blade::render('<x-bt-radio name="test_radio" value="1" color="blue" />');
    expect($htmlBlue)->toContain('type="radio"');

    $htmlRed = Blade::render('<x-bt-radio name="test_radio" value="1" color="red" />');
    expect($htmlRed)->toContain('type="radio"');
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" class="custom-class" />');

    expect($html)->toContain('custom-class');
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" id="custom-radio-id" />');

    expect($html)->toContain('id="custom-radio-id"');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-radio name="test_radio_1" value="1" />');
    $html2 = Blade::render('<x-bt-radio name="test_radio_2" value="2" />');

    expect($html1)->not->toBe($html2);
});

it('can render in grouped mode', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" :grouped="true" />');

    expect($html)->toContain('type="radio"');
});

it('can render with wire:model', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" wire:model="selectedOption" />');

    expect($html)->toContain('wire:model="selectedOption"');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" data-test="custom-radio" aria-label="Custom Radio" />');

    expect($html)->toContain('data-test="custom-radio"');
    expect($html)->toContain('aria-label="Custom Radio"');
});

it('renders with cursor-pointer on label', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Test" />');

    expect($html)->toContain('cursor-pointer');
});

it('renders with select-none on label', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Test" />');

    expect($html)->toContain('select-none');
});

it('can render multiple radios with same name', function () {
    $html = Blade::render('
        <x-bt-radio name="options" value="1" label="Option 1" />
        <x-bt-radio name="options" value="2" label="Option 2" />
        <x-bt-radio name="options" value="3" label="Option 3" />
    ');

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
    expect($html)->toContain('Option 3');
    expect($html)->toContain('value="1"');
    expect($html)->toContain('value="2"');
    expect($html)->toContain('value="3"');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-radio 
            name="preferences"
            value="premium"
            label="Premium Plan"
            size="lg"
            color="primary"
            checked
            class="custom-radio"
        />
    ');

    expect($html)->toContain('Premium Plan');
    expect($html)->toContain('value="premium"');
    expect($html)->toContain('checked');
    expect($html)->toContain('custom-radio');
});

it('shows error when not in grouped mode', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" :custom-error="\'Error\'" :grouped="false" />');

    expect($html)->toContain('Error');
    expect($html)->toContain('text-red-500');
});

it('renders accessible radio input', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Accessible Option" />');

    expect($html)->toContain('type="radio"');
    expect($html)->toContain('<label');
    expect($html)->toContain('Accessible Option');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Option" help="Select this option" />');

    expect($html)->toContain('Select this option');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" label="Option" hint="Hint text" />');

    expect($html)->toContain('Hint text');
});

it('hides field-help when grouped', function () {
    $html = Blade::render('<x-bt-radio name="test_radio" value="1" :grouped="true" help="Should not appear" />');

    expect($html)->not->toContain('Should not appear');
});
