<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ─── Basic Rendering ──────────────────────────────────────────────

it('renders basic file dropzone component', function () {
    $html = Blade::render('<x-bt-file-dropzone name="test_file" />');

    expect($html)
        ->toContain('beartropyFileDropzone')
        ->toContain('type="file"')
        ->toContain('name="test_file[]"');
});

it('renders with x-data Alpine component', function () {
    $html = Blade::render('<x-bt-file-dropzone name="docs" />');

    expect($html)->toContain('x-data="beartropyFileDropzone(');
});

it('generates unique id when not provided', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('beartropy-filedropzone-');
});

it('uses custom id when provided', function () {
    $html = Blade::render('<x-bt-file-dropzone id="my-dropzone" name="f1" label="Files" />');

    expect($html)
        ->toContain('id="my-dropzone"')
        ->toContain('for="my-dropzone"');
});

// ─── Label ────────────────────────────────────────────────────────

it('renders label when provided', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" label="Upload Files" />');

    expect($html)
        ->toContain('Upload Files')
        ->toContain('<label');
});

it('omits label when not provided', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->not->toContain('<label');
});

// ─── Placeholder ──────────────────────────────────────────────────

it('shows default plural placeholder text', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('Drop files here or click to select');
});

it('shows singular placeholder when multiple is false', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :multiple="false" />');

    expect($html)->toContain('Drop a file here or click to select');
});

it('shows custom placeholder when provided', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" placeholder="Drag your photos" />');

    expect($html)->toContain('Drag your photos');
});

// ─── Multiple vs Single ──────────────────────────────────────────

it('renders with multiple attribute by default', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain('multiple')
        ->toContain('name="f1[]"');
});

it('renders single mode when multiple is false', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :multiple="false" />');

    expect($html)
        ->toContain('name="f1"')
        ->not->toContain('name="f1[]"');
});

// ─── Accept Attribute ─────────────────────────────────────────────

it('renders accept attribute on file input', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" accept="image/*,.pdf" />');

    expect($html)->toContain('accept="image/*,.pdf"');
});

it('passes accept to JS config', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" accept=".jpg,.png" />');

    expect($html)->toContain("accept:");
    expect($html)->toContain('.jpg,.png');
});

// ─── Max File Size ────────────────────────────────────────────────

it('passes maxFileSize to JS config', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :max-file-size="5242880" />');

    expect($html)->toContain('maxFileSize: 5242880');
});

it('shows accept hint with max file size', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :max-file-size="10485760" />');

    expect($html)->toContain('Max 10 MB');
});

it('shows combined accept and size hint', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" accept=".pdf,.doc" :max-file-size="5242880" />');

    expect($html)
        ->toContain('PDF, DOC')
        ->toContain('Max 5 MB');
});

// ─── Max Files ────────────────────────────────────────────────────

it('passes maxFiles to JS config', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :max-files="3" />');

    expect($html)->toContain('maxFiles: 3');
});

// ─── Preview ──────────────────────────────────────────────────────

it('renders preview image template when preview is enabled', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :preview="true" />');

    expect($html)->toContain('f.preview');
});

it('omits preview image template when preview is disabled', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :preview="false" />');

    // The x-if="f.preview" template for showing image thumbnails should be absent
    expect($html)->not->toContain('x-if="f.preview"');
});

// ─── Clearable ────────────────────────────────────────────────────

it('renders clear all button when clearable', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :clearable="true" />');

    expect($html)
        ->toContain('clearFiles()')
        ->toContain('Clear all');
});

it('omits clear and remove buttons when not clearable', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :clearable="false" />');

    expect($html)
        ->not->toContain('Clear all')
        ->not->toContain('removeFile(')
        ->not->toContain('removeExisting(');
});

// ─── Disabled ─────────────────────────────────────────────────────

it('renders disabled state', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :disabled="true" />');

    expect($html)
        ->toContain('disabled')
        ->toContain('disabled: true');
});

it('applies disabled CSS classes', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :disabled="true" />');

    expect($html)->toContain('opacity-60');
});

// ─── Custom Error ─────────────────────────────────────────────────

it('shows custom error via field-help', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" custom-error="Upload failed" />');

    expect($html)->toContain('Upload failed');
});

it('applies error label class when error exists', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" label="Files" custom-error="Bad" />');

    expect($html)->toContain('text-red-500');
});

// ─── Help / Hint ──────────────────────────────────────────────────

it('renders help text', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" help="Max 10MB per file" />');

    expect($html)->toContain('Max 10MB per file');
});

it('renders hint text', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" hint="Accepted: PDF, DOC" />');

    expect($html)->toContain('Accepted: PDF, DOC');
});

// ─── Existing Files ───────────────────────────────────────────────

it('passes existing files to JS config', function () {
    $existing = [['name' => 'old.pdf', 'url' => '/files/old.pdf', 'size' => 1024]];
    $html = Blade::render('<x-bt-file-dropzone name="f1" :existing-files="$existing" />', compact('existing'));

    expect($html)
        ->toContain('old.pdf')
        ->toContain('existingFiles:');
});

// ─── Drag & Drop ──────────────────────────────────────────────────

it('renders drag and drop event handlers', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain('@dragover.prevent')
        ->toContain('@drop.prevent')
        ->toContain('@dragleave.prevent');
});

it('has drag state CSS class binding', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('dragging');
});

// ─── Keyboard Accessibility ───────────────────────────────────────

it('renders keyboard accessibility attributes', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain('role="button"')
        ->toContain('tabindex="0"')
        ->toContain('@keydown.enter.prevent')
        ->toContain('@keydown.space.prevent');
});

// ─── Upload Icon ──────────────────────────────────────────────────

it('renders upload icon in empty state', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('<svg');
});

// ─── File Type Icons ──────────────────────────────────────────────

it('renders file type icon placeholders for non-image files', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain("f.file.type.startsWith('image/')")
        ->toContain("f.file.type === 'application/pdf'")
        ->toContain("f.file.type.startsWith('video/')")
        ->toContain("f.file.type.startsWith('audio/')");
});

// ─── Livewire Upload Events ───────────────────────────────────────

it('renders property-scoped Livewire upload events with wire:model', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" wire:model="photo" />');

    expect($html)
        ->toContain('livewire-upload-start')
        ->toContain('livewire-upload-finish')
        ->toContain('livewire-upload-error')
        ->toContain('livewire-upload-progress')
        ->toContain("'photo'");
});

it('scopes upload events to wire model property', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" wire:model="avatar" />');

    expect($html)->toContain("n === 'avatar'");
});

it('renders progress bar structure', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" wire:model="upload" />');

    expect($html)
        ->toContain('uploading')
        ->toContain('progress');
});

// ─── Status Icons ─────────────────────────────────────────────────

it('renders status icons for complete and error states', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain("f.status === 'complete'")
        ->toContain("f.status === 'error'");
});

// ─── File Input ───────────────────────────────────────────────────

it('renders sr-only file input', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain('class="sr-only"')
        ->toContain('type="file"');
});

it('renders change event handler on input', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('@change="addFiles');
});

// ─── Color Presets ────────────────────────────────────────────────

it('renders with default beartropy color preset', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('border-beartropy-300');
});

it('renders with different color presets', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" color="blue" />');

    expect($html)->toContain('border-blue-300');
});

it('renders with color via magic attribute', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" red />');

    expect($html)->toContain('border-red-300');
});

it('supports primary color preset', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" color="primary" />');

    expect($html)->toContain('border-beartropy-300');
});

// ─── Client-Side Validation Config ────────────────────────────────

it('passes i18n strings to JS config', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)
        ->toContain('file_too_large:')
        ->toContain('file_type_not_accepted:')
        ->toContain('max_files_exceeded:');
});

// ─── Custom Classes ───────────────────────────────────────────────

it('passes through custom classes', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" class="mt-4 custom-class" />');

    expect($html)->toContain('custom-class');
});

// ─── Add More Button ──────────────────────────────────────────────

it('renders add more button in multiple mode', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :multiple="true" />');

    expect($html)->toContain('Choose file');
});

it('omits add more button in single mode', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" :multiple="false" />');

    expect($html)->not->toContain('Choose file');
});

// ─── Client Error Display ─────────────────────────────────────────

it('renders client-side validation error container', function () {
    $html = Blade::render('<x-bt-file-dropzone name="f1" />');

    expect($html)->toContain('errors.length');
});

// ─── Combined Features ────────────────────────────────────────────

it('renders with all features combined', function () {
    $existing = [['name' => 'old.pdf', 'url' => '/files/old.pdf']];
    $html = Blade::render('
        <x-bt-file-dropzone
            name="documents"
            label="Upload Documents"
            color="blue"
            :multiple="true"
            accept=".pdf,.doc,.docx"
            :max-file-size="10485760"
            :max-files="5"
            :preview="true"
            :clearable="true"
            hint="PDF, DOC, DOCX only"
            :existing-files="$existing"
        />
    ', compact('existing'));

    expect($html)
        ->toContain('Upload Documents')
        ->toContain('border-blue-300')
        ->toContain('multiple')
        ->toContain('accept=".pdf,.doc,.docx"')
        ->toContain('maxFileSize: 10485760')
        ->toContain('maxFiles: 5')
        ->toContain('clearFiles')
        ->toContain('PDF, DOC, DOCX only')
        ->toContain('old.pdf');
});
