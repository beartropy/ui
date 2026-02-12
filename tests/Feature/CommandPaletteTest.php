<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
    Cache::flush();
});

// --- Structure ---

it('renders wrapper with Alpine x-data and keyboard shortcuts', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('x-data="btCommandPalette(')
        ->toContain('@keydown.window.prevent.cmd.k=')
        ->toContain('@keydown.window.prevent.ctrl.k=');
});

it('renders x-teleport portal to body', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('x-teleport="body"');
});

it('renders modal overlay with x-show and x-cloak', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('x-show="open"')
        ->toContain('x-cloak');
});

it('renders ARIA dialog attributes on overlay', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('aria-label="Command palette"');
});

it('renders listbox with ARIA role and label', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('role="listbox"')
        ->toContain('aria-label="Search results"');
});

it('renders list items with role option and aria-selected', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('role="option"')
        ->toContain(':aria-selected="index === selectedIndex"');
});

// --- Trigger ---

it('renders default input trigger with search placeholder', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('Search the site')
        ->toContain('@click="open = true"');
});

it('renders custom slot trigger instead of default input', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]"><button>Open</button></x-bt-command-palette>');

    expect($html)
        ->toContain('<button>Open</button>')
        ->toContain('@click="open = true"');
});

// --- Items ---

it('passes items to Alpine as bt_cp_data', function () {
    $items = [
        ['title' => 'Dashboard', 'action' => '/dashboard'],
        ['title' => 'Settings', 'action' => '/settings'],
    ];

    $html = Blade::render('<x-bt-command-palette :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Dashboard')
        ->toContain('Settings')
        ->toContain('/dashboard')
        ->toContain('/settings');
});

it('normalizes items with missing keys', function () {
    $items = [
        ['title' => 'Minimal'],
    ];

    $html = Blade::render('<x-bt-command-palette :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Minimal');
});

it('renders no results message', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('No results.');
});

it('renders showing first results hint', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('Showing first 5 results');
});

// --- Search input ---

it('renders modal search input with x-model query', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('x-model="query"')
        ->toContain('autofocus');
});

// --- Keyboard navigation ---

it('renders keyboard handlers for arrow keys, tab, and enter', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('@keydown.arrow-down.prevent="handleKey($event)"')
        ->toContain('@keydown.arrow-up.prevent="handleKey($event)"')
        ->toContain('@keydown.enter.prevent="handleKey($event)"')
        ->toContain('@keydown.tab.prevent="handleKey($event)"')
        ->toContain('@keydown.shift.tab.prevent="handleKey($event)"');
});

it('renders click.self on dialog wrapper to close', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('@click.self="open = false"');
});

it('renders escape to close', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('@keydown.escape.window="open = false"');
});

// --- Color presets ---

it('applies default beartropy color preset', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('hover:bg-beartropy-100/60')
        ->toContain('bg-beartropy-500/10');
});

it('applies blue color preset', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" color="blue" />');

    expect($html)
        ->toContain('bg-blue-50/80')
        ->toContain('hover:bg-blue-100/60')
        ->toContain('text-blue-900')
        ->toContain('bg-blue-500/10');
});

it('applies emerald color preset', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" color="emerald" />');

    expect($html)
        ->toContain('bg-emerald-50/80')
        ->toContain('hover:bg-emerald-100/60')
        ->toContain('bg-emerald-500/10');
});

it('applies violet color preset', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" color="violet" />');

    expect($html)
        ->toContain('bg-violet-50/80')
        ->toContain('hover:bg-violet-100/60')
        ->toContain('bg-violet-500/10');
});

// --- Transitions ---

it('renders enter and leave transitions on overlay', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('x-transition:enter="transition ease-out duration-200"')
        ->toContain('x-transition:enter-start="opacity-0"')
        ->toContain('x-transition:leave="transition ease-in duration-200"');
});

it('renders modal panel spring transition', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('x-transition:enter-start="opacity-0 scale-95 translate-y-8"')
        ->toContain('x-transition:enter-end="opacity-100 scale-100 translate-y-0"');
});

// --- Styling ---

it('renders backdrop blur overlay', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('bg-black/40 backdrop-blur-xl z-[9999]');
});

it('renders modal panel with rounded border', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('rounded-2xl shadow-2xl max-w-2xl');
});

it('renders tags with click-to-search behavior', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain('@click.stop="query = tag"');
});

// --- Alpine script ---

it('includes btCommandPalette Alpine component script', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)
        ->toContain('function btCommandPalette(')
        ->toContain('get filtered()')
        ->toContain('handleKey(e)')
        ->toContain('execute(item)')
        ->toContain('scrollIntoView()');
});

it('script supports route: action type', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain("action.startsWith('route:')");
});

it('script supports url: action type', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain("action.startsWith('url:')");
});

it('script supports dispatch: action type', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain("action.startsWith('dispatch:')");
});

it('script opens _blank links with noopener noreferrer', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" />');

    expect($html)->toContain("'noopener,noreferrer'");
});

// --- Permission filtering ---

it('filters items by permission for guests', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $items = [
        ['title' => 'Public', 'action' => '/public'],
        ['title' => 'Admin Only', 'action' => '/admin', 'permission' => 'admin-access'],
        ['title' => 'Role Gated', 'action' => '/role', 'roles' => 'editor'],
    ];

    $html = Blade::render('<x-bt-command-palette :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Public')
        ->not->toContain('Admin Only')
        ->not->toContain('Role Gated');
});

it('shows all items for guests when allow-guests is true', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $items = [
        ['title' => 'Public', 'action' => '/public'],
        ['title' => 'Protected', 'action' => '/admin', 'permission' => 'admin-access'],
    ];

    $html = Blade::render('<x-bt-command-palette :items="$items" :allow-guests="true" />', ['items' => $items]);

    expect($html)
        ->toContain('Public')
        ->toContain('Protected');
});

it('strips permission and roles keys from client data', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $items = [
        ['title' => 'Dashboard', 'action' => '/dashboard', 'permission' => 'view-dashboard'],
    ];

    $html = Blade::render('<x-bt-command-palette :items="$items" :allow-guests="true" />', ['items' => $items]);

    expect($html)
        ->toContain('Dashboard')
        ->not->toContain('view-dashboard');
});

// --- JSON source ---

it('loads items from JSON storage file', function () {
    Storage::fake('local');
    Storage::disk('local')->put('cp-items.json', json_encode([
        ['title' => 'From JSON', 'action' => '/json'],
    ]));

    $html = Blade::render('<x-bt-command-palette src="cp-items.json" />');

    expect($html)->toContain('From JSON');
});

it('renders empty when JSON file does not exist', function () {
    Storage::fake('local');

    $html = Blade::render('<x-bt-command-palette src="missing.json" />');

    expect($html)
        ->toContain('x-data="btCommandPalette(')
        ->not->toContain('From JSON');
});

// --- Caching ---

it('caches items per user key', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $items = [['title' => 'Cached', 'action' => '/cached']];

    Blade::render('<x-bt-command-palette :items="$items" />', ['items' => $items]);

    expect(Cache::has('bt-cp:guest:v' . crc32(json_encode($items)) . '|inline'))->toBeTrue();
});

// --- Custom ID ---

it('uses custom id when provided', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" id="my-palette" />');

    expect($html)
        ->toContain('id="my-palette"')
        ->toContain('my-palette-input');
});

// --- Merge attributes ---

it('merges custom class on wrapper', function () {
    $html = Blade::render('<x-bt-command-palette :items="[]" class="custom-class" />');

    expect($html)->toContain('custom-class');
});
