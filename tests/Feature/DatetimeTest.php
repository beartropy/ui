<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic datetime component', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');

    expect($html)->toContain('x-data="$beartropy.datetimepicker');
    expect($html)->toContain('name="test_date"');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" label="Select Date" />');

    expect($html)->toContain('Select Date');
    expect($html)->toContain('<label');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" placeholder="Choose a date..." />');

    expect($html)->toContain('Choose a date...');
});

it('can render with initial value', function () {
    $value = '2023-10-15';
    $html = Blade::render('<x-bt-datetime name="test_date" value="' . $value . '" />');

    expect($html)->toContain($value);
});

it('can render with show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" :show-time="true" />');

    expect($html)->toContain('name="test_date"');
});

it('can render in range mode', function () {
    $html = Blade::render('<x-bt-datetime name="test_range" :range="true" />');

    expect($html)->toContain('Seleccionar rango...');
});

it('can render with min date', function () {
    $min = '2023-01-01';
    $html = Blade::render('<x-bt-datetime name="test_date" min="' . $min . '" />');

    expect($html)->toContain($min);
});

it('can render with max date', function () {
    $max = '2023-12-31';
    $html = Blade::render('<x-bt-datetime name="test_date" max="' . $max . '" />');

    expect($html)->toContain($max);
});

it('can render with min and max dates', function () {
    $min = '2023-01-01';
    $max = '2023-12-31';
    $html = Blade::render('<x-bt-datetime name="test_date" min="' . $min . '" max="' . $max . '" />');

    expect($html)->toContain($min);
    expect($html)->toContain($max);
});

it('can render with custom display format', function () {
    $format = '{d}-{m}-{Y}';
    $html = Blade::render('<x-bt-datetime name="test_date" format-display="' . $format . '" />');

    expect($html)->toContain($format);
});

it('uses default display format for date only', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');

    expect($html)->toContain('{d}/{m}/{Y}');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" custom-error="Invalid date" />');

    expect($html)->toContain('Invalid date');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" hint="Select a valid date" />');

    expect($html)->toContain('Select a valid date');
});

it('respects Spanish locale by default', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');

    expect($html)->toContain('Seleccionar fecha...');
});

it('respects English locale', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" locale="en" />');

    expect($html)->toContain('Select date...');
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" id="custom-date-id" />');

    expect($html)->toContain('id="custom-date-id"');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-datetime name="test_date_1" />');
    $html2 = Blade::render('<x-bt-datetime name="test_date_2" />');

    expect($html1)->not->toBe($html2);
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" class="custom-datetime" />');

    expect($html)->toContain('custom-datetime');
});

it('renders flatpickr initialization', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');

    expect($html)->toContain('x-data="$beartropy.datetimepicker');
});

it('handles datetime with time enabled', function () {
    $html = Blade::render('<x-bt-datetime name="test_datetime" :show-time="true" value="2023-10-15 14:30" />');

    expect($html)->toContain('2023-10-15 14:30');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-datetime 
            name="appointment_date"
            label="Appointment Date & Time"
            placeholder="Select date and time..."
            value="2024-06-15 10:00"
            min="2024-01-01"
            max="2024-12-31"
            :show-time="true"
            format-display="{d}/{m}/{Y} {H}:{i}"
            hint="Choose your preferred appointment slot"
            locale="en"
            class="custom-datetime-picker"
        />
    ');

    expect($html)->toContain('Appointment Date');
    expect($html)->toContain('Select date and time...');
    expect($html)->toContain('2024-06-15 10:00');
    expect($html)->toContain('2024-01-01');
    expect($html)->toContain('2024-12-31');
    expect($html)->toContain('{d}/{m}/{Y} {H}:{i}');
    expect($html)->toContain('Choose your preferred appointment slot');
    expect($html)->toContain('custom-datetime-picker');
});

it('handles error state correctly', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" custom-error="Please select a valid date" />');

    expect($html)->toContain('Please select a valid date');
});

it('can render date range with custom placeholders', function () {
    $html = Blade::render('<x-bt-datetime name="test_range" :range="true" placeholder="Select date range..." />');

    expect($html)->toContain('Select date range...');
});

it('renders with calendar functionality', function () {
    $html = Blade::render('<x-bt-datetime name="test_date" />');

    expect($html)->toContain('$beartropy.datetimepicker');
});
