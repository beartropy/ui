<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic button icon component', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('<button');
    expect($html)->toContain('flex items-center justify-center');
});

it('can render with default icon', function () {
    $html = Blade::render('<x-bt-button-icon />');

    // Default icon is 'plus'
    expect($html)->toContain('<svg');
});

it('can render with custom icon', function () {
    $html = Blade::render('<x-bt-button-icon icon="trash" />');

    expect($html)->toContain('<svg');
});

it('can render with slot content', function () {
    $html = Blade::render('<x-bt-button-icon><span class="custom-icon">X</span></x-bt-button-icon>');

    expect($html)->toContain('custom-icon');
    expect($html)->toContain('X');
});

it('prefers slot content over icon attribute', function () {
    $html = Blade::render('<x-bt-button-icon icon="plus"><span class="custom">Custom</span></x-bt-button-icon>');

    expect($html)->toContain('custom');
    expect($html)->toContain('Custom');
});

it('can render as link when href is provided', function () {
    $html = Blade::render('<x-bt-button-icon href="/home" />');

    expect($html)->toContain('<a');
    expect($html)->toContain('href="/home"');
});

it('renders as button by default', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('<button');
});

it('can render with rounded-full by default', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('rounded-full');
});

it('can render with custom rounded value', function () {
    $html = Blade::render('<x-bt-button-icon rounded="md" />');

    expect($html)->toContain('rounded-md');
});

it('can render with different rounded values', function () {
    $htmlNone = Blade::render('<x-bt-button-icon rounded="none" />');
    expect($htmlNone)->toContain('rounded-none');

    $htmlLg = Blade::render('<x-bt-button-icon rounded="lg" />');
    expect($htmlLg)->toContain('rounded-lg');
});

it('renders with shadow-lg', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('shadow-lg');
});

it('renders with transition', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('transition');
});

it('can render with spinner enabled', function () {
    $html = Blade::render('<x-bt-button-icon :spinner="true" wire:click="save" />');

    expect($html)->toContain('wire:loading');
    expect($html)->toContain('animate-spin');
});

it('can render without spinner', function () {
    $html = Blade::render('<x-bt-button-icon :spinner="false" />');

    expect($html)->not->toContain('wire:loading');
});

it('auto-detects wire:target from wire:click', function () {
    $html = Blade::render('<x-bt-button-icon wire:click="handleClick" />');

    expect($html)->toContain('wire:click="handleClick"');
    expect($html)->toContain('wire:target="handleClick"');
});

it('can render with explicit wire:target', function () {
    $html = Blade::render('<x-bt-button-icon wire:click="save" wire:target="save" />');

    expect($html)->toContain('wire:target="save"');
});

it('does not duplicate wire:target when explicitly provided', function () {
    $html = Blade::render('<x-bt-button-icon wire:click="save" wire:target="customTarget" />');

    // Should have wire:target="customTarget" from attributes merge, not an extra one
    expect(substr_count($html, 'wire:target='))->toBeLessThanOrEqual(3);
    expect($html)->toContain('wire:target="customTarget"');
});

it('shows spinner during loading', function () {
    $html = Blade::render('<x-bt-button-icon :spinner="true" wire:click="save" />');

    expect($html)->toContain('wire:loading');
    expect($html)->toContain('wire:loading.remove');
});

it('applies correct size classes for sm', function () {
    $html = Blade::render('<x-bt-button-icon size="sm" />');

    expect($html)->toContain('w-8 h-8');
    expect($html)->toContain('w-3 h-3');
});

it('applies correct size classes for md', function () {
    $html = Blade::render('<x-bt-button-icon size="md" />');

    expect($html)->toContain('w-10 h-10');
    expect($html)->toContain('w-5 h-5');
});

it('applies correct size classes for lg', function () {
    $html = Blade::render('<x-bt-button-icon size="lg" />');

    expect($html)->toContain('w-12 h-12');
    expect($html)->toContain('w-6 h-6');
});

it('applies correct size classes for xs', function () {
    $html = Blade::render('<x-bt-button-icon size="xs" />');

    expect($html)->toContain('w-7 h-7');
    expect($html)->toContain('w-2 h-2');
});

it('applies correct size classes for xl', function () {
    $html = Blade::render('<x-bt-button-icon size="xl" />');

    expect($html)->toContain('w-14 h-14');
    expect($html)->toContain('w-7 h-7');
});

it('applies correct color classes for red', function () {
    $html = Blade::render('<x-bt-button-icon color="red" />');

    expect($html)->toContain('bg-red-600');
    expect($html)->toContain('hover:bg-red-700');
    expect($html)->toContain('text-white');
});

it('applies correct color classes for blue', function () {
    $html = Blade::render('<x-bt-button-icon color="blue" />');

    expect($html)->toContain('bg-blue-600');
    expect($html)->toContain('hover:bg-blue-700');
});

it('applies correct color classes for green', function () {
    $html = Blade::render('<x-bt-button-icon color="green" />');

    expect($html)->toContain('bg-green-600');
    expect($html)->toContain('hover:bg-green-700');
});

it('applies default beartropy color', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('bg-beartropy-600');
    expect($html)->toContain('hover:bg-beartropy-700');
});

it('renders default aria-label', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('aria-label="New"');
});

it('renders custom aria-label from label prop', function () {
    $html = Blade::render('<x-bt-button-icon label="Delete item" />');

    expect($html)->toContain('aria-label="Delete item"');
});

it('allows aria-label attribute to override default', function () {
    $html = Blade::render('<x-bt-button-icon aria-label="Custom label" />');

    expect($html)->toContain('aria-label="Custom label"');
});

it('does not render duplicate attributes', function () {
    $html = Blade::render('<x-bt-button-icon data-test="my-button" />');

    expect(substr_count($html, 'data-test="my-button"'))->toBe(1);
});

it('does not render duplicate href on links', function () {
    $html = Blade::render('<x-bt-button-icon href="/page" />');

    expect(substr_count($html, 'href="/page"'))->toBe(1);
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-button-icon class="custom-class" />');

    expect($html)->toContain('custom-class');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-button-icon data-test="icon-button" aria-label="Delete" />');

    expect($html)->toContain('data-test="icon-button"');
    expect($html)->toContain('aria-label="Delete"');
});

it('can render with type attribute', function () {
    $html = Blade::render('<x-bt-button-icon type="submit" />');

    expect($html)->toContain('type="submit"');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-button-icon disabled />');

    expect($html)->toContain('disabled');
});

it('wraps button in relative div', function () {
    $html = Blade::render('<x-bt-button-icon />');

    expect($html)->toContain('<div class="relative">');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-button-icon
            icon="trash"
            size="lg"
            color="red"
            rounded="lg"
            :spinner="true"
            wire:click="delete"
            aria-label="Delete item"
            class="custom-delete"
        />
    ');

    expect($html)->toContain('rounded-lg');
    expect($html)->toContain('shadow-lg');
    expect($html)->toContain('transition');
    expect($html)->toContain('wire:click="delete"');
    expect($html)->toContain('aria-label="Delete item"');
    expect($html)->toContain('custom-delete');
    expect($html)->toContain('wire:loading');
    expect($html)->toContain('bg-red-600');
    expect($html)->toContain('w-12 h-12');
});

it('renders link without type attribute', function () {
    $html = Blade::render('<x-bt-button-icon href="/page" />');

    expect($html)->toContain('<a');
    expect($html)->toContain('href="/page"');
    expect($html)->not->toContain('type=');
});

it('uses magic color attribute', function () {
    $html = Blade::render('<x-bt-button-icon red />');

    expect($html)->toContain('bg-red-600');
});

it('uses magic size attribute', function () {
    $html = Blade::render('<x-bt-button-icon lg />');

    expect($html)->toContain('w-12 h-12');
});

it('scales spinner size with button size', function () {
    $html = Blade::render('<x-bt-button-icon size="lg" :spinner="true" wire:click="save" />');

    // Spinner should use lg icon size (w-6 h-6), not hardcoded w-5 h-5
    expect($html)->toContain('w-6 h-6 animate-spin');
});
