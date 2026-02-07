<?php

use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('can render basic toggle component', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('type="checkbox"');
    expect($html)->toContain('x-model="checked"');
});

it('can render with label', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Enable notifications" />');
    
    expect($html)->toContain('Enable notifications');
});

it('can render with slot content', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle">Custom Label</x-bt-toggle>');
    
    expect($html)->toContain('Custom Label');
});

it('prefers slot content over label attribute', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Label Text">Slot Text</x-bt-toggle>');
    
    expect($html)->toContain('Slot Text');
});

it('can render with label on the right by default', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Right Label" />');
    
    expect($html)->toContain('Right Label');
    expect($html)->toContain('inline-flex items-center gap-2');
});

it('can render with label on the left', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Left Label" label-position="left" />');
    
    expect($html)->toContain('Left Label');
    expect($html)->toContain('inline-flex items-center gap-2');
});

it('can render with label on top', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Top Label" label-position="top" />');
    
    expect($html)->toContain('Top Label');
    expect($html)->toContain('flex flex-col gap-1');
});

it('can render with label on bottom', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Bottom Label" label-position="bottom" />');
    
    expect($html)->toContain('Bottom Label');
    expect($html)->toContain('flex flex-col gap-1');
});

it('can render with disabled state', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :disabled="true" />');
    
    expect($html)->toContain('disabled');
});

it('can render with checked state', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" checked />');
    
    expect($html)->toContain('checked: true');
});

it('can render unchecked by default', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('checked: false');
});

it('shows validation errors with custom-error', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :custom-error="\'This field is required\'" />');

    expect($html)->toContain('This field is required');
    expect($html)->toContain('text-red-500');
});

it('can render with hint text', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" hint="Toggle to enable feature" />');
    
    expect($html)->toContain('Toggle to enable feature');
});

it('can render with help text', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" help="Help text here" />');

    expect($html)->toContain('Help text here');
});

it('help takes precedence over hint', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" help="Help wins" hint="Hint loses" />');

    expect($html)->toContain('Help wins');
    expect($html)->not->toContain('Hint loses');
});

it('renders checkbox as sr-only', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('sr-only');
});

it('renders with rounded-full track', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('rounded-full');
});

it('renders with cursor-pointer', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Test" />');
    
    expect($html)->toContain('cursor-pointer');
});

it('renders with select-none', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" label="Test" />');
    
    expect($html)->toContain('select-none');
});

it('can render with autosave enabled', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('autosave: true');
    expect($html)->toContain('triggerAutosave()');
});

it('can render without autosave', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('autosave: false');
});

it('can render with custom autosave method', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" autosave-method="customSave" wire:model="setting" />');
    
    expect($html)->toContain("method: 'customSave'");
});

it('uses default autosave method', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain("method: 'savePreference'");
});

it('can render with custom autosave debounce', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" :autosave-debounce="500" wire:model="setting" />');
    
    expect($html)->toContain('debounceMs: 500');
});

it('uses default autosave debounce', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('debounceMs: 300');
});

it('renders autosave indicators when enabled', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('x-show="saving"');
    expect($html)->toContain('x-show="saved"');
    expect($html)->toContain('x-show="error"');
    expect($html)->toContain('animate-spin');
});

it('does not render autosave indicators when disabled', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->not->toContain('x-show="saving"');
});

it('has Alpine.js reactive checked state', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('checked:');
    expect($html)->toContain('x-model="checked"');
});

it('has autosave state variables', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('saving: false');
    expect($html)->toContain('saved: false');
    expect($html)->toContain('error: false');
});

it('renders with transition classes', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('transition');
});

it('can render with wire:model', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" wire:model="enabled" />');
    
    expect($html)->toContain('wire:model="enabled"');
    expect($html)->toContain('$wire.entangle');
});

it('generates unique id when not provided', function () {
    $html1 = Blade::render('<x-bt-toggle name="test_toggle_1" />');
    $html2 = Blade::render('<x-bt-toggle name="test_toggle_2" />');
    
    expect($html1)->toContain('id="beartropy-toggle-');
    expect($html2)->toContain('id="beartropy-toggle-');
    expect($html1)->not->toBe($html2);
});

it('can render with custom id', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" id="custom-toggle" />');
    
    expect($html)->toContain('id="custom-toggle"');
});

it('renders with peer class for styling', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" />');
    
    expect($html)->toContain('peer');
});

it('has onChange handler for autosave', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('@change="triggerAutosave()"');
});

it('can render with different sizes', function () {
    $htmlSm = Blade::render('<x-bt-toggle name="test_toggle" size="sm" />');
    expect($htmlSm)->toContain('type="checkbox"');
    
    $htmlMd = Blade::render('<x-bt-toggle name="test_toggle" size="md" />');
    expect($htmlMd)->toContain('type="checkbox"');
    
    $htmlLg = Blade::render('<x-bt-toggle name="test_toggle" size="lg" />');
    expect($htmlLg)->toContain('type="checkbox"');
});

it('can render with different colors', function () {
    $htmlPrimary = Blade::render('<x-bt-toggle name="test_toggle" color="primary" />');
    expect($htmlPrimary)->toContain('type="checkbox"');
    
    $htmlBlue = Blade::render('<x-bt-toggle name="test_toggle" color="blue" />');
    expect($htmlBlue)->toContain('type="checkbox"');
});

it('renders autosave border states', function () {
    $html = Blade::render('<x-bt-toggle name="test_toggle" :autosave="true" wire:model="setting" />');
    
    expect($html)->toContain('border-dotted border-gray-400');
    expect($html)->toContain('border-emerald-500');
    expect($html)->toContain('border-red-500');
});

it('can render with multiple features combined', function () {
    $html = Blade::render('
        <x-bt-toggle 
            name="notifications"
            label="Enable Notifications"
            label-position="left"
            :autosave="true"
            autosave-method="saveNotificationSetting"
            :autosave-debounce="500"
            hint="Receive email notifications"
            wire:model="notificationsEnabled"
            size="lg"
            color="primary"
        />
    ');
    
    expect($html)->toContain('Enable Notifications');
    expect($html)->toContain('inline-flex items-center gap-2');
    expect($html)->toContain('autosave: true');
    expect($html)->toContain("method: 'saveNotificationSetting'");
    expect($html)->toContain('debounceMs: 500');
    expect($html)->toContain('Receive email notifications');
    expect($html)->toContain('wire:model="notificationsEnabled"');
});
