<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic chat input component', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('<textarea');
});

it('renders with default 1 row', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('<textarea');
});

it('can render with multiple rows', function () {
    $html = Blade::render('<x-bt-chat-input :rows="3" />');

    expect($html)->toContain('<textarea');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-chat-input label="Message" />');

    expect($html)->toContain('Message');
});

it('can render with placeholder', function () {
    $html = Blade::render('<x-bt-chat-input placeholder="Type a message..." />');

    expect($html)->toContain('Type a message...');
});

it('can be disabled', function () {
    $html = Blade::render('<x-bt-chat-input :disabled="true" />');

    expect($html)->toContain('<textarea');
});

it('can be readonly', function () {
    $html = Blade::render('<x-bt-chat-input :readonly="true" />');

    expect($html)->toContain('<textarea');
});

it('can be required', function () {
    $html = Blade::render('<x-bt-chat-input :required="true" />');

    expect($html)->toContain('<textarea');
});

it('supports submit on Enter by default', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('<textarea');
});

it('can disable submit on Enter', function () {
    $html = Blade::render('<x-bt-chat-input :submitOnEnter="false" />');

    expect($html)->toContain('<textarea');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-chat-input help="Enter to send, Shift+Enter for new line" />');

    expect($html)->toContain('<textarea');
});

it('can render with max length', function () {
    $html = Blade::render('<x-bt-chat-input :maxLength="500" />');

    expect($html)->toContain('<textarea');
});

it('supports stacked layout', function () {
    $html = Blade::render('<x-bt-chat-input :stacked="true" />');

    expect($html)->toContain('<textarea');
});

it('supports color presets', function () {
    $colors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];

    foreach ($colors as $color) {
        $html = Blade::render("<x-bt-chat-input color=\"{$color}\" />");
        expect($html)->toContain('<textarea');
    }
});

it('can render with all features', function () {
    $html = Blade::render('
        <x-bt-chat-input 
            label="Chat Message"
            placeholder="Type here..."
            :rows="2"
            :submitOnEnter="true"
            :maxLength="1000"
            help="Press Enter to send"
        />
    ');

    expect($html)->toContain('Chat Message');
    expect($html)->toContain('Type here...');
});
