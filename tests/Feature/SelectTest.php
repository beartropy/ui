<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic select component', function () {
    $options = ['option1' => 'Option 1', 'option2' => 'Option 2'];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('x-data');
    expect($html)->toContain('Seleccionar...');
});

it('can render with simple array options', function () {
    $options = ['Apple', 'Banana', 'Orange'];
    $html = Blade::render('<x-bt-select name="fruits" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Apple');
    expect($html)->toContain('Banana');
    expect($html)->toContain('Orange');
});

it('can render with associative array options', function () {
    $options = [
        '1' => 'First Option',
        '2' => 'Second Option',
        '3' => 'Third Option',
    ];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('First Option');
    expect($html)->toContain('Second Option');
    expect($html)->toContain('Third Option');
});

it('can render with label', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" label="Select Label" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Select Label');
    expect($html)->toContain('<label');
});

it('can render with custom placeholder', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" placeholder="Choose an option..." :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Choose an option...');
});

it('can render in multiple mode', function () {
    $options = ['1' => 'Option 1', '2' => 'Option 2'];
    $html = Blade::render('<x-bt-select name="test_select" :multiple="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('isMulti: true');
});

it('can render in single mode by default', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('isMulti: false');
});

it('can render with searchable enabled by default', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Buscar...');
    expect($html)->toContain('x-model="search"');
});

it('can render without search when searchable is false', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :searchable="false" :options="$options" />', ['options' => $options]);

    expect($html)->not->toContain('Buscar...');
});

it('can render with clearable button', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :clearable="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('clearValue()');
    expect($html)->toContain('Limpiar selección');
});

it('can render without clearable button', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :clearable="false" :options="$options" />', ['options' => $options]);

    expect($html)->not->toContain('Limpiar selección');
});

it('can render with initial value', function () {
    $options = ['1' => 'Option 1', '2' => 'Option 2'];
    $html = Blade::render('<x-bt-select name="test_select" initial-value="1" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('can render with initial multiple values', function () {
    $options = ['1' => 'Option 1', '2' => 'Option 2', '3' => 'Option 3'];
    $initialValue = ['1', '2'];
    $html = Blade::render('<x-bt-select name="test_select" :multiple="true" :initial-value="$initial" :options="$options" />', [
        'options' => $options,
        'initial' => $initialValue,
    ]);

    expect($html)->toContain('isMulti: true');
    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
});

it('shows validation errors with custom-error', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" custom-error="This field is required" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('This field is required');
    expect($html)->toContain('hasFieldError: true');
});

it('can render with hint text', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" hint="This is a hint" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('This is a hint');
});

it('can render with remote options', function () {
    $html = Blade::render('<x-bt-select name="test_select" :remote="true" remote-url="/api/options" />');

    expect($html)->toContain('remoteUrl: \'/api/options\'');
    expect($html)->toContain('fetchOptions');
});

it('can render with custom per-page value', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :per-page="25" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('perPage: 25');
});

it('can render with autosave enabled', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :autosave="true" autosave-key="user_preference" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('autosave: true');
    expect($html)->toContain('autosaveKey: \'user_preference\'');
    expect($html)->toContain('triggerAutosave()');
});

it('can render with custom autosave method', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :autosave="true" autosave-method="customSave" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('autosaveMethod: \'customSave\'');
});

it('can render with custom autosave debounce', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :autosave="true" :autosave-debounce="500" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('autosaveDebounce: 500');
});

it('can render with complex options including descriptions', function () {
    $options = [
        ['value' => '1', 'label' => 'Option 1', 'description' => 'First option description'],
        ['value' => '2', 'label' => 'Option 2', 'description' => 'Second option description'],
    ];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Option 1');
    expect($html)->toContain('Option 2');
    expect($html)->toContain('First option description');
    expect($html)->toContain('Second option description');
});

it('can render with custom option mappings', function () {
    $options = [
        ['id' => '1', 'name' => 'Custom Name 1'],
        ['id' => '2', 'name' => 'Custom Name 2'],
    ];
    $html = Blade::render('<x-bt-select name="test_select" option-value="id" option-label="name" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('Custom Name 1');
    expect($html)->toContain('Custom Name 2');
});

it('can render with empty options', function () {
    $html = Blade::render('<x-bt-select name="test_select" :options="[]" />');

    expect($html)->toContain('No se encontraron opciones');
});

it('can render with custom empty message', function () {
    $html = Blade::render('<x-bt-select name="test_select" :options="[]" empty-message="No items available" />');

    expect($html)->toContain('No items available');
});

it('disables clearable and searchable when empty', function () {
    $html = Blade::render('<x-bt-select name="test_select" :options="[]" />');

    expect($html)->not->toContain('Limpiar selección');
    expect($html)->not->toContain('Buscar...');
});

it('can render with different sizes', function () {
    $options = ['1' => 'Option 1'];

    $htmlSm = Blade::render('<x-bt-select name="test_select" size="sm" :options="$options" />', ['options' => $options]);
    expect($htmlSm)->toContain('h-8'); // sm size class

    $htmlMd = Blade::render('<x-bt-select name="test_select" size="md" :options="$options" />', ['options' => $options]);
    expect($htmlMd)->toContain('h-10'); // md size class

    $htmlLg = Blade::render('<x-bt-select name="test_select" size="lg" :options="$options" />', ['options' => $options]);
    expect($htmlLg)->toContain('h-12'); // lg size class
});

it('generates unique select id when not provided', function () {
    $options = ['1' => 'Option 1'];
    $html1 = Blade::render('<x-bt-select name="test_select_1" :options="$options" />', ['options' => $options]);
    $html2 = Blade::render('<x-bt-select name="test_select_2" :options="$options" />', ['options' => $options]);

    expect($html1)->toContain('id="select-');
    expect($html2)->toContain('id="select-');
    expect($html1)->not->toBe($html2);
});

it('can render with custom id', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" id="custom-select-id" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('id="custom-select-id"');
});

it('renders chevron icon for dropdown toggle', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('rotate-180');
    expect($html)->toContain('toggle()');
});

it('renders chips for multiple selected values', function () {
    $options = ['1' => 'Option 1', '2' => 'Option 2'];
    $html = Blade::render('<x-bt-select name="test_select" :multiple="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('beartropy-select-chip');
    expect($html)->toContain('visibleChips()');
});

it('renders badge for hidden chips count', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :multiple="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('beartropy-select-badge');
    expect($html)->toContain('hiddenCount()');
});

it('renders checkboxes for multiple mode options', function () {
    $options = ['1' => 'Option 1'];
    $html = Blade::render('<x-bt-select name="test_select" :multiple="true" :options="$options" />', ['options' => $options]);

    expect($html)->toContain('type="checkbox"');
    expect($html)->toContain('form-checkbox');
});
