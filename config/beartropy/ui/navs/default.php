<?php

return [
    [
        'category' => 'General',
        'items' => [
            [
                'label'   => 'Home',
                'icon'    => 'fas fa-home',
                'route'   => '/home',
                'tooltip' => 'Main dashboard',
                'badge'   => 1,
                'match'   => '/home*',
            ],
            [
                'divider' => true, // Aquí se muestra un divider horizontal
            ],
            [
                'label'   => 'Profile',
                'icon'    => '<svg viewBox="0 0 24 24" class="w-4 h-4 text-blue-600"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 16-4 16 0" /></svg>',
                'route'   => '/profile',
                'tooltip' => 'Your user profile',
            ],
            [
                'label'   => 'Documentation',
                'icon'    => 'fas fa-book',
                'route'   => 'https://docs.example.com',
                'external'=> true,
                'tooltip' => 'Project documentation (external)',
            ],
            [
                'label'   => 'Settings',
                'icon'    => '<img src="/icons/settings.svg" class="w-4 h-4">',
                'route'   => '/settings',
                'tooltip' => 'System preferences',
                'disabled'=> true,
            ],
        ],
    ],

    [
        'category' => 'Management',
        'items' => [
            [
                'label'   => 'Users',
                'icon'    => 'fas fa-users',
                'route'   => '/users',
                'tooltip' => 'Manage all users',
                'badge'   => 15,
                'children'=> [
                    [
                        'label'   => 'List Users',
                        'icon'    => 'fas fa-list',
                        'route'   => '/users/list',
                        'tooltip' => 'View user directory',
                        'match'   => '/users/list*',
                    ],
                    [
                        'label'   => 'Create User',
                        'icon'    => 'fas fa-user-plus',
                        'route'   => '/users/create',
                        'tooltip' => 'Add a new user',
                        'can'     => 'manage-users',
                    ],
                ],
            ],
            [
                'label'   => 'Teams',
                'icon'    => '<svg viewBox="0 0 20 20" class="w-4 h-4 text-green-700"><rect x="3" y="8" width="14" height="8" rx="2"/><circle cx="7" cy="12" r="2"/><circle cx="13" cy="12" r="2"/></svg>',
                'route'   => '/teams',
                'tooltip' => 'Manage teams',
                'badge'   => 'new',
                'children'=> [
                    [
                        'label'   => 'All Teams',
                        'icon'    => 'fas fa-layer-group',
                        'route'   => '/teams/all',
                        'tooltip' => 'Team directory',
                    ],
                    [
                        'label'   => 'Create Team',
                        'icon'    => '<img src="/icons/team-add.svg" class="w-4 h-4">',
                        'route'   => '/teams/create',
                        'tooltip' => 'Add a new team',
                        'disabled'=> true,
                    ],
                ],
            ],
        ],
    ],

    [
        'category' => 'Features',
        'items' => [
            [
                'label'   => 'Notifications',
                'icon'    => 'fas fa-bell',
                'route'   => '/notifications',
                'tooltip' => 'User notifications',
                'badge'   => 4,
            ],
            [
                'label'   => 'Reports',
                'icon'    => 'fas fa-chart-bar',
                'route'   => '/reports',
                'tooltip' => 'Analytics & reports',
                'can'     => ['admin', 'manager'],
                'children'=> [
                    [
                        'label'   => 'Monthly Report',
                        'icon'    => '<svg width="20" height="20" fill="none" class="w-4 h-4"><rect width="14" height="8" x="3" y="6" rx="2" fill="#F59E42"/><circle cx="7" cy="10" r="2" fill="#fff"/></svg>',
                        'route'   => '/reports/monthly',
                        'tooltip' => 'View monthly analytics',
                    ],
                    [
                        'label'   => 'Custom Report',
                        'icon'    => 'fas fa-filter',
                        'route'   => '/reports/custom',
                        'tooltip' => 'Create your own report',
                        'disabled'=> true,
                    ],
                ],
            ],
        ],
    ],

    [
        'category' => 'Administration',
        'items' => [
            [
                'label'   => 'System Logs',
                'icon'    => 'fas fa-file-alt',
                'route'   => '/logs',
                'tooltip' => 'View system logs',
                'can'     => 'admin',
            ],
            [
                'label'   => 'API Keys',
                'icon'    => '<img src="/icons/api-key.svg" class="w-4 h-4">',
                'route'   => '/api-keys',
                'tooltip' => 'Manage API credentials',
                'badge'   => 2,
                'can'     => ['admin', 'developer'],
                'external'=> true,
            ],
        ],
    ]
];
