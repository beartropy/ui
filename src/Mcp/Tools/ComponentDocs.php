<?php

namespace Beartropy\Ui\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ComponentDocs extends Tool
{
    protected string $name = 'bt-ui-component-docs';

    protected string $description = 'Get detailed documentation for a specific Beartropy UI component including props, slots, examples, and architecture details.';

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [
            'component' => $schema->string()
                ->description('Component name in kebab-case (e.g., "button", "select", "file-dropzone"). Use bt-ui-list-components to see all available names.')
                ->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $component = trim((string) $request->get('component'));
        $docsPath = dirname(__DIR__, 3).'/docs';
        $llmFile = $docsPath.'/llms/'.$component.'.md';
        $componentFile = $docsPath.'/components/'.$component.'.md';

        if (! file_exists($llmFile) && ! file_exists($componentFile)) {
            $available = $this->availableComponents($docsPath);

            return Response::error(
                "Unknown component: {$component}. Available components: ".implode(', ', $available)
            );
        }

        $sections = [];

        if (file_exists($llmFile)) {
            $sections[] = file_get_contents($llmFile);
        }

        if (file_exists($componentFile)) {
            $sections[] = file_get_contents($componentFile);
        }

        return Response::text(implode("\n\n---\n\n", $sections));
    }

    /**
     * @return list<string>
     */
    protected function availableComponents(string $docsPath): array
    {
        $names = [];

        foreach (glob($docsPath.'/llms/*.md') as $file) {
            $names[] = basename($file, '.md');
        }

        sort($names);

        return $names;
    }
}
