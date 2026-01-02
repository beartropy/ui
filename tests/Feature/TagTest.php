<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic tag component', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('tagInput');
});

it('renders with Alpine.js tagInput', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('$beartropy.tagInput');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-tag label="Tags" />');

    expect($html)->toContain('Tags');
    expect($html)->toContain('<label');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-tag placeholder="Enter tags..." />');

    expect($html)->toContain('Enter tags...');
});

it('uses default placeholder', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('Add tag...');
});

it('can render with initial values', function () {
    $html = Blade::render('<x-bt-tag :value="[\'tag1\', \'tag2\']" />');

    expect($html)->toContain('initialTags');
});

it('enforces unique tags by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('unique: true');
});

it('can disable unique tags', function () {
    $html = Blade::render('<x-bt-tag :unique="false" />');

    expect($html)->toContain('unique: false');
});

it('can set max tags', function () {
    $html = Blade::render('<x-bt-tag :maxTags="5" />');

    expect($html)->toContain('maxTags: 5');
});

it('renders with no max tags by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('maxTags: null');
});

it('can be disabled', function () {
    $html = Blade::render('<x-bt-tag :disabled="true" />');

    expect($html)->toContain('disabled: true');
    expect($html)->toContain('cursor-not-allowed');
    expect($html)->toContain('opacity-60');
});

it('is enabled by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('disabled: false');
});

it('supports custom separator', function () {
    $html = Blade::render('<x-bt-tag separator=";" />');

    expect($html)->toContain('separator');
});

it('uses comma separator by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('separator');
});

it('renders input field', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('<input');
    expect($html)->toContain('x-ref="input"');
});

it('renders with key event handlers', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('@keydown.enter.prevent');
    expect($html)->toContain('@keydown.tab');
    expect($html)->toContain('@keydown.backspace');
});

it('renders tag chips container', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('x-for');
    expect($html)->toContain('tags');
});

it('renders remove button for tags', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('removeTag');
    expect($html)->toContain('&times;');
});

it('supports start slot', function () {
    $html = Blade::render('
        <x-bt-tag>
            <x-slot:start>
                <span>Icon</span>
            </x-slot:start>
        </x-bt-tag>
    ');

    expect($html)->toContain('Icon');
});

it('supports end slot', function () {
    $html = Blade::render('
        <x-bt-tag>
            <x-slot:end>
                <span>Clear</span>
            </x-slot:end>
        </x-bt-tag>
    ');

    expect($html)->toContain('Clear');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-tag help="Enter tags separated by comma" />');

    expect($html)->toContain('x-data');
});

it('can render with error', function () {
    $html = Blade::render('<x-bt-tag error="Tags are required" />');

    expect($html)->toContain('x-data');
});

it('generates unique input ID', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('taginput-');
});

it('can render with custom ID', function () {
    $html = Blade::render('<x-bt-tag id="my-tags" />');

    expect($html)->toContain('id="my-tags"');
});


it('supports paste handling', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('@paste="handlePaste"');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-tag 
            name="tags"
            label="Project Tags"
            placeholder="Add tag..."
            :value="[\'tag1\']"
            :unique="true"
            :maxTags="10"
            :disabled="false"
            help="Add up to 10 tags"
        />
    ');

    expect($html)->toContain('Project Tags');
    expect($html)->toContain('Add tag...');
    expect($html)->toContain('unique: true');
    expect($html)->toContain('maxTags: 10');
    expect($html)->toContain('disabled: false');
});
