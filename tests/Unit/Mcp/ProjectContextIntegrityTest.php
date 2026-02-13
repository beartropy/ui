<?php

/**
 * Tests for the data layer used by the beartropy-project-context MCP tool.
 *
 * Verifies that the data sources the tool relies on (composer.json version,
 * config keys, component_defaults references) are present and consistent.
 *
 * Since `laravel/mcp` is not a dev dependency, we test the raw data sources
 * rather than instantiating the tool class.
 */

$basePath = dirname(__DIR__, 3);
$configPath = $basePath.'/config/beartropyui.php';
$composerPath = $basePath.'/composer.json';
$docsPath = $basePath.'/docs/llms';

// --- composer.json version ---

it('has a version field in composer.json', function () use ($composerPath) {
    $data = json_decode(file_get_contents($composerPath), true);

    expect($data)->toHaveKey('version');
    expect($data['version'])->toBeString()->not->toBeEmpty();
});

it('has a valid semver version in composer.json', function () use ($composerPath) {
    $data = json_decode(file_get_contents($composerPath), true);

    expect($data['version'])->toMatch('/^\d+\.\d+\.\d+(-[\w.]+)?$/');
});

// --- Config structure ---

it('config has the expected top-level keys', function () use ($configPath) {
    // We can't use config() here (no Laravel app), so we parse the file structure.
    $source = file_get_contents($configPath);

    expect($source)->toContain("'prefix'");
    expect($source)->toContain("'icons'");
    expect($source)->toContain("'component_defaults'");
});

it('config icons section has set and variant keys', function () use ($configPath) {
    $source = file_get_contents($configPath);

    expect($source)->toContain("'set'");
    expect($source)->toContain("'variant'");
});

// --- component_defaults references valid components ---

/**
 * Parse component names from the component_defaults section of the config file.
 *
 * @return list<string>
 */
function parseComponentDefaultNames(string $configPath): array
{
    $source = file_get_contents($configPath);

    // Match the component_defaults array block
    preg_match("/'component_defaults'\s*=>\s*\[(.+?)\n\s{4}\],/s", $source, $match);

    if (! $match) {
        throw new RuntimeException('Could not parse component_defaults from config');
    }

    preg_match_all("/'([a-z][-a-z]*)'\s*=>\s*\[/", $match[1], $nameMatches);

    return $nameMatches[1];
}

it('component_defaults only references components with docs', function () use ($configPath, $docsPath) {
    $defaultNames = parseComponentDefaultNames($configPath);
    $missing = [];

    foreach ($defaultNames as $name) {
        if (! file_exists($docsPath.'/'.$name.'.md')) {
            $missing[] = $name;
        }
    }

    expect($missing)->toBeEmpty(
        'component_defaults references components without LLM docs: '.implode(', ', $missing)
    );
});

it('component_defaults has at least one entry', function () use ($configPath) {
    $names = parseComponentDefaultNames($configPath);

    expect($names)->not->toBeEmpty();
});
