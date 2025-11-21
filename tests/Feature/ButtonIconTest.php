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
});

it('can render with explicit wire:target', function () {
    $html = Blade::render('<x-bt-button-icon wire:click="save" wire:target="save" />');
    
    expect($html)->toContain('wire:target="save"');
});

it('shows spinner during loading', function () {
    $html = Blade::render('<x-bt-button-icon :spinner="true" wire:click="save" />');
    
    expect($html)->toContain('wire:loading');
    expect($html)->toContain('wire:loading.remove');
});

it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-button-icon size="sm" />');
    expect($htmlSm)->toContain('<button');
    
    $htmlMd = Blade::render('<x-bt-button-icon size="md" />');
    expect($htmlMd)->toContain('<button');
    
    $htmlLg = Blade::render('<x-bt-button-icon size="lg" />');
    expect($htmlLg)->toContain('<button');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-button-icon color="primary" />');
    expect($htmlPrimary)->toContain('<button');
    
    $htmlSecondary = Blade::render('<x-bt-button-icon color="secondary" />');
    expect($htmlSecondary)->toContain('<button');
    
    $htmlDanger = Blade::render('<x-bt-button-icon color="danger" />');
    expect($htmlDanger)->toContain('<button');
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
            color="danger"
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
});

it('renders link without type attribute', function () {
    $html = Blade::render('<x-bt-button-icon href="/page" />');
    
    expect($html)->toContain('<a');
    expect($html)->toContain('href="/page"');
    expect($html)->not->toContain('type=');
});
