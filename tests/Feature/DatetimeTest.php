<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render datetime component', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');
    expect($html)->toContain('x-data="$beartropy.datetimepicker');
    expect($html)->toContain('name="test_date"');
});

it('can render with show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" :show-time="true" />');
    expect($html)->toContain('true');
});

it('can render in range mode', function () {
    $html = Blade::render('<x-bt-datetime name="test_range" :range="true" />');
    expect($html)->toContain('true');
    expect($html)->toContain('Seleccionar rango...');
});

it('can render with min and max dates', function () {
    $min = '2023-01-01';
    $max = '2023-12-31';
    $html = Blade::render('<x-bt-datetime name="test_date" min="'.$min.'" max="'.$max.'" />');
    expect($html)->toContain($min);
    expect($html)->toContain($max);
});

it('can render with custom format', function () {
    $format = 'd-m-Y';
    $html = Blade::render('<x-bt-datetime name="test_date" format-display="'.$format.'" />');
    expect($html)->toContain($format);
});

it('can render with initial value', function () {
    $value = '2023-10-15';
    $html = Blade::render('<x-bt-datetime name="test_date" value="'.$value.'" />');
    expect($html)->toContain($value);
});

it('shows validation errors', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" custom-error="Invalid date" />');

    expect($html)->toContain('Invalid date');
    expect($html)->toContain('border-red-500');
});

it('respects locale', function () {
    // Default is 'es'
    $htmlEs = Blade::render('<x-bt-datetime name="test_date" />');
    expect($htmlEs)->toContain('Seleccionar fecha...');

    // English
    $htmlEn = Blade::render('<x-bt-datetime name="test_date" locale="en" />');
    expect($htmlEn)->toContain('Select date...');
});
