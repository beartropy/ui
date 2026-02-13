<?php

/**
 * Tests for the data layer used by the beartropy-list-components MCP tool.
 *
 * Verifies that the hardcoded CATEGORIES constant in ListComponents stays in
 * sync with the actual documentation files, preventing the tool from
 * advertising components with no docs or missing components that have docs.
 *
 * Since `laravel/mcp` is not a dev dependency (the Tool base class is only
 * available when Laravel Boost is installed), we parse the CATEGORIES constant
 * directly from the source file rather than loading the class.
 */

$docsPath = dirname(__DIR__, 3).'/docs/llms';
$sourcePath = dirname(__DIR__, 3).'/src/Mcp/Tools/ListComponents.php';

/**
 * Parse the CATEGORIES constant from the ListComponents source file.
 *
 * Extracts component names from the PHP array literal without loading
 * the class (which would fail without the MCP base class).
 *
 * @return array<string, list<string>>
 */
function parseCategoriesFromSource(string $sourcePath): array
{
    $source = file_get_contents($sourcePath);

    // Match the full CATEGORIES constant block
    preg_match('/(?:public|protected)\s+const\s+CATEGORIES\s*=\s*\[(.+?)\n\s*\];/s', $source, $match);

    if (! $match) {
        throw new RuntimeException('Could not parse CATEGORIES from ListComponents.php');
    }

    $categories = [];
    $block = $match[1];

    // Match each category and its component list
    preg_match_all("/'(\w+)'\s*=>\s*\[([^\]]+)\]/s", $block, $catMatches, PREG_SET_ORDER);

    foreach ($catMatches as $catMatch) {
        $catName = $catMatch[1];
        preg_match_all("/'([a-z][-a-z]*)'/", $catMatch[2], $nameMatches);
        $categories[$catName] = $nameMatches[1];
    }

    return $categories;
}

$categories = parseCategoriesFromSource($sourcePath);
$allCategorized = array_merge(...array_values($categories));

it('has doc files for every component in CATEGORIES', function () use ($docsPath, $allCategorized) {
    $missing = [];

    foreach ($allCategorized as $name) {
        if (! file_exists($docsPath.'/'.$name.'.md')) {
            $missing[] = $name;
        }
    }

    expect($missing)->toBeEmpty(
        'Components in CATEGORIES without LLM docs: '.implode(', ', $missing)
    );
});

it('has CATEGORIES entries for every doc file', function () use ($docsPath, $allCategorized) {
    $docFiles = glob($docsPath.'/*.md');
    $uncategorized = [];

    foreach ($docFiles as $file) {
        $name = basename($file, '.md');

        if (! in_array($name, $allCategorized, true)) {
            $uncategorized[] = $name;
        }
    }

    expect($uncategorized)->toBeEmpty(
        'Doc files not listed in CATEGORIES: '.implode(', ', $uncategorized)
    );
});

it('has no duplicate entries across categories', function () use ($categories) {
    $seen = [];
    $duplicates = [];

    foreach ($categories as $cat => $components) {
        foreach ($components as $name) {
            if (isset($seen[$name])) {
                $duplicates[] = "{$name} (in '{$seen[$name]}' and '{$cat}')";
            }
            $seen[$name] = $cat;
        }
    }

    expect($duplicates)->toBeEmpty(
        'Duplicate components across categories: '.implode(', ', $duplicates)
    );
});

it('keeps components alphabetically sorted within each category', function () use ($categories) {
    foreach ($categories as $cat => $components) {
        $sorted = $components;
        sort($sorted);

        expect($components)->toBe($sorted,
            "Components in '{$cat}' category are not alphabetically sorted"
        );
    }
});

it('only contains valid category names', function () use ($categories) {
    $validCategories = ['forms', 'ui'];

    expect(array_keys($categories))->toBe($validCategories);
});

it('categorized count matches doc file count', function () use ($docsPath, $allCategorized) {
    $docCount = count(glob($docsPath.'/*.md'));

    expect(count($allCategorized))->toBe($docCount,
        count($allCategorized)." categorized components vs {$docCount} doc files"
    );
});
