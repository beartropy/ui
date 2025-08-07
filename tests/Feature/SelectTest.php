<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a basic select', function () {
    $html = Blade::render('<x-select :options="[1 => \'Uno\', 2 => \'Dos\']" />');
    expect($html)->toContain('Uno');
    expect($html)->toContain('Dos');
});

it('renders select with label and placeholder', function () {
    $html = Blade::render('<x-select label="País" placeholder="Elegí un país" :options="[1 => \'Argentina\']" />');
    expect($html)->toContain('País');
    expect($html)->toContain('Elegí un país');
});

it('renders with magic color and size', function () {
    $html = Blade::render('<x-select red lg :options="[1 => \'A\']" />');
    expect($html)->toContain('red');
    expect($html)->toContain('lg');
});

it('renders start slot content', function () {
    $html = Blade::render('<x-select :options="[1 => \'A\']">
        <x-slot name="start"><span class="icon-start">S</span></x-slot>
    </x-select>');
    expect($html)->toContain('icon-start');
});

it('renders clearable button', function () {
    $html = Blade::render('<x-select clearable :options="[1 => \'A\']" initialValue="1" />');
    expect($html)->toContain('svg');
    expect($html)->toContain('Limpiar selección');
});

it('renders as multi select and chips', function () {
    $html = Blade::render('<x-select multiple :options="[1 => \'A\', 2 => \'B\']" :initialValue="[1,2]" />');
    expect($html)->toContain('A');
    expect($html)->toContain('B');
    expect($html)->toContain('chip'); // si tu clase para chips incluye 'chip'
});

it('renders helpers: avatar, icon, description', function () {
    $html = Blade::render('<x-select :options="[
        1 => [\'label\' => \'Uno\', \'avatar\' => \'X\', \'description\' => \'desc\' ],
        2 => [\'label\' => \'Dos\', \'icon\' => \'🌎\', \'description\' => \'desc2\']
    ]" />');
    expect($html)->toContain('Uno');
    expect($html)->toContain('Dos');
    expect($html)->toContain('desc');
    expect($html)->toContain('desc2');
    expect($html)->toContain('avatar');
    expect($html)->toContain('icon');
});

it('renders badge +N when multi select chips exceed maxChips', function () {
    $html = Blade::render('<x-select multiple :options="[1,2,3,4,5]" :initialValue="[1,2,3,4,5]" />');
    expect($html)->toContain('+');
    expect($html)->toContain('badge');
});

it('renders error message if error is set', function () {
    $html = Blade::render('<x-select label="País" name="pais" :custom-error="\'Error país\'" :options="[1 => \'Argentina\']" />');
    expect($html)->toContain('Error país');
});

it('matches the expected snapshot for a complex select', function () {
    $html = Blade::render('
        <x-select
            id="complex-select"
            label="País"
            red
            lg
            clearable
            multiple
            :options="[
                1 => [\'label\' => \'Uno\', \'avatar\' => \'A\', \'description\' => \'Desc A\'],
                2 => [\'label\' => \'Dos\', \'icon\' => \'🌎\', \'description\' => \'Desc B\']
            ]"
            :initialValue="[1,2]"
        >
            <x-slot name="start"><span class="snap-start">START</span></x-slot>
            <x-slot name="end"><span class="snap-end">END</span></x-slot>
        </x-select>
    ');
    expect($html)->toMatchSnapshot();
});
