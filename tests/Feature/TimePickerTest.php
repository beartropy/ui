<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ---------- Basic Rendering ----------

it('renders with beartropyTimepicker in x-data', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('beartropyTimepicker');
    expect($html)->toContain('x-data');
});

it('does not use legacy $beartropy.timepicker registration', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->not->toContain('$beartropy.timepicker');
});

// ---------- Label ----------

it('renders with label', function () {
    $html = Blade::render('<x-bt-time-picker label="Pick Time" />');

    expect($html)->toContain('Pick Time');
    expect($html)->toContain('<label');
});

it('renders label with for attribute matching id', function () {
    $html = Blade::render('<x-bt-time-picker id="my-time" label="Time" />');

    expect($html)->toContain('for="my-time"');
});

it('does not render label for attribute when label not provided', function () {
    $html = Blade::render('<x-bt-time-picker id="no-label-time" />');

    // Column labels exist but none should have for="no-label-time"
    expect($html)->not->toContain('for="no-label-time"');
});

// ---------- Placeholder ----------

it('uses default i18n select_time placeholder', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('Select time...');
});

it('uses custom placeholder', function () {
    $html = Blade::render('<x-bt-time-picker placeholder="Choose a time..." />');

    expect($html)->toContain('Choose a time...');
});

// ---------- ID ----------

it('uses custom id', function () {
    $html = Blade::render('<x-bt-time-picker id="custom-id" />');

    expect($html)->toContain('custom-id');
});

it('auto-generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-time-picker />');
    $html2 = Blade::render('<x-bt-time-picker />');

    expect($html1)->not->toBe($html2);
    expect($html1)->toContain('beartropy-timepicker-');
});

// ---------- Name ----------

it('uses explicit name in hidden input', function () {
    $html = Blade::render('<x-bt-time-picker name="appointment_time" />');

    expect($html)->toContain('name="appointment_time"');
});

it('falls back to id for name when name not provided', function () {
    $html = Blade::render('<x-bt-time-picker id="my-time" />');

    expect($html)->toContain('name="my-time"');
});

// ---------- Value ----------

it('passes initial value to JS config', function () {
    $html = Blade::render('<x-bt-time-picker value="14:30" />');

    expect($html)->toContain("'14:30'");
});

// ---------- Format ----------

it('defaults to 24h format', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('is12h: false');
});

it('detects 12h format from h:i A', function () {
    $html = Blade::render('<x-bt-time-picker format="h:i A" />');

    expect($html)->toContain('is12h: true');
});

// ---------- 12h AM/PM ----------

it('renders AM/PM buttons in 12h mode', function () {
    $html = Blade::render('<x-bt-time-picker format="h:i A" />');

    expect($html)->toContain('>AM</button>');
    expect($html)->toContain('>PM</button>');
});

it('does not render AM/PM buttons in 24h mode', function () {
    $html = Blade::render('<x-bt-time-picker format="H:i" />');

    expect($html)->not->toContain('>AM</button>');
    expect($html)->not->toContain('>PM</button>');
});

// ---------- Seconds ----------

it('renders seconds wheel when seconds is true', function () {
    $html = Blade::render('<x-bt-time-picker :seconds="true" />');

    expect($html)->toContain('wheelSecond');
    expect($html)->toContain('showSeconds: true');
    expect($html)->toContain('Sec');
});

it('does not render seconds wheel by default', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->not->toContain('wheelSecond');
    expect($html)->toContain('showSeconds: false');
});

// ---------- Disabled ----------

it('passes disabled state to JS config', function () {
    $html = Blade::render('<x-bt-time-picker :disabled="true" />');

    expect($html)->toContain('disabled: true');
});

it('defaults disabled to false', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('disabled: false');
});

// ---------- Clearable ----------

it('renders clear button by default', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('clear()');
});

it('hides clear button when clearable is false', function () {
    $html = Blade::render('<x-bt-time-picker :clearable="false" />');

    expect($html)->not->toContain('clear()');
});

// ---------- Custom Error ----------

it('shows custom error message', function () {
    $html = Blade::render('<x-bt-time-picker custom-error="Time is required" />');

    expect($html)->toContain('Time is required');
});

// ---------- Help Text ----------

it('renders help text', function () {
    $html = Blade::render('<x-bt-time-picker help="Select your time" />');

    expect($html)->toContain('Select your time');
});

// ---------- Hint Text ----------

it('renders hint text', function () {
    $html = Blade::render('<x-bt-time-picker hint="Format: HH:mm" />');

    expect($html)->toContain('Format: HH:mm');
});

// ---------- Localized Labels (no Spanish) ----------

it('renders localized hour label', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('Hour');
    expect($html)->not->toContain('Hora');
});

it('renders localized minute label', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('Min');
});

// ---------- Now Button ----------

it('renders Now button with i18n text', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('Now');
    expect($html)->toContain('setNow()');
});

// ---------- Min/Max ----------

it('passes min to JS config', function () {
    $html = Blade::render('<x-bt-time-picker min="08:00" />');

    expect($html)->toContain("min: '08:00'");
});

it('passes max to JS config', function () {
    $html = Blade::render('<x-bt-time-picker max="17:30" />');

    expect($html)->toContain("max: '17:30'");
});

it('defaults min and max to null', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('min: null');
    expect($html)->toContain('max: null');
});

// ---------- Interval ----------

it('passes interval to JS config', function () {
    $html = Blade::render('<x-bt-time-picker :interval="15" />');

    expect($html)->toContain('interval: 15');
});

it('defaults interval to 1', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('interval: 1');
});

// ---------- Clock Icon ----------

it('renders clock icon SVG', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('<svg');
    expect($html)->toContain('viewBox');
});

// ---------- Wheel Design ----------

it('uses wheel-style layout with adjacent values', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('getAdjacentHour');
    expect($html)->toContain('getAdjacentMinute');
    expect($html)->toContain('wheelHour');
    expect($html)->toContain('wheelMinute');
    expect($html)->toContain('role="listbox"');
});

it('uses time-picker preset wheel colors', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('text-beartropy-600');
    expect($html)->toContain('bg-beartropy-50');
});

// ---------- Color Presets ----------

it('uses default beartropy color', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('text-beartropy-600');
});

it('applies explicit color prop', function () {
    $html = Blade::render('<x-bt-time-picker color="blue" />');

    expect($html)->toContain('text-blue-600');
});

it('applies magic color attribute', function () {
    $html = Blade::render('<x-bt-time-picker rose />');

    expect($html)->toContain('text-rose-600');
});

// ---------- Wire Model ----------

it('renders hidden input without wire:model', function () {
    $html = Blade::render('<x-bt-time-picker name="time_field" />');

    expect($html)->toContain('type="hidden"');
    expect($html)->toContain('name="time_field"');
});

// ---------- Custom Classes ----------

it('applies custom classes to wrapper', function () {
    $html = Blade::render('<x-bt-time-picker class="custom-time-picker" />');

    expect($html)->toContain('custom-time-picker');
});

// ---------- Keyboard Navigation ----------

it('includes keyboard navigation handlers on hour wheel', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('moveHour');
    expect($html)->toContain('moveMinute');
});

it('includes keyboard navigation handlers on seconds wheel', function () {
    $html = Blade::render('<x-bt-time-picker :seconds="true" />');

    expect($html)->toContain('moveSecond');
});

// ---------- Hidden Input ----------

it('hidden input binds to Alpine value', function () {
    $html = Blade::render('<x-bt-time-picker name="t" />');

    expect($html)->toContain(':value="value"');
});

// ---------- Wheel Focusable ----------

it('hour and minute wheels are focusable', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('tabindex="0"');
});

// ---------- Click Outside Closes ----------

it('includes click outside handler', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain('@click.outside');
});

// ---------- i18n in JS Config ----------

it('passes i18n now text to JS config', function () {
    $html = Blade::render('<x-bt-time-picker />');

    expect($html)->toContain("now: 'Now'");
});

// ---------- Combined Features ----------

it('renders with all features combined', function () {
    $html = Blade::render('
        <x-bt-time-picker
            id="appt-time"
            name="appointment_time"
            label="Appointment Time"
            placeholder="Pick time..."
            value="10:00"
            format="h:i A"
            :seconds="true"
            min="08:00"
            max="17:00"
            :interval="15"
            hint="Business hours only"
            class="custom-picker"
        />
    ');

    expect($html)->toContain('Appointment Time');
    expect($html)->toContain('Pick time...');
    expect($html)->toContain('is12h: true');
    expect($html)->toContain('showSeconds: true');
    expect($html)->toContain("min: '08:00'");
    expect($html)->toContain("max: '17:00'");
    expect($html)->toContain('interval: 15');
    expect($html)->toContain('Business hours only');
    expect($html)->toContain('custom-picker');
    expect($html)->toContain('>AM</button>');
    expect($html)->toContain('Sec');
    expect($html)->toContain('Now');
    expect($html)->toContain('getAdjacentHour');
    expect($html)->toContain('wheelHour');
});
