<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic input component', function () {
    $html = Blade::render('<x-bt-input name="test_input" />');
    expect($html)->toContain('name="test_input"');
    expect($html)->toContain('data-beartropy-input');
});

it('can render with different input types', function () {
    $html = Blade::render('<x-bt-input name="email_input" type="email" />');
    expect($html)->toContain("x-bind:type=\"showPassword ? 'text' : 'email'\"");

    $htmlPassword = Blade::render('<x-bt-input name="password_input" type="password" />');
    expect($htmlPassword)->toContain("x-bind:type=\"showPassword ? 'text' : 'password'\"");

    $htmlNumber = Blade::render('<x-bt-input name="number_input" type="number" />');
    expect($htmlNumber)->toContain("x-bind:type=\"showPassword ? 'text' : 'number'\"");
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-input name="test_input" label="Test Label" />');
    expect($html)->toContain('Test Label');
    expect($html)->toContain('<label');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-input name="test_input" placeholder="Enter text here" />');
    expect($html)->toContain('placeholder="Enter text here"');
});

it('can render with initial value', function () {
    $value = 'Initial Value';
    $html = Blade::render('<x-bt-input name="test_input" value="'.$value.'" />');
    expect($html)->toContain($value);
});

it('can render with icon-start', function () {
    $html = Blade::render('<x-bt-input name="test_input" icon-start="magnifying-glass" />');
    expect($html)->toContain('beartropy-inputbase-start-slot');
    expect($html)->toContain('<svg');
});

it('can render with icon-end', function () {
    $html = Blade::render('<x-bt-input name="test_input" icon-end="envelope" />');
    expect($html)->toContain('beartropy-inputbase-end-slot');
    expect($html)->toContain('<svg');
});

it('can render with clearable button', function () {
    $html = Blade::render('<x-bt-input name="test_input" :clearable="true" />');
    expect($html)->toContain('x-on:click="clear"');
    expect($html)->toContain('aria-label="Clear"');
});

it('can render without clearable button', function () {
    $html = Blade::render('<x-bt-input name="test_input" :clearable="false" />');
    expect($html)->not->toContain('x-on:click="clear"');
});

it('can render with copy button', function () {
    $html = Blade::render('<x-bt-input name="test_input" :copy-button="true" />');
    expect($html)->toContain('x-on:click="copyToClipboard"');
    expect($html)->toContain('aria-label="Copy to clipboard"');
});

it('can render password toggle for password type', function () {
    $html = Blade::render('<x-bt-input name="password_input" type="password" />');
    expect($html)->toContain('x-on:click="showPassword = !showPassword"');
    expect($html)->toContain('Show password');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-input name="test_input" custom-error="This field is required" />');
    expect($html)->toContain('This field is required');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-input name="test_input" help="This is a help message" />');
    expect($html)->toContain('This is a help message');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-input name="test_input" hint="This is a hint" />');
    expect($html)->toContain('This is a hint');
});

it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-input name="test_input" size="sm" />');
    expect($htmlSm)->toContain('h-8'); // sm size class

    $htmlMd = Blade::render('<x-bt-input name="test_input" size="md" />');
    expect($htmlMd)->toContain('h-10'); // md size class (default)

    $htmlLg = Blade::render('<x-bt-input name="test_input" size="lg" />');
    expect($htmlLg)->toContain('h-12'); // lg size class
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-input 
            name="complex_input" 
            label="Complex Input"
            placeholder="Enter value"
            icon-start="magnifying-glass"
            :copy-button="true"
            :clearable="true"
            help="This is a complex input example"
            size="lg"
        />
    ');

    expect($html)->toContain('Complex Input');
    expect($html)->toContain('Enter value');
    expect($html)->toContain('beartropy-inputbase-start-slot');
    expect($html)->toContain('x-on:click="copyToClipboard"');
    expect($html)->toContain('x-on:click="clear"');
    expect($html)->toContain('This is a complex input example');
    expect($html)->toContain('h-12'); // lg size
});

it('generates unique input id when not provided', function () {
    $html1 = Blade::render('<x-bt-input name="test_input_1" />');
    $html2 = Blade::render('<x-bt-input name="test_input_2" />');

    expect($html1)->toContain('id="input-');
    expect($html2)->toContain('id="input-');
    expect($html1)->not->toBe($html2);
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-input name="test_input" id="custom-input-id" />');
    expect($html)->toContain('id="custom-input-id"');
});
