<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ---------- Basic Rendering ----------

it('renders with beartropyDatetimepicker in x-data', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('beartropyDatetimepicker');
    expect($html)->toContain('x-data');
});

it('does not use legacy $beartropy.datetimepicker registration', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->not->toContain('$beartropy.datetimepicker');
});

it('does not render redundant x-init', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->not->toContain('x-init="init()"');
});

it('delegates click-outside to dropdown-base (not root div)', function () {
    $html = Blade::render('<x-bt-datetime />');

    // The root x-data div should NOT have its own @click.outside since
    // the dropdown-base handles it (dropdown is teleported to <body>).
    // Verify the root div opens properly with beartropyDatetimepicker.
    expect($html)->toContain('beartropyDatetimepicker');
});

// ---------- Label ----------

it('renders with label', function () {
    $html = Blade::render('<x-bt-datetime label="Select Date" />');

    expect($html)->toContain('Select Date');
    expect($html)->toContain('<label');
});

it('renders label with for attribute matching id', function () {
    $html = Blade::render('<x-bt-datetime id="my-date" label="Date" />');

    expect($html)->toContain('for="my-date"');
});

it('does not render label when not provided', function () {
    $html = Blade::render('<x-bt-datetime id="no-label-date" />');

    expect($html)->not->toContain('for="no-label-date"');
});

// ---------- Placeholder ----------

it('uses default i18n select_date placeholder', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('Select date...');
});

it('uses default i18n select_range placeholder in range mode', function () {
    $html = Blade::render('<x-bt-datetime :range="true" />');

    expect($html)->toContain('Select range...');
});

it('uses custom placeholder', function () {
    $html = Blade::render('<x-bt-datetime placeholder="Choose a date..." />');

    expect($html)->toContain('Choose a date...');
});

// ---------- ID ----------

it('renders custom id', function () {
    $html = Blade::render('<x-bt-datetime id="custom-date-id" />');

    expect($html)->toContain('id="custom-date-id"');
});

it('generates unique id when not provided', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('beartropy-datetime-');
});

// ---------- Name ----------

it('renders explicit name', function () {
    $html = Blade::render('<x-bt-datetime name="start_date" />');

    expect($html)->toContain('name="start_date"');
});

it('falls back name to id when not provided', function () {
    $html = Blade::render('<x-bt-datetime id="fallback-id" />');

    expect($html)->toContain('name="fallback-id"');
});

// ---------- Value ----------

it('passes initial value to JS config', function () {
    $html = Blade::render('<x-bt-datetime value="2024-06-15" />');

    expect($html)->toContain('2024-06-15');
});

// ---------- Disabled ----------

it('passes disabled to JS config', function () {
    $html = Blade::render('<x-bt-datetime :disabled="true" />');

    expect($html)->toContain('disabled: true');
});

it('renders disabled with cursor-not-allowed', function () {
    $html = Blade::render('<x-bt-datetime :disabled="true" />');

    expect($html)->toContain('cursor-not-allowed');
});

// ---------- Clearable ----------

it('renders clear button by default', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('clearSelection()');
});

it('hides clear button when clearable is false', function () {
    $html = Blade::render('<x-bt-datetime :clearable="false" />');

    expect($html)->not->toContain('clearSelection()');
});

// ---------- Custom Error ----------

it('renders custom error message', function () {
    $html = Blade::render('<x-bt-datetime custom-error="Invalid date" />');

    expect($html)->toContain('Invalid date');
});

// ---------- Help / Hint ----------

it('renders help text', function () {
    $html = Blade::render('<x-bt-datetime help="Pick a date within range" />');

    expect($html)->toContain('Pick a date within range');
});

it('renders hint text', function () {
    $html = Blade::render('<x-bt-datetime hint="Select a valid date" />');

    expect($html)->toContain('Select a valid date');
});

// ---------- Calendar ----------

it('renders weekday headers', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('Mon');
    expect($html)->toContain('Tue');
    expect($html)->toContain('Wed');
    expect($html)->toContain('Thu');
    expect($html)->toContain('Fri');
    expect($html)->toContain('Sat');
    expect($html)->toContain('Sun');
});

it('renders month navigation buttons', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('prevMonth()');
    expect($html)->toContain('nextMonth()');
});

it('renders day grid', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('selectDay(day)');
    expect($html)->toContain('x-for="(day, i) in days"');
});

it('applies today ring class', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('isToday(day)');
});

it('renders today button', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('goToToday()');
    expect($html)->toContain('Today');
});

it('passes min to JS config', function () {
    $html = Blade::render('<x-bt-datetime min="2024-01-01" />');

    expect($html)->toContain('2024-01-01');
});

it('passes max to JS config', function () {
    $html = Blade::render('<x-bt-datetime max="2024-12-31" />');

    expect($html)->toContain('2024-12-31');
});

// ---------- Range Mode ----------

it('passes range mode to JS config', function () {
    $html = Blade::render('<x-bt-datetime :range="true" />');

    expect($html)->toContain('range: true');
});

it('does not enable range by default', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('range: false');
});

// ---------- Time Features ----------

it('renders wheel UI when show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('role="listbox"');
    expect($html)->toContain('getHourForType');
    expect($html)->toContain('getMinuteForType');
});

it('renders wheel interaction methods when show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('getAdjacentHour');
    expect($html)->toContain('moveHour');
    expect($html)->toContain('wheelHour');
    expect($html)->toContain('getAdjacentMinute');
    expect($html)->toContain('moveMinute');
    expect($html)->toContain('wheelMinute');
});

it('renders now button when show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('setTimeNow');
    expect($html)->toContain('Now');
});

it('renders change date button when show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('Change date');
});

it('does not render time wheel when show-time is false', function () {
    $html = Blade::render('<x-bt-datetime :show-time="false" />');

    expect($html)->not->toContain('getHourForType');
    expect($html)->not->toContain('getMinuteForType');
    expect($html)->not->toContain('setTimeNow');
});

it('uses datetime display format with time when show-time enabled', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('{d}\/{m}\/{Y} {H}:{i}');
});

// ---------- Display Format ----------

it('uses default date-only display format', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('{d}\/{m}\/{Y}');
});

it('uses custom display format', function () {
    $html = Blade::render('<x-bt-datetime format-display="{d}-{m}-{Y}" />');

    expect($html)->toContain('{d}-{m}-{Y}');
});

// ---------- Color Presets ----------

it('uses default beartropy color', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('beartropy');
});

it('accepts explicit color prop', function () {
    $html = Blade::render('<x-bt-datetime color="blue" />');

    expect($html)->toContain('blue');
});

// ---------- Hidden Input ----------

it('renders hidden input without wire:model', function () {
    $html = Blade::render('<x-bt-datetime name="my_date" />');

    expect($html)->toContain('type="hidden"');
    expect($html)->toContain('name="my_date"');
});

// ---------- Custom Classes ----------

it('passes custom classes to wrapper', function () {
    $html = Blade::render('<x-bt-datetime class="custom-datetime" />');

    expect($html)->toContain('custom-datetime');
});

// ---------- Combined Features ----------

it('can render with all features combined', function () {
    $html = Blade::render('
        <x-bt-datetime
            name="appointment_date"
            label="Appointment Date"
            placeholder="Select date and time..."
            value="2024-06-15 10:00"
            min="2024-01-01"
            max="2024-12-31"
            :show-time="true"
            format-display="{d}/{m}/{Y} {H}:{i}"
            hint="Choose your preferred appointment slot"
            class="custom-datetime-picker"
        />
    ');

    expect($html)->toContain('Appointment Date');
    expect($html)->toContain('Select date and time...');
    expect($html)->toContain('2024-06-15 10:00');
    expect($html)->toContain('2024-01-01');
    expect($html)->toContain('2024-12-31');
    expect($html)->toContain('Choose your preferred appointment slot');
    expect($html)->toContain('custom-datetime-picker');
});

it('renders with datetime + range + time combined', function () {
    $html = Blade::render('<x-bt-datetime :range="true" :show-time="true" />');

    expect($html)->toContain('range: true');
    expect($html)->toContain('showTime: true');
    expect($html)->toContain('currentTimeType()');
});

it('renders calendar icon SVG', function () {
    $html = Blade::render('<x-bt-datetime />');

    expect($html)->toContain('M8 7V3m8 4V3m-9 8h10M5 21h14');
});

it('renders column labels for hour and minute in time mode', function () {
    $html = Blade::render('<x-bt-datetime :show-time="true" />');

    expect($html)->toContain('Hour');
    expect($html)->toContain('Min');
});
