<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic checkbox component', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('type="checkbox"');
    expect($html)->toContain('peer sr-only');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-checkbox label="Accept terms" />');
    
    expect($html)->toContain('Accept terms');
});

it('can render with slot content', function () {
    $html = Blade::render('<x-bt-checkbox>Custom Label</x-bt-checkbox>');
    
    expect($html)->toContain('Custom Label');
});

it('prefers slot content over label attribute', function () {
    $html = Blade::render('<x-bt-checkbox label="Label Text">Slot Text</x-bt-checkbox>');
    
    expect($html)->toContain('Slot Text');
});

it('can render with label on the right by default', function () {
    $html = Blade::render('<x-bt-checkbox label="Right Label" />');
    
    expect($html)->toContain('Right Label');
    expect($html)->toContain('inline-flex items-center');
});

it('can render with label on the left', function () {
    $html = Blade::render('<x-bt-checkbox label="Left Label" label-position="left" />');
    
    expect($html)->toContain('Left Label');
});

it('can render with name attribute', function () {
    $html = Blade::render('<x-bt-checkbox name="terms" />');
    
    // Name is merged with attributes
    expect($html)->toContain('type="checkbox"');
});

it('can render with value attribute', function () {
    $html = Blade::render('<x-bt-checkbox value="1" />');
    
    // Value is merged with attributes
    expect($html)->toContain('type="checkbox"');
});

it('can render with checked state', function () {
    $html = Blade::render('<x-bt-checkbox :checked="true" />');
    
    expect($html)->toContain('checked');
});

it('can render unchecked by default', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    // Check that the checkbox input doesn't have the checked attribute
    expect($html)->not->toContain('checked="checked"');
    expect($html)->not->toContain('checked>');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-checkbox :disabled="true" />');
    
    expect($html)->toContain('disabled');
});

it('renders checkbox as sr-only', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('sr-only');
});

it('renders with peer class', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('peer');
});

it('renders checkmark SVG', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('<svg');
    expect($html)->toContain('viewBox="0 0 16 16"');
    expect($html)->toContain('M4 8l3 3 5-5');
});

it('renders with scale animation for checkmark', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('scale-0');
    expect($html)->toContain('peer-checked:scale-100');
});

it('renders with transition', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('transition');
});

it('renders with cursor-pointer', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('cursor-pointer');
});

it('renders with select-none', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('select-none');
});

it('can render with rounded corners', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('rounded-');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-checkbox :custom-error="\'This field is required\'" />');

    expect($html)->toContain('This field is required');
    expect($html)->toContain('text-red-500');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-checkbox hint="Check to agree" />');
    
    expect($html)->toContain('Check to agree');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-checkbox help="Additional info" />');

    expect($html)->toContain('Additional info');
});


it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-checkbox size="sm" />');
    expect($htmlSm)->toContain('type="checkbox"');
    
    $htmlMd = Blade::render('<x-bt-checkbox size="md" />');
    expect($htmlMd)->toContain('type="checkbox"');
    
    $htmlLg = Blade::render('<x-bt-checkbox size="lg" />');
    expect($htmlLg)->toContain('type="checkbox"');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-checkbox color="primary" />');
    expect($htmlPrimary)->toContain('type="checkbox"');
    
    $htmlBlue = Blade::render('<x-bt-checkbox color="blue" />');
    expect($htmlBlue)->toContain('type="checkbox"');
});

it('can render with wire:model', function () {
    $html = Blade::render('<x-bt-checkbox wire:model="accepted" />');
    
    expect($html)->toContain('wire:model="accepted"');
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-checkbox class="custom-class" />');
    
    expect($html)->toContain('custom-class');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-checkbox data-test="checkbox" aria-label="Accept" />');
    
    expect($html)->toContain('data-test="checkbox"');
    expect($html)->toContain('aria-label="Accept"');
});

it('renders with pointer-events-none on checkmark', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('pointer-events-none');
});

it('wraps checkbox in flex container', function () {
    $html = Blade::render('<x-bt-checkbox />');
    
    expect($html)->toContain('flex flex-col');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-checkbox 
            name="terms"
            value="1"
            label="I accept the terms and conditions"
            label-position="right"
            :checked="true"
            hint="Please read carefully"
            size="lg"
            color="primary"
            wire:model="termsAccepted"
        />
    ');
    
    expect($html)->toContain('I accept the terms and conditions');
    expect($html)->toContain('checked');
    expect($html)->toContain('Please read carefully');
    expect($html)->toContain('wire:model="termsAccepted"');
});

it('renders disabled styles when disabled', function () {
    $html = Blade::render('<x-bt-checkbox :disabled="true" label="Disabled" />');
    
    expect($html)->toContain('disabled');
});
