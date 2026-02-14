<?php

namespace Beartropy\Ui\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ListComponents extends Tool
{
    protected string $name = 'bt-ui-list-components';

    protected string $description = 'List all available Beartropy UI components with their categories. Use this to discover component names before calling bt-ui-component-docs.';

    /** @var array<string, list<string>> */
    public const CATEGORIES = [
        'forms' => [
            'button',
            'button-icon',
            'chat-input',
            'checkbox',
            'datetime',
            'file-dropzone',
            'file-input',
            'input',
            'lookup',
            'radio',
            'radio-group',
            'select',
            'tag',
            'textarea',
            'time-picker',
            'toggle',
        ],
        'ui' => [
            'alert',
            'avatar',
            'badge',
            'card',
            'command-palette',
            'debug-breakpoints',
            'dialog',
            'dropdown',
            'fab',
            'icon',
            'menu',
            'modal',
            'nav',
            'option',
            'skeleton',
            'slider',
            'table',
            'toast',
            'toggle-theme',
            'tooltip',
        ],
    ];

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [
            'category' => $schema->string()
                ->description('Filter by category: "forms" or "ui". Omit to list all components.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $category = $request->get('category');
        $docsPath = dirname(__DIR__, 3).'/docs/llms';
        $available = [];

        foreach (glob($docsPath.'/*.md') as $file) {
            $available[] = basename($file, '.md');
        }

        $grouped = [];

        foreach (self::CATEGORIES as $cat => $components) {
            if ($category && $cat !== $category) {
                continue;
            }

            $grouped[$cat] = array_values(array_intersect($components, $available));
        }

        // Include any components not in the hardcoded map
        if (! $category) {
            $mapped = array_merge(...array_values(self::CATEGORIES));
            $uncategorized = array_diff($available, $mapped);

            if ($uncategorized !== []) {
                $grouped['other'] = array_values($uncategorized);
            }
        }

        return Response::json($grouped);
    }
}
