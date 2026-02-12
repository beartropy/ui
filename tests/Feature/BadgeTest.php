<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a span element with inline-flex', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('<span');
    expect($html)->toContain('inline-flex');
    expect($html)->toContain('items-center');
    expect($html)->toContain('rounded-xl');
});

it('renders slot content', function () {
    $html = Blade::render('<x-bt-badge>Badge Text</x-bt-badge>');

    expect($html)->toContain('Badge Text');
});

it('renders label prop', function () {
    $html = Blade::render('<x-bt-badge label="Status" />');

    expect($html)->toContain('Status');
});

it('renders label and slot combined', function () {
    $html = Blade::render('<x-bt-badge label="Pre">Post</x-bt-badge>');

    expect($html)->toContain('Pre');
    expect($html)->toContain('Post');
});

it('uses default color beartropy from config', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('bg-beartropy-100');
    expect($html)->toContain('text-beartropy-800');
});

it('uses default size sm from config', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('text-sm');
    expect($html)->toContain('px-3');
    expect($html)->toContain('py-1');
});

it('uses default variant solid', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    expect($html)->toContain('bg-beartropy-100');
    expect($html)->not->toContain('border-beartropy');
});

it('applies color via prop', function () {
    $html = Blade::render('<x-bt-badge color="red">Text</x-bt-badge>');

    expect($html)->toContain('bg-red-100');
    expect($html)->toContain('text-red-800');
});

it('applies color via magic attribute', function () {
    $html = Blade::render('<x-bt-badge green>Text</x-bt-badge>');

    expect($html)->toContain('bg-green-100');
    expect($html)->toContain('text-green-800');
});

it('supports all preset colors', function () {
    $colors = [
        'beartropy', 'red', 'orange', 'amber', 'yellow', 'lime',
        'green', 'emerald', 'teal', 'cyan', 'sky', 'blue',
        'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose',
    ];

    foreach ($colors as $color) {
        $html = Blade::render("<x-bt-badge color=\"{$color}\">Text</x-bt-badge>");
        expect($html)->toContain("bg-{$color}-100");
    }
});

it('applies soft variant', function () {
    $html = Blade::render('<x-bt-badge variant="soft" color="red">Text</x-bt-badge>');

    expect($html)->toContain('bg-red-50/80');
    expect($html)->toContain('text-red-600');
});

it('applies outline variant with border', function () {
    $html = Blade::render('<x-bt-badge variant="outline" color="blue">Text</x-bt-badge>');

    expect($html)->toContain('bg-transparent');
    expect($html)->toContain('text-blue-700');
    expect($html)->toContain('border-blue-400/60');
});

it('applies tint variant', function () {
    $html = Blade::render('<x-bt-badge variant="tint" color="green">Text</x-bt-badge>');

    expect($html)->toContain('bg-green-200/40');
    expect($html)->toContain('backdrop-blur');
});

it('applies glass variant', function () {
    $html = Blade::render('<x-bt-badge variant="glass" color="purple">Text</x-bt-badge>');

    expect($html)->toContain('backdrop-blur-xl');
    expect($html)->toContain('shadow');
    expect($html)->toContain('border-white/30');
});

it('applies variant via magic attribute', function () {
    $html = Blade::render('<x-bt-badge outline blue>Text</x-bt-badge>');

    expect($html)->toContain('bg-transparent');
    expect($html)->toContain('border-blue-400/60');
});

it('applies xs size via prop', function () {
    $html = Blade::render('<x-bt-badge size="xs">Text</x-bt-badge>');

    expect($html)->toContain('text-xs');
    expect($html)->toContain('px-2');
    expect($html)->toContain('py-0.5');
});

it('applies lg size via magic attribute', function () {
    $html = Blade::render('<x-bt-badge lg>Text</x-bt-badge>');

    expect($html)->toContain('text-lg');
    expect($html)->toContain('px-4');
});

it('supports all preset sizes', function () {
    $sizes = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
    ];

    foreach ($sizes as $size => $fontClass) {
        $html = Blade::render("<x-bt-badge size=\"{$size}\">Text</x-bt-badge>");
        expect($html)->toContain($fontClass);
    }
});

it('renders icon shorthand as left icon', function () {
    $html = Blade::render('<x-bt-badge icon="check" label="Done" />');

    expect($html)->toContain('Done');
    expect($html)->toContain('mr-1');
});

it('renders iconLeft', function () {
    $html = Blade::render('<x-bt-badge iconLeft="check">Success</x-bt-badge>');

    expect($html)->toContain('mr-1');
    expect($html)->toContain('Success');
});

it('renders iconRight', function () {
    $html = Blade::render('<x-bt-badge iconRight="arrow-right">Next</x-bt-badge>');

    expect($html)->toContain('ml-1');
    expect($html)->toContain('Next');
});

it('renders icon and label combined', function () {
    $html = Blade::render('<x-bt-badge icon="check" label="Done" green />');

    expect($html)->toContain('Done');
    expect($html)->toContain('mr-1');
    expect($html)->toContain('bg-green-100');
});

it('supports start slot', function () {
    $html = Blade::render('
        <x-bt-badge>
            <x-slot:start><span class="start-marker">S</span></x-slot:start>
            Badge
        </x-bt-badge>
    ');

    expect($html)->toContain('start-marker');
    expect($html)->toContain('Badge');
});

it('supports end slot', function () {
    $html = Blade::render('
        <x-bt-badge>
            Badge
            <x-slot:end><span class="end-marker">E</span></x-slot:end>
        </x-bt-badge>
    ');

    expect($html)->toContain('end-marker');
    expect($html)->toContain('Badge');
});

it('merges custom classes', function () {
    $html = Blade::render('<x-bt-badge class="custom-badge">Text</x-bt-badge>');

    expect($html)->toContain('custom-badge');
    expect($html)->toContain('inline-flex');
});

it('forwards data attributes', function () {
    $html = Blade::render('<x-bt-badge data-testid="my-badge">Text</x-bt-badge>');

    expect($html)->toContain('data-testid="my-badge"');
});

it('renders single span without double wrapping', function () {
    $html = Blade::render('<x-bt-badge>Text</x-bt-badge>');

    $spanCount = substr_count($html, '<span');
    expect($spanCount)->toBe(1);
});

it('renders combined features correctly', function () {
    $html = Blade::render('
        <x-bt-badge
            color="red"
            variant="outline"
            size="lg"
            iconLeft="check-circle"
            iconRight="arrow-right"
            label="Complete"
            class="extra"
            data-role="status"
        >
             Badge
        </x-bt-badge>
    ');

    expect($html)->toContain('Complete');
    expect($html)->toContain('Badge');
    expect($html)->toContain('bg-transparent');
    expect($html)->toContain('border-red-400/60');
    expect($html)->toContain('text-lg');
    expect($html)->toContain('mr-1');
    expect($html)->toContain('ml-1');
    expect($html)->toContain('extra');
    expect($html)->toContain('data-role="status"');
});
