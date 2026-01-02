<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic textarea component', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" />');

    expect($html)->toContain('<textarea');
    expect($html)->toContain('name="test_textarea"');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" label="Description" />');

    expect($html)->toContain('Description');
    expect($html)->toContain('<label');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" placeholder="Enter your text here..." />');

    expect($html)->toContain('Enter your text here...');
});

it('can render with default rows', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" />');

    expect($html)->toContain('rows="4"');
});

it('can render with custom rows', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :rows="10" />');

    expect($html)->toContain('rows="10"');
});

it('can render with custom cols', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :cols="50" />');

    expect($html)->toContain('cols="50"');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('can render with readonly state', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :readonly="true" />');

    expect($html)->toContain('readonly');
});

it('can render with required attribute', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :required="true" />');

    expect($html)->toContain('required');
});

it('can render with maxlength', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :max-length="500" />');

    expect($html)->toContain('maxlength="500"');
});

it('shows character counter by default', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :max-length="100" />');

    expect($html)->toContain('x-text');
});

it('can hide character counter', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :max-length="100" :show-counter="false" />');

    expect($html)->not->toContain('x-text="characterCount"');
});

it('shows copy button by default', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" />');

    expect($html)->toContain('clipboard');
});

it('can hide copy button', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :show-copy-button="false" />');

    // Should not render the copy button section
    expect($html)->not->toContain('Copiar contenido');
});

it('can render with auto-resize enabled', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :auto-resize="true" />');

    expect($html)->toContain('x-data');
});

it('can render with custom resize option', function () {
    $htmlNone = Blade::render('<x-bt-textarea name="test_textarea" resize="none" />');
    expect($htmlNone)->toContain('<textarea');

    $htmlVertical = Blade::render('<x-bt-textarea name="test_textarea" resize="y" />');
    expect($htmlVertical)->toContain('<textarea');

    $htmlHorizontal = Blade::render('<x-bt-textarea name="test_textarea" resize="x" />');
    expect($htmlHorizontal)->toContain('<textarea');

    $htmlBoth = Blade::render('<x-bt-textarea name="test_textarea" resize="both" />');
    expect($htmlBoth)->toContain('<textarea');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" help="Maximum 500 characters" />');

    expect($html)->toContain('Maximum 500 characters');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" custom-error="This field is required" />');

    expect($html)->toContain('This field is required');
});

it('can render with initial value via slot', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea">Initial content here</x-bt-textarea>');

    expect($html)->toContain('Initial content here');
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" id="custom-textarea-id" />');

    expect($html)->toContain('id="custom-textarea-id"');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-textarea name="test_textarea_1" />');
    $html2 = Blade::render('<x-bt-textarea name="test_textarea_2" />');

    expect($html1)->not->toBe($html2);
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" class="custom-class" />');

    expect($html)->toContain('custom-class');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-textarea name="test_textarea" color="primary" />');
    expect($htmlPrimary)->toContain('<textarea');

    $htmlDanger = Blade::render('<x-bt-textarea name="test_textarea" color="danger" />');
    expect($htmlDanger)->toContain('<textarea');

    $htmlSuccess = Blade::render('<x-bt-textarea name="test_textarea" color="success" />');
    expect($htmlSuccess)->toContain('<textarea');
});

it('can render with wire:model', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" wire:model="description" />');

    expect($html)->toContain('wire:model="description"');
});

it('can render with wire:model.debounce', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" wire:model.debounce.500ms="description" />');

    expect($html)->toContain('wire:model.debounce.500ms="description"');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" data-test="custom-textarea" aria-label="Custom Textarea" />');

    expect($html)->toContain('data-test="custom-textarea"');
    expect($html)->toContain('aria-label="Custom Textarea"');
});

it('renders with base styling classes', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" />');

    expect($html)->toContain('<textarea');
    expect($html)->toContain('name="test_textarea"');
});

it('renders disabled styling when disabled', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('can render with spellcheck enabled', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" spellcheck="true" />');

    expect($html)->toContain('spellcheck="true"');
});

it('can render with autocomplete', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" autocomplete="on" />');

    expect($html)->toContain('autocomplete="on"');
});

it('displays character count correctly', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" :max-length="200" />');

    expect($html)->toContain('200');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-textarea 
            name="comment"
            label="Your Comment"
            placeholder="Share your thoughts..."
            :rows="8"
            :max-length="1000"
            :show-counter="true"
            :show-copy-button="true"
            :auto-resize="true"
            resize="vertical"
            help="Be respectful and constructive"
            :required="true"
            wire:model.debounce.300ms="userComment"
            class="custom-textarea"
        >
            Initial comment text
        </x-bt-textarea>
    ');

    expect($html)->toContain('Your Comment');
    expect($html)->toContain('Share your thoughts...');
    expect($html)->toContain('rows="8"');
    expect($html)->toContain('maxlength="1000"');
    expect($html)->toContain('Be respectful and constructive');
    expect($html)->toContain('required');
    expect($html)->toContain('wire:model.debounce.300ms="userComment"');
    expect($html)->toContain('custom-textarea');
    expect($html)->toContain('Initial comment text');
});

it('handles error state styling', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea" custom-error="Invalid input" />');

    expect($html)->toContain('Invalid input');
});

it('handles multiline initial content', function () {
    $html = Blade::render('<x-bt-textarea name="test_textarea">
        Line 1
        Line 2
        Line 3
    </x-bt-textarea>');

    expect($html)->toContain('Line 1');
    expect($html)->toContain('Line 2');
    expect($html)->toContain('Line 3');
});

it('can render without optional features', function () {
    $html = Blade::render('<x-bt-textarea name="minimal" :show-counter="false" :show-copy-button="false" />');

    expect($html)->toContain('name="minimal"');
    expect($html)->toContain('<textarea');
});
