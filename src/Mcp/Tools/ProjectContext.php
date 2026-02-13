<?php

namespace Beartropy\Ui\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ProjectContext extends Tool
{
    protected string $name = 'beartropy-project-context';

    protected string $description = 'Returns this project\'s Beartropy UI configuration: component defaults, color presets, icon set, and installed version.';

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): Response
    {
        $lines = [];

        $lines[] = '# Beartropy UI — Project Context';
        $lines[] = '';

        // Package version
        $lines[] = '## Version';
        $lines[] = '';
        $lines[] = $this->packageVersion();
        $lines[] = '';

        // Component tag prefix
        $lines[] = '## Component Prefix';
        $lines[] = '';
        $prefix = config('beartropyui.prefix', 'bt');
        $lines[] = $prefix
            ? "Tag prefix: `<x-{$prefix}-*>` (e.g. `<x-{$prefix}-button>`)"
            : 'No prefix configured — components use `<x-button>`, `<x-alert>`, etc.';
        $lines[] = '';

        // Icon configuration
        $lines[] = '## Icon Set';
        $lines[] = '';
        $iconSet = config('beartropyui.icons.set', 'heroicons');
        $iconVariant = config('beartropyui.icons.variant', 'outline');
        $lines[] = "- Set: **{$iconSet}**";
        $lines[] = "- Default variant: **{$iconVariant}**";
        $lines[] = '';

        // Component defaults
        $defaults = config('beartropyui.component_defaults', []);

        if ($defaults) {
            $lines[] = '## Component Defaults';
            $lines[] = '';
            $lines[] = '| Component | Defaults |';
            $lines[] = '|-----------|----------|';

            foreach ($defaults as $component => $settings) {
                $parts = [];

                foreach ($settings as $key => $value) {
                    $display = is_bool($value) ? ($value ? 'true' : 'false') : $value;
                    $parts[] = "{$key}: {$display}";
                }

                $lines[] = "| {$component} | ".implode(', ', $parts).' |';
            }

            $lines[] = '';
        }

        // Component counts by category
        $lines[] = '## Available Components';
        $lines[] = '';

        $docsPath = dirname(__DIR__, 3).'/docs/llms';
        $docFiles = glob($docsPath.'/*.md') ?: [];
        $docNames = array_map(fn ($f) => basename($f, '.md'), $docFiles);

        $categories = ListComponents::CATEGORIES;
        $total = 0;

        foreach ($categories as $cat => $components) {
            $available = array_intersect($components, $docNames);
            $count = count($available);
            $total += $count;
            $lines[] = "- **{$cat}**: {$count} components";
        }

        $mapped = array_merge(...array_values($categories));
        $uncategorized = array_diff($docNames, $mapped);

        if ($uncategorized !== []) {
            $count = count($uncategorized);
            $total += $count;
            $lines[] = "- **other**: {$count} components";
        }

        $lines[] = "- **total**: {$total} components";
        $lines[] = '';
        $lines[] = '> Use `beartropy-list-components` for full names, `beartropy-component-docs` for per-component details.';

        return Response::text(implode("\n", $lines));
    }

    protected function packageVersion(): string
    {
        $composerFile = dirname(__DIR__, 3).'/composer.json';

        if (! file_exists($composerFile)) {
            return 'unknown';
        }

        $data = json_decode(file_get_contents($composerFile), true);

        return $data['version'] ?? 'unknown';
    }
}
