<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic file dropzone component', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('type="file"');
    expect($html)->toContain('name="test_file[]"'); // Multiple by default
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" label="Upload Files" />');

    expect($html)->toContain('Upload Files');
    expect($html)->toContain('<label');
});

it('renders with Alpine.js file management', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('files:');
    expect($html)->toContain('syncInput');
    expect($html)->toContain('addFiles');
    expect($html)->toContain('clearFiles');
});

it('renders drag and drop area', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('dragover.prevent');
    expect($html)->toContain('drop.prevent');
});

it('renders upload icon by default', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    // Icon is rendered via SVG include
    expect($html)->toContain('<svg');
});

it('can render with custom icon', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" icon="document" />');

    // Component should render successfully with custom icon
    expect($html)->toContain('x-data');
});

it('can render with multiple files enabled by default', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('multiple');
});

it('can render with single file mode', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" :multiple="false" />');

    expect($html)->not->toContain(' multiple');
    expect($html)->toContain('name="test_file"'); // No []
});

it('can render with file preview enabled by default', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('x-show="files.length"');
    expect($html)->toContain('URL.createObjectURL');
});

it('can render without file preview', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" :preview="false" />');

    expect($html)->not->toContain('URL.createObjectURL');
});

it('can render with accept attribute', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" accept="image/*" />');

    expect($html)->toContain('accept="image/*"');
});

it('can render with clearable option by default', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('clearFiles');
    expect($html)->toContain('Clear all');
});

it('can render without clearable option', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" :clearable="false" />');

    expect($html)->not->toContain('Clear all');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" custom-error="File upload failed" />');

    expect($html)->toContain('File upload failed');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" hint="Max file size: 10MB" />');

    expect($html)->toContain('Max file size: 10MB');
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-file-dropzone id="custom-dropzone-id" name="test_file" />');

    expect($html)->toContain('id="custom-dropzone-id"');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-file-dropzone name="test_file_1" />');
    $html2 = Blade::render('<x-bt-file-dropzone name="test_file_2" />');

    expect($html1)->not->toBe($html2);
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" class="custom-dropzone" />');

    expect($html)->toContain('custom-dropzone');
});

it('renders drop zone text', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('Drop files here or click to select');
});

it('renders file list display', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('files.map(f => f.name).join');
});

it('handles remove file functionality', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('removeFile');
});

it('renders progress bar for Livewire uploads', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" wire:model="upload" />');

    expect($html)->toContain('uploading');
    expect($html)->toContain('progress');
});

it('listens to Livewire upload events', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('livewire-upload-start');
    expect($html)->toContain('livewire-upload-finish');
    expect($html)->toContain('livewire-upload-error');
    expect($html)->toContain('livewire-upload-progress');
});

it('renders hidden file input', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('class="hidden"');
    expect($html)->toContain('type="file"');
});

it('renders clickable label for file selection', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('<label for=');
    expect($html)->toContain('cursor-pointer');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-file-dropzone 
            name="documents"
            label="Upload Documents"
            icon="document"
            :multiple="true"
            accept=".pdf,.doc,.docx"
            :preview="true"
            :clearable="true"
            hint="Accepted formats: PDF, DOC, DOCX"
            class="custom-document-uploader"
        />
    ');

    expect($html)->toContain('Upload Documents');
    expect($html)->toContain('document');
    expect($html)->toContain('multiple');
    expect($html)->toContain('accept=".pdf,.doc,.docx"');
    expect($html)->toContain('Accepted formats: PDF, DOC, DOCX');
    expect($html)->toContain('custom-document-uploader');
    expect($html)->toContain('URL.createObjectURL');
    expect($html)->toContain('clearFiles');
});

it('renders with proper accessibility attributes', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('tabindex');
});

it('handles image preview for image files', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" :preview="true" />');

    expect($html)->toContain("file.type.startsWith('image/')");
    expect($html)->toContain('<img');
});

it('displays file names when files are selected', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('x-show="files.length"');
    expect($html)->toContain('f.name');
});

it('renders change event handler', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('@change="addFiles');
});

it('supports different color presets', function () {
    $htmlPrimary = Blade::render('<x-bt-file-dropzone name="test_file" color="primary" />');
    expect($htmlPrimary)->toContain('type="file"');

    $htmlDanger = Blade::render('<x-bt-file-dropzone name="test_file" color="danger" />');
    expect($htmlDanger)->toContain('type="file"');
});

it('handles DataTransfer API for file management', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)->toContain('DataTransfer');
    expect($html)->toContain('dt.items.add');
});
