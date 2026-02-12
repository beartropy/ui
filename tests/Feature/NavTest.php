<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

// --- Structure ---

it('renders a nav element with aria-label', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)
        ->toContain('<nav')
        ->toContain('aria-label="Sidebar navigation"');
});

it('renders empty nav without errors', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)
        ->toContain('<nav')
        ->toContain('</nav>');
});

it('renders Alpine x-data on the nav', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)->toContain('x-data=');
});

// --- Categories ---

it('renders category headings', function () {
    $items = [
        ['category' => 'General', 'items' => [
            ['label' => 'Home', 'route' => '/home', 'icon' => 'home'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('General');
});

it('hides category headings when hideCategories is true', function () {
    $items = [
        ['category' => 'Hidden', 'items' => [
            ['label' => 'Item', 'route' => '/item'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" :hide-categories="true" />', ['items' => $items]);

    expect($html)->not->toContain('Hidden');
});

it('wraps flat items into anonymous category', function () {
    $items = [
        ['label' => 'Dashboard', 'route' => '/dashboard', 'icon' => 'home'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('Dashboard');
});

// --- Items ---

it('renders item labels', function () {
    $items = [
        ['label' => 'Dashboard', 'route' => '/dashboard', 'icon' => 'home'],
        ['label' => 'Settings', 'route' => '/settings', 'icon' => 'cog-6-tooth'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Dashboard')
        ->toContain('Settings');
});

it('renders item hrefs from route key', function () {
    $items = [
        ['label' => 'Users', 'route' => '/users'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('href="/users"');
});

it('renders icons through the Icon component', function () {
    $items = [
        ['label' => 'Home', 'route' => '/home', 'icon' => 'heroicon-o-home'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain('<svg');
});

it('does not render icon for items without icon key', function () {
    $items = [
        ['label' => 'Plain', 'route' => '/plain'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" :collapse-button-as-item="false" />', ['items' => $items]);

    // With no icons set and collapse button disabled, no SVGs from item icons
    // (chevron SVG only appears on parents with children)
    expect($html)
        ->toContain('Plain')
        ->not->toContain('<svg');
});

// --- Children ---

it('renders children as nested items', function () {
    $items = [
        ['label' => 'Users', 'route' => '#', 'icon' => 'users', 'children' => [
            ['label' => 'All Users', 'route' => '/users'],
            ['label' => 'Create User', 'route' => '/users/create'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Users')
        ->toContain('All Users')
        ->toContain('Create User')
        ->toContain('href="/users"')
        ->toContain('href="/users/create"');
});

it('renders parent with children as toggle (href=#)', function () {
    $items = [
        ['label' => 'Parent', 'icon' => 'folder', 'children' => [
            ['label' => 'Child', 'route' => '/child'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('data-has-children="1"')
        ->toContain('click.prevent');
});

it('renders aria-expanded on parent items with children', function () {
    $items = [
        ['label' => 'Group', 'icon' => 'folder', 'children' => [
            ['label' => 'Sub', 'route' => '/sub'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)->toContain(':aria-expanded=');
});

it('renders chevron SVG for expandable parents', function () {
    $items = [
        ['label' => 'Expandable', 'children' => [
            ['label' => 'Child', 'route' => '/c'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('rotate-180')
        ->toContain('<svg');
});

it('renders floating submenu via x-teleport for children', function () {
    $items = [
        ['label' => 'Group', 'children' => [
            ['label' => 'Floating Child', 'route' => '/fc'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('x-teleport="body"')
        ->toContain('role="menu"')
        ->toContain('role="menuitem"');
});

// --- Dividers ---

it('renders dividers as border elements', function () {
    $items = [
        ['label' => 'Before', 'route' => '/before'],
        ['divider' => true],
        ['label' => 'After', 'route' => '/after'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Before')
        ->toContain('After')
        ->toContain('border-t');
});

// --- Badges ---

it('renders static badges', function () {
    $items = [
        ['label' => 'Inbox', 'route' => '/inbox', 'badge' => '5'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('>5</span>')
        ->toContain('rounded-full');
});

// --- Disabled & External ---

it('renders disabled items with opacity and pointer-events-none', function () {
    $items = [
        ['label' => 'Disabled', 'route' => '/disabled', 'disabled' => true],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('opacity-60')
        ->toContain('pointer-events-none');
});

it('renders external links with target blank', function () {
    $items = [
        ['label' => 'Docs', 'route' => 'https://example.com', 'external' => true],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('target="_blank"')
        ->toContain('rel="noopener"')
        ->toContain('href="https://example.com"');
});

// --- wire:navigate ---

it('does not add wire:navigate by default', function () {
    $items = [
        ['label' => 'Link', 'route' => '/link'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    // The Alpine JS contains "livewire:navigated" event listener, so check for the attribute pattern
    expect($html)->not->toMatch('/href="\/link"\s+wire:navigate/');
});

it('adds wire:navigate when withnavigate is true', function () {
    $items = [
        ['label' => 'Link', 'route' => '/link'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" :withnavigate="true" />', ['items' => $items]);

    expect($html)->toContain('wire:navigate');
});

// --- Active State (PHP) ---

it('detects active item via route path match', function () {
    // Simulate request to /dashboard
    $this->get('/dashboard');

    $nav = new \Beartropy\Ui\Components\Nav(items: [
        ['label' => 'Dashboard', 'route' => '/dashboard'],
    ]);

    expect($nav->isItemActive(['route' => '/dashboard']))->toBeTrue();
    expect($nav->isItemActive(['route' => '/other']))->toBeFalse();
});

it('detects active item via match patterns', function () {
    $this->get('/users/42/edit');

    $nav = new \Beartropy\Ui\Components\Nav();

    expect($nav->isItemActive(['match' => 'users/*']))->toBeTrue();
    expect($nav->isItemActive(['match' => 'posts/*']))->toBeFalse();
});

it('detects active item via routeNameMatch', function () {
    Route::get('/test-route', fn () => '')->name('test.index');
    $this->get('/test-route');

    $nav = new \Beartropy\Ui\Components\Nav();

    expect($nav->isItemActive(['routeNameMatch' => 'test.*']))->toBeTrue();
    expect($nav->isItemActive(['routeNameMatch' => 'other.*']))->toBeFalse();
});

it('detects active parent when child is active', function () {
    $this->get('/users');

    $nav = new \Beartropy\Ui\Components\Nav();

    $parent = [
        'label' => 'Users',
        'children' => [
            ['label' => 'All', 'route' => '/users'],
        ],
    ];

    expect($nav->isItemActive($parent))->toBeTrue();
});

it('detects active item via routeName', function () {
    Route::get('/named-test', fn () => '')->name('named.test');
    $this->get('/named-test');

    $nav = new \Beartropy\Ui\Components\Nav();

    expect($nav->isItemActive(['routeName' => 'named.test']))->toBeTrue();
    expect($nav->isItemActive(['routeName' => 'named.other']))->toBeFalse();
});

// --- Collapse Button ---

it('renders collapse button by default', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)
        ->toContain('sidebarIsCollapsed')
        ->toContain('<button');
});

it('hides collapse button when collapseButtonAsItem is false', function () {
    $html = Blade::render('<x-bt-nav :items="[]" :collapse-button-as-item="false" />');

    // No button element at the footer
    expect($html)->not->toMatch('/<button[^>]*type="button"/');
});

it('renders localized collapse/expand labels', function () {
    $html = Blade::render('<x-bt-nav :items="[]" />');

    expect($html)
        ->toContain('Collapse')
        ->toContain('Expand');
});

// --- Color Presets ---

it('uses default beartropy color preset', function () {
    $items = [
        ['label' => 'Home', 'route' => '/home'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('hover:bg-beartropy-200/40')
        ->toContain('bg-beartropy-200/60');
});

it('applies color preset via color prop', function () {
    $items = [
        ['label' => 'Home', 'route' => '/home'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" color="blue" />', ['items' => $items]);

    expect($html)
        ->toContain('hover:bg-blue-200/40')
        ->toContain('bg-blue-200/60');
});

it('applies text highlight mode', function () {
    $items = [
        ['label' => 'Home', 'route' => '/home'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" highlight-mode="text" />', ['items' => $items]);

    expect($html)
        ->toContain('hover:text-beartropy-500');
});

// --- Permissions ---

it('filters items based on can permission', function () {
    $items = [
        ['label' => 'Public', 'route' => '/public'],
        ['label' => 'Secret', 'route' => '/secret', 'can' => 'admin-access'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('Public')
        ->not->toContain('Secret');
});

it('filters children and removes parent when all children filtered', function () {
    $items = [
        ['label' => 'Admin', 'children' => [
            ['label' => 'Secret Child', 'route' => '/secret', 'can' => 'admin-access'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->not->toContain('Admin')
        ->not->toContain('Secret Child');
});

// --- Tooltips ---

it('renders tooltip attribute on items', function () {
    $items = [
        ['label' => 'Help', 'route' => '/help', 'tooltip' => 'Get help'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('title="Get help"')
        ->toContain('x-tooltip=');
});

// --- navId ---

it('generates unique IDs for items', function () {
    $nav = new \Beartropy\Ui\Components\Nav();

    $id1 = $nav->navId(['label' => 'A', 'route' => '/a']);
    $id2 = $nav->navId(['label' => 'B', 'route' => '/b']);
    $id3 = $nav->navId(['label' => 'A', 'route' => '/a']);

    expect($id1)->not->toBe($id2);
    expect($id1)->toBe($id3);
});

// --- Data Attributes ---

it('renders data attributes for Alpine active detection', function () {
    $items = [
        ['label' => 'Link', 'route' => '/link'],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('data-nav-id=')
        ->toContain('data-has-children="0"')
        ->toContain('data-href="/link"');
});

it('renders data-child-prefixes on parent items', function () {
    $items = [
        ['label' => 'Parent', 'children' => [
            ['label' => 'Child A', 'route' => '/a'],
            ['label' => 'Child B', 'route' => '/b'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" />', ['items' => $items]);

    expect($html)
        ->toContain('data-has-children="1"')
        ->toContain('data-child-prefixes=');
});

// --- renderIcon ---

it('passes raw SVG through renderIcon', function () {
    $nav = new \Beartropy\Ui\Components\Nav();

    $result = $nav->renderIcon('<svg class="custom">test</svg>');
    expect($result)->toBe('<svg class="custom">test</svg>');
});

it('returns empty string for empty icon', function () {
    $nav = new \Beartropy\Ui\Components\Nav();

    expect($nav->renderIcon(''))->toBe('');
});

// --- Priority: user value overrides preset ---

it('allows user to override categoryClass', function () {
    $items = [
        ['category' => 'Custom', 'items' => [
            ['label' => 'Item', 'route' => '/item'],
        ]],
    ];
    $html = Blade::render('<x-bt-nav :items="$items" category-class="my-custom-category" />', ['items' => $items]);

    expect($html)->toContain('my-custom-category');
});
