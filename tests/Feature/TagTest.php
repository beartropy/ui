<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ── Basic rendering ─────────────────────────────────────────────

it('renders with beartropyTagInput x-data', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('x-data="beartropyTagInput({');
});

it('does not use legacy $beartropy.tagInput', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->not->toContain('$beartropy.tagInput');
});

// ── Label ───────────────────────────────────────────────────────

it('renders label with for attribute', function () {
    $html = Blade::render('<x-bt-tag label="My Tags" id="custom-id" />');

    expect($html)->toContain('<label');
    expect($html)->toContain('for="custom-id"');
    expect($html)->toContain('My Tags');
});

it('safely escapes label text', function () {
    $html = Blade::render('<x-bt-tag label="<script>alert(1)</script>" />');

    expect($html)->not->toContain('<script>');
    expect($html)->toContain('&lt;script&gt;');
});

it('does not render label when not provided', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->not->toContain('<label');
});

// ── Placeholder ─────────────────────────────────────────────────

it('uses i18n default placeholder', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('Add tag...');
});

it('uses custom placeholder when provided', function () {
    $html = Blade::render('<x-bt-tag placeholder="Enter tags..." />');

    expect($html)->toContain('Enter tags...');
});

// ── ID ──────────────────────────────────────────────────────────

it('uses custom id on input', function () {
    $html = Blade::render('<x-bt-tag id="my-tags" />');

    expect($html)->toContain('id="my-tags"');
});

it('auto-generates id when not provided', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('id="beartropy-tag-');
});

// ── Name ────────────────────────────────────────────────────────

it('uses explicit name for hidden inputs', function () {
    $html = Blade::render('<x-bt-tag name="my-tags" />');

    expect($html)->toContain('`my-tags[]`');
});

it('falls back name to id', function () {
    $html = Blade::render('<x-bt-tag id="fallback-id" />');

    expect($html)->toContain('`fallback-id[]`');
});

// ── Initial values ──────────────────────────────────────────────

it('passes initial values to Alpine', function () {
    $html = Blade::render('<x-bt-tag :value="[\'tag1\', \'tag2\']" />');

    expect($html)->toContain('initialTags:');
    expect($html)->toContain('tag1');
    expect($html)->toContain('tag2');
});

// ── Unique ──────────────────────────────────────────────────────

it('enforces unique tags by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('unique: true');
});

it('can disable unique tags', function () {
    $html = Blade::render('<x-bt-tag :unique="false" />');

    expect($html)->toContain('unique: false');
});

// ── Max tags ────────────────────────────────────────────────────

it('passes max tags to Alpine', function () {
    $html = Blade::render('<x-bt-tag :maxTags="5" />');

    expect($html)->toContain('maxTags: 5');
});

it('defaults to null max tags', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('maxTags: null');
});

// ── Disabled ────────────────────────────────────────────────────

it('renders disabled state', function () {
    $html = Blade::render('<x-bt-tag :disabled="true" />');

    expect($html)->toContain('disabled: true');
    expect($html)->toContain('cursor-not-allowed');
    expect($html)->toContain('opacity-60');
    expect($html)->toContain('aria-disabled="true"');
});

it('is enabled by default', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('disabled: false');
    expect($html)->not->toContain('aria-disabled');
});

// ── Separator ───────────────────────────────────────────────────

it('supports custom separator', function () {
    $html = Blade::render('<x-bt-tag separator=";" />');

    expect($html)->toContain('separator:');
});

// ── Input field + key handlers ──────────────────────────────────

it('renders input field with event handlers', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('<input');
    expect($html)->toContain('x-ref="input"');
    expect($html)->toContain('x-model="input"');
    expect($html)->toContain('@keydown.enter.prevent');
    expect($html)->toContain('@keydown.tab');
    expect($html)->toContain('@keydown.backspace');
    expect($html)->toContain('@blur="addTag()"');
    expect($html)->toContain('@paste="handlePaste"');
});

// ── Chips container ─────────────────────────────────────────────

it('renders chip template with removeTag', function () {
    $html = Blade::render('<x-bt-tag />');

    expect($html)->toContain('x-for="(tag, i) in tags"');
    expect($html)->toContain('x-text="tag"');
    expect($html)->toContain('removeTag(i)');
    expect($html)->toContain('&times;');
});

// ── Slots ───────────────────────────────────────────────────────

it('supports start slot', function () {
    $html = Blade::render('
        <x-bt-tag>
            <x-slot:start>
                <span class="start-icon">S</span>
            </x-slot:start>
        </x-bt-tag>
    ');

    expect($html)->toContain('beartropy-inputbase-start-slot');
    expect($html)->toContain('start-icon');
});

it('supports end slot', function () {
    $html = Blade::render('
        <x-bt-tag>
            <x-slot:end>
                <span class="end-icon">E</span>
            </x-slot:end>
        </x-bt-tag>
    ');

    expect($html)->toContain('beartropy-inputbase-end-slot');
    expect($html)->toContain('end-icon');
});

// ── Help text ───────────────────────────────────────────────────

it('renders help text in field-help', function () {
    $html = Blade::render('<x-bt-tag help="Separate with commas" />');

    expect($html)->toContain('Separate with commas');
    expect($html)->toContain('text-gray-400');
});

// ── Hint text ───────────────────────────────────────────────────

it('renders hint text in field-help', function () {
    $html = Blade::render('<x-bt-tag hint="Max 5 tags" />');

    expect($html)->toContain('Max 5 tags');
    expect($html)->toContain('text-gray-400');
});

it('prefers hint over help when both provided', function () {
    $html = Blade::render('<x-bt-tag hint="Hint text" help="Help text" />');

    expect($html)->toContain('Hint text');
});

// ── Custom error ────────────────────────────────────────────────

it('renders custom error in field-help', function () {
    $html = Blade::render('<x-bt-tag customError="Tags required" />');

    expect($html)->toContain('Tags required');
    expect($html)->toContain('text-red-500');
});

// ── Hidden inputs for form submission ───────────────────────────

it('renders hidden input template for form submission', function () {
    $html = Blade::render('<x-bt-tag name="tags" />');

    expect($html)->toContain('type="hidden"');
    expect($html)->toContain(':name="`tags[]`"');
    expect($html)->toContain(':value="tag"');
});

it('renders hidden inputs only when no wire:model', function () {
    // Without wire:model, hidden inputs should be present
    $html = Blade::render('<x-bt-tag name="tags" />');

    expect($html)->toContain('type="hidden"');
    expect($html)->toContain(':name="`tags[]`"');
    // Note: wire:model test requires Livewire context for @entangle
});

// ── Attribute forwarding ────────────────────────────────────────

it('forwards custom classes to wrapper div', function () {
    $html = Blade::render('<x-bt-tag class="mt-4" />');

    expect($html)->toContain('mt-4');
});

it('forwards custom data attributes to wrapper', function () {
    $html = Blade::render('<x-bt-tag data-testid="tag-input" />');

    expect($html)->toContain('data-testid="tag-input"');
});

// ── Combined features ───────────────────────────────────────────

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-tag
            id="project-tags"
            name="tags"
            label="Project Tags"
            placeholder="Add a tag..."
            :value="[\'laravel\']"
            :unique="true"
            :maxTags="10"
            :disabled="false"
            help="Add up to 10 tags"
            class="mt-2"
        />
    ');

    expect($html)->toContain('beartropyTagInput');
    expect($html)->toContain('id="project-tags"');
    expect($html)->toContain('for="project-tags"');
    expect($html)->toContain('Project Tags');
    expect($html)->toContain('Add a tag...');
    expect($html)->toContain('unique: true');
    expect($html)->toContain('maxTags: 10');
    expect($html)->toContain('disabled: false');
    expect($html)->toContain('Add up to 10 tags');
    expect($html)->toContain('mt-2');
    expect($html)->toContain(':name="`tags[]`"');
});
