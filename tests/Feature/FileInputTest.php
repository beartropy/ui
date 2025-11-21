<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic file input component', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('type="file"');
    expect($html)->toContain('name="test_file"');
    expect($html)->toContain('x-data');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" label="Upload File" />');
    
    expect($html)->toContain('Upload File');
    expect($html)->toContain('<label');
});

it('can render with custom placeholder', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" placeholder="Select your file..." />');
    
    expect($html)->toContain('Select your file...');
});

it('can render with default placeholder', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('Elegir archivo');
});

it('can render in multiple mode', function () {
    $html = Blade::render('<x-bt-file-input name="test_files" :multiple="true" />');
    
    expect($html)->toContain('multiple');
});

it('can render in single mode by default', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->not->toContain('multiple');
});

it('can render with accept attribute', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" accept="image/*" />');
    
    expect($html)->toContain('accept="image/*"');
});

it('can render with specific file types', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" accept=".pdf,.doc,.docx" />');
    
    expect($html)->toContain('accept=".pdf,.doc,.docx"');
});

it('can render with clearable button', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" :clearable="true" />');
    
    expect($html)->toContain('@click.stop="clear()"');
    expect($html)->toContain('x-show="files.length"');
});

it('can render without clearable button', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" :clearable="false" />');
    
    expect($html)->not->toContain('@click.stop="clear()"');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" :disabled="true" />');
    
    expect($html)->toContain('disabled');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" custom-error="File is required" />');
    
    expect($html)->toContain('File is required');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" hint="Maximum file size: 10MB" />');
    
    expect($html)->toContain('Maximum file size: 10MB');
});

it('renders file input as sr-only', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('sr-only');
});

it('renders paper-clip icon', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    // Icon is rendered as SVG, check for the path that represents a paper clip
    expect($html)->toContain('<svg');
    expect($html)->toContain('m18.375 12.739');
});

it('renders upload icon', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    // Icon is rendered as SVG, check for the upload arrow path
    expect($html)->toContain('<svg');
    expect($html)->toContain('M3 16.5v2.25');
});

it('has onChange handler', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('onChange(e)');
    expect($html)->toContain('x-on:change="onChange($event)"');
});

it('has clear function', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('clear()');
});

it('has openPicker function', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('openPicker()');
    expect($html)->toContain('@click="openPicker()"');
});

it('has keyboard accessibility', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('@keydown.enter.prevent="openPicker()"');
    expect($html)->toContain('@keydown.space.prevent="openPicker()"');
    expect($html)->toContain('role="button"');
    expect($html)->toContain('tabindex="0"');
});

it('shows uploading state with Livewire', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" wire:model="file" />');
    
    expect($html)->toContain('uploading');
    expect($html)->toContain('x-show="uploading"');
    expect($html)->toContain('livewire-upload-start');
});

it('shows uploaded state with Livewire', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" wire:model="file" />');
    
    expect($html)->toContain('uploaded');
    expect($html)->toContain('x-show="!uploading && uploaded"');
    expect($html)->toContain('livewire-upload-finish');
});

it('shows validation errors state with Livewire', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" wire:model="file" />');
    
    expect($html)->toContain('validationErrors');
    expect($html)->toContain('livewire-upload-error');
});

it('renders spinner during upload', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" wire:model="file" />');
    
    expect($html)->toContain('animate-spin');
    expect($html)->toContain('Cargando…');
});

it('renders success icon after upload', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" wire:model="file" />');
    
    expect($html)->toContain('Subido');
});

it('renders error icon on validation error', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" custom-error="Invalid file" />');
    
    expect($html)->toContain('Error de validación');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-file-input name="test_file_1" />');
    $html2 = Blade::render('<x-bt-file-input name="test_file_2" />');
    
    expect($html1)->toContain('id="input-file-');
    expect($html2)->toContain('id="input-file-');
    expect($html1)->not->toBe($html2);
});

it('has Alpine.js reactive files array', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('files: []');
});

it('updates label based on file selection', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('this.files[0].name');
    expect($html)->toContain('archivos seleccionados');
});

it('can render with custom end slot', function () {
    $html = Blade::render('
        <x-bt-file-input name="test_file">
            <x-slot:end>
                <span class="custom-end">Custom</span>
            </x-slot:end>
        </x-bt-file-input>
    ');
    
    expect($html)->toContain('custom-end');
    expect($html)->toContain('Custom');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-file-input 
            name="documents"
            label="Upload Documents"
            placeholder="Choose files..."
            :multiple="true"
            accept=".pdf,.doc,.docx"
            :clearable="true"
            hint="Max 10MB per file"
            wire:model="documents"
        />
    ');
    
    expect($html)->toContain('Upload Documents');
    expect($html)->toContain('Choose files...');
    expect($html)->toContain('multiple');
    expect($html)->toContain('accept=".pdf,.doc,.docx"');
    expect($html)->toContain('@click.stop="clear()"');
    expect($html)->toContain('Max 10MB per file');
    expect($html)->toContain('wire:model="documents"');
});

it('renders with cursor-pointer for clickable area', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('cursor-pointer');
});

it('renders with select-none to prevent text selection', function () {
    $html = Blade::render('<x-bt-file-input name="test_file" />');
    
    expect($html)->toContain('select-none');
});

it('uses name as id fallback', function () {
    $html = Blade::render('<x-bt-file-input name="my_file" />');
    
    expect($html)->toContain('name="my_file"');
});
