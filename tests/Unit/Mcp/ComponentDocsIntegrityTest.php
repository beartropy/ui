<?php

/**
 * Tests for the data layer used by the bt-ui-component-docs MCP tool.
 *
 * Verifies that every component listed by the tool has valid documentation
 * files and that those files contain the expected structure.
 */

$docsPath = dirname(__DIR__, 3).'/docs';

it('has an LLM doc for every component', function () use ($docsPath) {
    $llmFiles = glob($docsPath.'/llms/*.md');

    expect($llmFiles)->not->toBeEmpty();

    foreach ($llmFiles as $file) {
        $name = basename($file, '.md');
        expect(file_get_contents($file))
            ->not->toBeEmpty("LLM doc for '{$name}' is empty");
    }
});

it('has a user doc for every component with an LLM doc', function () use ($docsPath) {
    $llmFiles = glob($docsPath.'/llms/*.md');
    $missing = [];

    foreach ($llmFiles as $file) {
        $name = basename($file, '.md');
        $userDoc = $docsPath.'/components/'.$name.'.md';

        if (! file_exists($userDoc)) {
            $missing[] = $name;
        }
    }

    expect($missing)->toBeEmpty(
        'Missing user docs (docs/components/*.md) for: '.implode(', ', $missing)
    );
});

it('has an LLM doc for every component with a user doc', function () use ($docsPath) {
    $userFiles = glob($docsPath.'/components/*.md');
    $missing = [];

    foreach ($userFiles as $file) {
        $name = basename($file, '.md');
        $llmDoc = $docsPath.'/llms/'.$name.'.md';

        if (! file_exists($llmDoc)) {
            $missing[] = $name;
        }
    }

    expect($missing)->toBeEmpty(
        'Missing LLM docs (docs/llms/*.md) for: '.implode(', ', $missing)
    );
});

it('LLM docs contain a props section', function () use ($docsPath) {
    $llmFiles = glob($docsPath.'/llms/*.md');
    $failures = [];

    foreach ($llmFiles as $file) {
        $name = basename($file, '.md');
        $content = file_get_contents($file);

        // Accept "## Props" or "## Constructor" as valid props sections
        if (! str_contains($content, '## Props') && ! str_contains($content, '## Constructor')) {
            $failures[] = $name;
        }
    }

    expect($failures)->toBeEmpty(
        'LLM docs missing props section: '.implode(', ', $failures)
    );
});

it('user docs contain a props section', function () use ($docsPath) {
    $userFiles = glob($docsPath.'/components/*.md');
    $failures = [];

    foreach ($userFiles as $file) {
        $name = basename($file, '.md');
        $content = file_get_contents($file);

        if (! str_contains($content, '## Props')) {
            $failures[] = $name;
        }
    }

    expect($failures)->toBeEmpty(
        'User docs missing props section: '.implode(', ', $failures)
    );
});

it('LLM and user doc counts match', function () use ($docsPath) {
    $llmCount = count(glob($docsPath.'/llms/*.md'));
    $userCount = count(glob($docsPath.'/components/*.md'));

    expect($llmCount)->toBe($userCount,
        "Doc count mismatch: {$llmCount} LLM docs vs {$userCount} user docs"
    );
});
