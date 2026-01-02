<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic table component', function () {
    $html = Blade::render('<x-bt-table :items="[]" />');

    expect($html)->toContain('table');
});

it('renders with array data', function () {
    $items = [
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
    ];

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Alice');
    expect($html)->toContain('Bob');
});

it('renders with collection data', function () {
    $items = collect([
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
    ]);

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Alice');
    expect($html)->toContain('Bob');
});

it('auto-generates columns from data keys', function () {
    $items = [
        ['id' => 1, 'email' => 'alice@example.com'],
    ];

    $html = Blade::render('<x-bt-table :items="$items" />', ['items' => $items]);

    expect($html)->toContain('email');
});

it('supports custom columns definition', function () {
    $items = [
        ['id' => 1, 'username' => 'alice', 'role' => 'admin'],
    ];
    $columns = ['username' => 'User', 'role' => 'Role'];

    $html = Blade::render('<x-bt-table :items="$items" :columns="$columns" />', ['items' => $items, 'columns' => $columns]);

    expect($html)->toContain('User');
    expect($html)->toContain('Role');
    expect($html)->toContain('alice');
});

it('supports color presets', function () {
    $html = Blade::render('<x-bt-table :items="[]" color="primary" />');

    expect($html)->toContain('table');
});

it('supports paginated prop', function () {
    $html = Blade::render('<x-bt-table :items="[]" :paginated="true" />');

    expect($html)->toContain('table');
});

it('supports searchable prop', function () {
    $html = Blade::render('<x-bt-table :items="[]" :searchable="true" />');

    expect($html)->toContain('table');
});

it('normalizes indexed arrays', function () {
    $items = [
        ['Alice', 'alice@example.com'],
        ['Bob', 'bob@example.com'],
    ];
    $columns = ['Name', 'Email'];

    $html = Blade::render('<x-bt-table :items="$items" :columns="$columns" />', ['items' => $items, 'columns' => $columns]);

    expect($html)->toContain('Alice');
    expect($html)->toContain('alice@example.com');
});
