<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// ─── Basic Rendering ──────────────────────────────────────────────

it('renders with Alpine beartropyChatInput component', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)
        ->toContain('x-data="beartropyChatInput(')
        ->toContain('<textarea')
        ->toContain('x-ref="textarea"');
});

it('does not contain inline Alpine init block', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)
        ->not->toContain('x-data=\'{')
        ->not->toContain('init()');
});

// ─── ID / Name ────────────────────────────────────────────────────

it('generates unique id when not provided', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('id="beartropy-chat-input-');
});

it('uses custom id when provided', function () {
    $html = Blade::render('<x-bt-chat-input id="my-chat" />');

    expect($html)->toContain('id="my-chat"');
});

it('uses explicit name when provided', function () {
    $html = Blade::render('<x-bt-chat-input name="message" />');

    expect($html)->toContain('name="message"');
});

it('falls back name to id when name not provided', function () {
    $html = Blade::render('<x-bt-chat-input id="chat-box" />');

    expect($html)->toContain('name="chat-box"');
});

// ─── Label ────────────────────────────────────────────────────────

it('renders label when provided', function () {
    $html = Blade::render('<x-bt-chat-input label="Message" />');

    expect($html)
        ->toContain('Message')
        ->toContain('<label');
});

it('links label to textarea via for attribute', function () {
    $html = Blade::render('<x-bt-chat-input id="my-input" label="Chat" />');

    expect($html)->toContain('for="my-input"');
});

it('omits label when not provided', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->not->toContain('<label');
});

it('shows required asterisk when required with label', function () {
    $html = Blade::render('<x-bt-chat-input label="Message" :required="true" />');

    expect($html)->toContain('text-red-500');
});

// ─── Placeholder ──────────────────────────────────────────────────

it('uses i18n default placeholder when none provided', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('Type a message...');
});

it('uses custom placeholder when provided', function () {
    $html = Blade::render('<x-bt-chat-input placeholder="Say something..." />');

    expect($html)->toContain('Say something...');
});

// ─── Disabled / Readonly / Required ───────────────────────────────

it('renders disabled attribute on textarea', function () {
    $html = Blade::render('<x-bt-chat-input :disabled="true" />');

    expect($html)->toMatch('/textarea[^>]*\bdisabled\b/');
});

it('renders readonly attribute on textarea', function () {
    $html = Blade::render('<x-bt-chat-input :readonly="true" />');

    expect($html)->toMatch('/textarea[^>]*\breadonly\b/');
});

it('renders required attribute on textarea', function () {
    $html = Blade::render('<x-bt-chat-input :required="true" />');

    expect($html)->toMatch('/textarea[^>]*\brequired\b/');
});

it('does not render disabled when false', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->not->toMatch('/textarea[^>]*\bdisabled\b/');
});

// ─── MaxLength ────────────────────────────────────────────────────

it('renders maxlength attribute when provided', function () {
    $html = Blade::render('<x-bt-chat-input :maxLength="500" />');

    expect($html)->toContain('maxlength="500"');
});

it('omits maxlength when not provided', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->not->toContain('maxlength');
});

// ─── Stacked Layout ───────────────────────────────────────────────

it('passes stacked false by default to Alpine config', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)
        ->toContain('isSingleLine: true')
        ->toContain('stacked: false');
});

it('passes stacked true when enabled', function () {
    $html = Blade::render('<x-bt-chat-input :stacked="true" />');

    expect($html)
        ->toContain('isSingleLine: false')
        ->toContain('stacked: true');
});

// ─── Submit on Enter / Action ─────────────────────────────────────

it('passes submitOnEnter true by default to Alpine config', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('submitOnEnter: true');
});

it('passes submitOnEnter false when disabled', function () {
    $html = Blade::render('<x-bt-chat-input :submitOnEnter="false" />');

    expect($html)->toContain('submitOnEnter: false');
});

it('passes action to Alpine config', function () {
    $html = Blade::render('<x-bt-chat-input action="sendMessage" />');

    expect($html)->toContain("action: 'sendMessage'");
});

it('renders handleEnter handler on textarea', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('handleEnter($event)');
});

// ─── Tools Slot ───────────────────────────────────────────────────

it('renders tools slot when provided', function () {
    $html = Blade::render('
        <x-bt-chat-input>
            <x-slot:tools>
                <button>Attach</button>
            </x-slot:tools>
        </x-bt-chat-input>
    ');

    expect($html)->toContain('<button>Attach</button>');
});

it('omits tools container when slot not provided', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->not->toContain('col-start-1 pl-2');
});

// ─── Footer / Actions Slot ────────────────────────────────────────

it('renders footer slot when provided', function () {
    $html = Blade::render('
        <x-bt-chat-input>
            <x-slot:footer>
                <button>Send</button>
            </x-slot:footer>
        </x-bt-chat-input>
    ');

    expect($html)->toContain('<button>Send</button>');
});

it('renders actions slot when provided', function () {
    $html = Blade::render('
        <x-bt-chat-input>
            <x-slot:actions>
                <button>Submit</button>
            </x-slot:actions>
        </x-bt-chat-input>
    ');

    expect($html)->toContain('<button>Submit</button>');
});

it('adds mb-4 wrapper class when footer is present', function () {
    $html = Blade::render('
        <x-bt-chat-input>
            <x-slot:footer>
                <span>Footer</span>
            </x-slot:footer>
        </x-bt-chat-input>
    ');

    expect($html)->toContain('mb-4');
});

// ─── Help / Hint / Error ──────────────────────────────────────────

it('renders help text via field-help component', function () {
    $html = Blade::render('<x-bt-chat-input help="Enter to send" />');

    expect($html)->toContain('Enter to send');
});

it('renders hint text via field-help component', function () {
    $html = Blade::render('<x-bt-chat-input hint="Shift+Enter for new line" />');

    expect($html)->toContain('Shift+Enter for new line');
});

it('renders custom error message via field-help', function () {
    $html = Blade::render('<x-bt-chat-input customError="Message too long" />');

    expect($html)->toContain('Message too long');
});

// ─── Border ───────────────────────────────────────────────────────

it('applies border classes when border is true', function () {
    $html = Blade::render('<x-bt-chat-input :border="true" />');

    expect($html)->toContain('focus-within:ring');
});

it('does not apply border classes when border is false', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->not->toContain('focus-within:ring');
});

// ─── Color Presets ────────────────────────────────────────────────

it('applies default primary color preset', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('bg-gray-300/50');
});

it('applies named color preset', function () {
    $html = Blade::render('<x-bt-chat-input color="blue" />');

    expect($html)->toContain('bg-blue-300/50');
});

it('applies magic color attribute', function () {
    $html = Blade::render('<x-bt-chat-input green />');

    expect($html)->toContain('bg-green-300/50');
});

it('applies error label class when error present', function () {
    $html = Blade::render('<x-bt-chat-input label="Msg" customError="Bad" />');

    expect($html)->toContain('text-red-500');
});

// ─── Custom Classes / Attribute Forwarding ────────────────────────

it('merges custom classes on wrapper', function () {
    $html = Blade::render('<x-bt-chat-input class="my-custom-class" />');

    expect($html)->toContain('my-custom-class');
});

it('forwards extra attributes to wrapper div', function () {
    $html = Blade::render('<x-bt-chat-input data-testid="chat" />');

    expect($html)->toContain('data-testid="chat"');
});

// ─── Textarea hardcoded attributes ────────────────────────────────

it('always renders rows=1 on textarea', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('rows="1"');
});

it('applies field-sizing content style', function () {
    $html = Blade::render('<x-bt-chat-input />');

    expect($html)->toContain('field-sizing: content');
});

// ─── Combined Features ────────────────────────────────────────────

it('renders with all features combined', function () {
    $html = Blade::render('
        <x-bt-chat-input
            id="full-chat"
            name="user_message"
            label="Chat Message"
            placeholder="Write here..."
            :disabled="false"
            :required="true"
            :maxLength="1000"
            :stacked="true"
            :submitOnEnter="true"
            action="send"
            :border="true"
            help="Press Enter to send"
            color="blue"
        >
            <x-slot:tools>
                <button>+</button>
            </x-slot:tools>
            <x-slot:actions>
                <button>Send</button>
            </x-slot:actions>
        </x-bt-chat-input>
    ');

    expect($html)
        ->toContain('id="full-chat"')
        ->toContain('name="user_message"')
        ->toContain('Chat Message')
        ->toContain('Write here...')
        ->toContain('maxlength="1000"')
        ->toContain('stacked: true')
        ->toContain("action: 'send'")
        ->toContain('bg-blue-300/50')
        ->toContain('<button>+</button>')
        ->toContain('<button>Send</button>')
        ->toContain('Press Enter to send');
});
