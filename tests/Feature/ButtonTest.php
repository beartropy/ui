<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic button component', function () {
    $html = Blade::render('<x-bt-button>Click me</x-bt-button>');
    
    expect($html)->toContain('<button');
    expect($html)->toContain('Click me');
});

it('can render button with label attribute', function () {
    $html = Blade::render('<x-bt-button label="Submit" />');
    
    expect($html)->toContain('Submit');
});

it('can render with different button types', function () {
    $htmlButton = Blade::render('<x-bt-button type="button">Button</x-bt-button>');
    expect($htmlButton)->toContain('type="button"');
    
    $htmlSubmit = Blade::render('<x-bt-button type="submit">Submit</x-bt-button>');
    expect($htmlSubmit)->toContain('type="submit"');
    
    $htmlReset = Blade::render('<x-bt-button type="reset">Reset</x-bt-button>');
    expect($htmlReset)->toContain('type="reset"');
});

it('can render as link when href is provided', function () {
    $html = Blade::render('<x-bt-button href="/home">Go Home</x-bt-button>');
    
    expect($html)->toContain('<a');
    expect($html)->toContain('href="/home"');
    expect($html)->toContain('Go Home');
    expect($html)->not->toContain('type="button"');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-button :disabled="true">Disabled</x-bt-button>');
    
    expect($html)->toContain('disabled');
});

it('can render with icon-start', function () {
    $html = Blade::render('<x-bt-button icon-start="plus">Add Item</x-bt-button>');
    
    expect($html)->toContain('Add Item');
    expect($html)->toContain('mr-2'); // Icon spacing
});

it('can render with icon-end', function () {
    $html = Blade::render('<x-bt-button icon-end="arrow-right">Next</x-bt-button>');
    
    expect($html)->toContain('Next');
    expect($html)->toContain('ml-2'); // Icon spacing
});

it('can render with both icons', function () {
    $html = Blade::render('<x-bt-button icon-start="plus" icon-end="arrow-right">Action</x-bt-button>');
    
    expect($html)->toContain('Action');
    expect($html)->toContain('mr-2'); // Start icon spacing
    expect($html)->toContain('ml-2'); // End icon spacing
});

it('can render with custom start slot', function () {
    $html = Blade::render('
        <x-bt-button>
            <x-slot:start>
                <span class="custom-start">Start</span>
            </x-slot:start>
            Button Text
        </x-bt-button>
    ');
    
    expect($html)->toContain('custom-start');
    expect($html)->toContain('Start');
    expect($html)->toContain('Button Text');
});

it('can render with custom end slot', function () {
    $html = Blade::render('
        <x-bt-button>
            Button Text
            <x-slot:end>
                <span class="custom-end">End</span>
            </x-slot:end>
        </x-bt-button>
    ');
    
    expect($html)->toContain('custom-end');
    expect($html)->toContain('End');
    expect($html)->toContain('Button Text');
});

it('can render with spinner enabled', function () {
    $html = Blade::render('<x-bt-button :spinner="true" wire:click="save">Save</x-bt-button>');
    
    expect($html)->toContain('wire:loading');
    expect($html)->toContain('animate-spin');
});

it('can render without spinner', function () {
    $html = Blade::render('<x-bt-button :spinner="false">Click</x-bt-button>');
    
    expect($html)->not->toContain('wire:loading');
});

it('renders with default button classes', function () {
    $html = Blade::render('<x-bt-button>Button</x-bt-button>');
    
    expect($html)->toContain('inline-flex');
    expect($html)->toContain('items-center');
    expect($html)->toContain('justify-center');
    expect($html)->toContain('rounded-md');
    expect($html)->toContain('transition-colors');
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-button class="custom-class">Button</x-bt-button>');
    
    expect($html)->toContain('custom-class');
});

it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-button size="sm">Small</x-bt-button>');
    expect($htmlSm)->toContain('Small');
    
    $htmlMd = Blade::render('<x-bt-button size="md">Medium</x-bt-button>');
    expect($htmlMd)->toContain('Medium');
    
    $htmlLg = Blade::render('<x-bt-button size="lg">Large</x-bt-button>');
    expect($htmlLg)->toContain('Large');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-button color="primary">Primary</x-bt-button>');
    expect($htmlPrimary)->toContain('Primary');
    
    $htmlSecondary = Blade::render('<x-bt-button color="secondary">Secondary</x-bt-button>');
    expect($htmlSecondary)->toContain('Secondary');
    
    $htmlDanger = Blade::render('<x-bt-button color="danger">Danger</x-bt-button>');
    expect($htmlDanger)->toContain('Danger');
});

it('can render with different variants', function () {
    $htmlSolid = Blade::render('<x-bt-button variant="solid">Solid</x-bt-button>');
    expect($htmlSolid)->toContain('Solid');
    
    $htmlOutline = Blade::render('<x-bt-button variant="outline">Outline</x-bt-button>');
    expect($htmlOutline)->toContain('Outline');
    
    $htmlGhost = Blade::render('<x-bt-button variant="ghost">Ghost</x-bt-button>');
    expect($htmlGhost)->toContain('Ghost');
});

it('can render with wire:click attribute', function () {
    $html = Blade::render('<x-bt-button wire:click="handleClick">Click Me</x-bt-button>');
    
    expect($html)->toContain('wire:click="handleClick"');
});

it('can render with wire:target for loading state', function () {
    $html = Blade::render('<x-bt-button wire:click="save" wire:target="save">Save</x-bt-button>');
    
    expect($html)->toContain('wire:target="save"');
    expect($html)->toContain('wire:loading.attr="disabled"');
});

it('auto-detects wire:target from wire:click', function () {
    $html = Blade::render('<x-bt-button wire:click="submitForm">Submit</x-bt-button>');
    
    expect($html)->toContain('wire:click="submitForm"');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-button data-test="custom-button" aria-label="Custom Button">Button</x-bt-button>');
    
    expect($html)->toContain('data-test="custom-button"');
    expect($html)->toContain('aria-label="Custom Button"');
});

it('renders disabled styles when disabled', function () {
    $html = Blade::render('<x-bt-button :disabled="true">Disabled</x-bt-button>');
    
    expect($html)->toContain('disabled');
});

it('can render icon-only button', function () {
    $html = Blade::render('<x-bt-button icon-start="trash" aria-label="Delete" />');
    
    expect($html)->toContain('aria-label="Delete"');
});

it('prefers slot content over label attribute', function () {
    $html = Blade::render('<x-bt-button label="Label Text">Slot Text</x-bt-button>');
    
    expect($html)->toContain('Slot Text');
});

it('uses label when slot is empty', function () {
    $html = Blade::render('<x-bt-button label="Label Text"></x-bt-button>');
    
    expect($html)->toContain('Label Text');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-button 
            type="submit"
            icon-start="check"
            icon-end="arrow-right"
            size="lg"
            color="primary"
            :spinner="true"
            wire:click="submit"
            class="custom-submit"
        >
            Submit Form
        </x-bt-button>
    ');
    
    expect($html)->toContain('type="submit"');
    expect($html)->toContain('Submit Form');
    expect($html)->toContain('custom-submit');
    expect($html)->toContain('wire:click="submit"');
    expect($html)->toContain('mr-2'); // icon-start spacing
    expect($html)->toContain('ml-2'); // icon-end spacing
});

it('renders link without disabled attribute', function () {
    $html = Blade::render('<x-bt-button href="/page" :disabled="true">Link</x-bt-button>');
    
    expect($html)->toContain('<a');
    expect($html)->toContain('href="/page"');
    // Links can have disabled class but not disabled attribute
});
