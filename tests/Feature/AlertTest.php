<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a basic alert with role and Alpine state', function () {
    $html = Blade::render('<x-bt-alert>Hello world</x-bt-alert>');

    expect($html)
        ->toContain('role="alert"')
        ->toContain('x-data=')
        ->toContain('x-show="open"')
        ->toContain('x-transition')
        ->toContain('Hello world');
});

it('renders slot content inside the content wrapper', function () {
    $html = Blade::render('<x-bt-alert success>Check your email for confirmation.</x-bt-alert>');

    expect($html)->toContain('Check your email for confirmation.');
});

it('renders title when provided', function () {
    $html = Blade::render('<x-bt-alert success title="All done!">Your changes were saved.</x-bt-alert>');

    expect($html)
        ->toContain('All done!')
        ->toContain('Your changes were saved.');
});

it('omits title div when no title is provided', function () {
    $html = Blade::render('<x-bt-alert success>No title here.</x-bt-alert>');

    // The title div uses the preset 'title' class — should not appear
    expect($html)
        ->toContain('No title here.')
        ->not->toContain('font-bold mb-1 text-lg leading-tight');
});

it('renders custom icon when provided', function () {
    $html = Blade::render('<x-bt-alert icon="shield-check" red>Secured.</x-bt-alert>');

    // Custom icon should render inside the icon wrapper
    expect($html)->toContain('Secured.');
    // The icon wrapper should be present
    expect($html)->toContain('flex-shrink-0');
});

it('renders preset icon for success color', function () {
    $html = Blade::render('<x-bt-alert success>Done.</x-bt-alert>');

    // success preset has icon: check-circle — icon wrapper should render
    expect($html)->toContain('flex-shrink-0');
});

it('renders preset icon for info color', function () {
    $html = Blade::render('<x-bt-alert info>Note.</x-bt-alert>');

    expect($html)->toContain('flex-shrink-0');
});

it('renders preset icon for warning color', function () {
    $html = Blade::render('<x-bt-alert warning>Caution.</x-bt-alert>');

    expect($html)->toContain('flex-shrink-0');
});

it('renders preset icon for error color', function () {
    $html = Blade::render('<x-bt-alert error>Failed.</x-bt-alert>');

    expect($html)->toContain('flex-shrink-0');
});

it('hides icon when noIcon is true', function () {
    $html = Blade::render('<x-bt-alert :noIcon="true" success>No icon.</x-bt-alert>');

    // Icon wrapper should not be present
    expect($html)
        ->toContain('No icon.')
        ->not->toContain('flex-shrink-0');
});

it('does not render empty icon wrapper for colors without preset icon', function () {
    // Named colors like "red" have icon: '' — should NOT render the wrapper
    $html = Blade::render('<x-bt-alert red>Red alert.</x-bt-alert>');

    expect($html)
        ->toContain('Red alert.')
        ->not->toContain('flex-shrink-0');
});

it('renders dismiss button when dismissible', function () {
    $html = Blade::render('<x-bt-alert :dismissible="true" success>Dismiss me.</x-bt-alert>');

    expect($html)
        ->toContain('@click="open = false"')
        ->toContain('aria-label=')
        ->toContain('type="button"');
});

it('does not render dismiss button by default', function () {
    $html = Blade::render('<x-bt-alert success>Not dismissible.</x-bt-alert>');

    expect($html)->not->toContain('@click="open = false"');
});

it('applies success color CSS classes', function () {
    $html = Blade::render('<x-bt-alert success>OK</x-bt-alert>');

    expect($html)
        ->toContain('bg-green-50')
        ->toContain('text-green-800')
        ->toContain('border-green-300');
});

it('applies info color CSS classes', function () {
    $html = Blade::render('<x-bt-alert info>Note</x-bt-alert>');

    expect($html)
        ->toContain('bg-blue-50')
        ->toContain('text-blue-800')
        ->toContain('border-blue-300');
});

it('applies warning color CSS classes', function () {
    $html = Blade::render('<x-bt-alert warning>Watch out</x-bt-alert>');

    expect($html)
        ->toContain('bg-yellow-50')
        ->toContain('text-yellow-800')
        ->toContain('border-yellow-300');
});

it('applies error color CSS classes', function () {
    $html = Blade::render('<x-bt-alert error>Oops</x-bt-alert>');

    expect($html)
        ->toContain('bg-red-50')
        ->toContain('text-red-800')
        ->toContain('border-red-300');
});

it('applies named color CSS classes via prop', function () {
    $html = Blade::render('<x-bt-alert color="blue">Blue alert</x-bt-alert>');

    expect($html)
        ->toContain('bg-blue-50')
        ->toContain('text-blue-800')
        ->toContain('border-blue-300');
});

it('applies named color CSS classes via magic attribute', function () {
    $html = Blade::render('<x-bt-alert purple>Purple alert</x-bt-alert>');

    expect($html)
        ->toContain('bg-purple-50')
        ->toContain('text-purple-800')
        ->toContain('border-purple-300');
});

it('appends custom classes', function () {
    $html = Blade::render('<x-bt-alert class="my-custom-class" success>Styled</x-bt-alert>');

    expect($html)->toContain('my-custom-class');
});

it('renders with all features combined', function () {
    $html = Blade::render('
        <x-bt-alert
            title="Important Notice"
            icon="exclamation-triangle"
            :dismissible="true"
            class="custom-combined"
            warning
        >
            This is a complete alert.
        </x-bt-alert>
    ');

    expect($html)
        ->toContain('Important Notice')
        ->toContain('This is a complete alert.')
        ->toContain('@click="open = false"')
        ->toContain('custom-combined')
        ->toContain('role="alert"')
        ->toContain('bg-yellow-50');
});

it('applies beartropy color CSS classes', function () {
    $html = Blade::render('<x-bt-alert beartropy>Brand alert</x-bt-alert>');

    expect($html)
        ->toContain('bg-beartropy-50')
        ->toContain('text-beartropy-800')
        ->toContain('border-beartropy-300');
});
