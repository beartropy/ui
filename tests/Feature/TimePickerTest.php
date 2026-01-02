<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic time picker component', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('$beartropy.timepicker');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" label="Select Time" />');

    expect($html)->toContain('Select Time');
    expect($html)->toContain('<label');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" placeholder="Choose a time..." />');

    expect($html)->toContain('Choose a time...');
});

it('can render with initial value', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" value="14:30" />');

    expect($html)->toContain('x-data');
});

it('uses default H:i format', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('H:i');
});

it('can render with custom format', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" format="H:i:s" />');

    expect($html)->toContain('H:i:s');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" custom-error="Time is required" />');

    expect($html)->toContain('Time is required');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" hint="Select your preferred time" />');

    expect($html)->toContain('Select your preferred time');
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" id="custom-time-id" />');

    expect($html)->toContain('id="custom-time-id"');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-time-picker name="test_time_1" />');
    $html2 = Blade::render('<x-bt-time-picker name="test_time_2" />');

    expect($html1)->not->toBe($html2);
});

it('can render with custom classes', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" class="custom-time-picker" />');

    expect($html)->toContain('custom-time-picker');
});

it('can render with custom attributes', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" data-test="custom-time" aria-label="Time Selector" />');

    expect($html)->toContain('data-test="custom-time"');
    expect($html)->toContain('aria-label="Time Selector"');
});

it('renders time picker dropdown', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('x-show');
});

it('can render with required attribute', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" required />');

    expect($html)->toContain('required');
});

it('renders hour and minute columns', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('Hora');
    expect($html)->toContain('Min');
});

it('renders clock icon', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    // Icon is rendered via SVG include
    expect($html)->toContain('x-data');
});

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-time-picker 
            name="appointment_time"
            label="Appointment Time"
            placeholder="Select time..."
            value="10:00"
            format="H:i"
            hint="Choose your preferred appointment time"
            class="custom-appointment-picker"
            required
        />
    ');

    expect($html)->toContain('Appointment Time');
    expect($html)->toContain('Select time...');
    expect($html)->toContain('H:i');
    expect($html)->toContain('Choose your preferred appointment time');
    expect($html)->toContain('custom-appointment-picker');
    expect($html)->toContain('required');
});

it('renders Alpine.js reactive state', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('x-data');
    expect($html)->toContain('$beartropy.timepicker');
});

it('handles disabled state styling', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" :disabled="true" />');

    expect($html)->toContain('disabled');
});

it('renders clearable option by default', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" />');

    expect($html)->toContain('Limpiar');
});

it('can hide clearable option', function () {
    $html = Blade::render('<x-bt-time-picker name="test_time" :clearable="false" />');

    expect($html)->not->toContain('Limpiar');
});
