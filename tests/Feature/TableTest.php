<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders an empty table without errors', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)
        ->toContain('<table')
        ->toContain('</table>');
});

it('renders Alpine x-data with beartropyTable', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)->toContain('$beartropy.beartropyTable(');
});

// --- Data Passing ---

it('passes array data into Alpine JSON', function () {
    $items = [
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
    ];

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Alice')
        ->toContain('Bob');
});

it('passes Collection data into Alpine JSON', function () {
    $items = collect([
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
    ]);

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Alice')
        ->toContain('Bob');
});

// --- Columns ---

it('auto-generates columns from data keys', function () {
    $items = [
        ['id' => 1, 'email' => 'alice@example.com'],
    ];

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('\u0022id\u0022')
        ->toContain('\u0022email\u0022');
});

it('accepts custom column labels', function () {
    $items = [
        ['id' => 1, 'username' => 'alice', 'role' => 'admin'],
    ];
    $columns = ['username' => 'User', 'role' => 'Role'];

    $html = Blade::render('<x-bt-table :items="$items" :columns="$columns" />', [
        'items' => $items,
        'columns' => $columns,
    ]);

    expect($html)
        ->toContain('\u0022User\u0022')
        ->toContain('\u0022Role\u0022');
});

// --- Indexed Array Normalization ---

it('normalizes indexed arrays using column labels', function () {
    $items = [
        ['Alice', 'alice@example.com'],
        ['Bob', 'bob@example.com'],
    ];
    $columns = ['Name', 'Email'];

    $html = Blade::render('<x-bt-table :items="$items" :columns="$columns" />', [
        'items' => $items,
        'columns' => $columns,
    ]);

    expect($html)
        ->toContain('Alice')
        ->toContain('alice@example.com');
});

// --- Boolean Props ---

it('passes sortable prop as Alpine config', function () {
    $html = Blade::render('<x-bt-table :items="[]" :sortable="false" />');

    expect($html)->toContain('sortable: false');
});

it('passes searchable prop as Alpine config', function () {
    $html = Blade::render('<x-bt-table :items="[]" :searchable="false" />');

    expect($html)->toContain('searchable: false');
});

it('passes paginated prop as Alpine config', function () {
    $html = Blade::render('<x-bt-table :items="[]" :paginated="false" />');

    expect($html)->toContain('paginated: false');
});

it('defaults boolean props to true', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)
        ->toContain('sortable: true')
        ->toContain('searchable: true')
        ->toContain('paginated: true');
});

// --- XSS Hardening ---

it('uses x-text by default for cell content', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)
        ->toContain('x-text="row[col]"')
        ->not->toContain('x-html="row[col]"');
});

it('uses x-html when allowHtml is true', function () {
    $html = Blade::render('<x-bt-table :items="$items" :allow-html="true" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)
        ->toContain('x-html="row[col]"')
        ->not->toContain('x-text="row[col]"');
});

// --- Striped Rows ---

it('does not add striped classes by default', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->not->toContain('even:bg-gray-50');
});

it('adds striped classes when striped is true', function () {
    $html = Blade::render('<x-bt-table :items="$items" :striped="true" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)
        ->toContain('even:bg-gray-50')
        ->toContain('dark:even:bg-gray-800/50');
});

// --- Localization ---

it('renders localized empty state text', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)->toContain(__('beartropy-ui::ui.no_results'));
});

it('renders localized pagination labels', function () {
    $items = [['id' => 1]];
    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain(__('beartropy-ui::ui.table_showing'))
        ->toContain(__('beartropy-ui::ui.table_to'))
        ->toContain(__('beartropy-ui::ui.table_of'))
        ->toContain(__('beartropy-ui::ui.table_results'));
});

it('renders localized search placeholder', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)->toContain(__('beartropy-ui::ui.search'));
});

// --- Accessibility ---

it('adds role=columnheader to th elements', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('role="columnheader"');
});

it('adds aria-sort attribute to sortable headers', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('aria-sort');
});

it('adds role=status to empty state cell', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)->toContain('role="status"');
});

it('adds type=button to pagination buttons', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    // All buttons should have type="button"
    expect($html)->toContain('type="button"');
});

it('adds aria-label to previous and next pagination buttons', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)
        ->toContain('aria-label="' . __('beartropy-ui::ui.table_previous') . '"')
        ->toContain('aria-label="' . __('beartropy-ui::ui.table_next') . '"');
});

it('adds aria-hidden to pagination SVG icons', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('aria-hidden="true"');
});

it('adds aria-current to active page button via Alpine binding', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain(":aria-current=\"page === p ? 'page' : undefined\"");
});

it('adds aria-hidden to sort direction indicators', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    // The sort arrow span has aria-hidden
    expect($html)->toContain('aria-hidden="true"');
});

it('adds aria-label to pagination nav', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('aria-label="' . __('beartropy-ui::ui.table_results') . '"');
});

// --- Pagination Structure ---

it('renders pagination nav element', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('<nav');
});

it('renders previous and next buttons with SVG', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)
        ->toContain('@click="prevPage"')
        ->toContain('@click="nextPage"');
});

it('renders page number buttons', function () {
    $html = Blade::render('<x-bt-table :items="$items" />', [
        'items' => [['id' => 1]],
    ]);

    expect($html)->toContain('@click="gotoPage(p)"');
});

// --- Color Preset ---

it('accepts a color prop', function () {
    $html = Blade::render('<x-bt-table :items="[]" color="beartropy" />');

    expect($html)->toContain('<table');
});

// --- Per Page ---

it('passes custom perPage to Alpine config', function () {
    $html = Blade::render('<x-bt-table :items="[]" :per-page="25" />');

    expect($html)->toContain('perPage: 25');
});
