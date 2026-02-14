<?php

namespace Beartropy\Ui\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Artisan Command: Install Beartropy AI coding skills.
 *
 * Auto-discovers skills from all installed beartropy/* packages using
 * convention-based scanning (.claude/skills/bt-{pkg}-* directories and
 * docs/llms/*.md files) with optional skills.json overrides.
 *
 * The component skill for each package is generated dynamically by
 * concatenating all per-component LLM reference docs from `docs/llms/`.
 */
class InstallSkills extends Command
{
    /**
     * @var string
     */
    protected $signature = 'beartropy:skills
        {--agent=claude : Target agent (claude, codex, copilot, cursor, windsurf, all)}
        {--force : Overwrite existing skills without prompting}
        {--remove : Remove all installed Beartropy skills}';

    /**
     * @var string
     */
    protected $description = 'Install Beartropy skills for AI coding tools';

    /**
     * All supported agent identifiers.
     *
     * @var string[]
     */
    protected const AGENTS = ['claude', 'codex', 'copilot', 'cursor', 'windsurf'];

    /**
     * Agent configuration map.
     *
     * @var array<string, array{targetDir: string, format: string, extension: string, stripFrontmatter: bool, maxFileSize: int|null}>
     */
    protected const AGENT_CONFIG = [
        'claude' => [
            'targetDir' => '.claude/skills',
            'format' => 'modular',
            'extension' => 'md',
            'stripFrontmatter' => false,
            'maxFileSize' => null,
        ],
        'codex' => [
            'targetDir' => '',
            'format' => 'single',
            'extension' => 'md',
            'stripFrontmatter' => true,
            'maxFileSize' => 32768,
        ],
        'copilot' => [
            'targetDir' => '.github',
            'format' => 'single',
            'extension' => 'md',
            'stripFrontmatter' => true,
            'maxFileSize' => null,
        ],
        'cursor' => [
            'targetDir' => '.cursor/rules',
            'format' => 'multi-file',
            'extension' => 'mdc',
            'stripFrontmatter' => true,
            'maxFileSize' => null,
        ],
        'windsurf' => [
            'targetDir' => '.windsurf/rules',
            'format' => 'multi-file',
            'extension' => 'md',
            'stripFrontmatter' => true,
            'maxFileSize' => 6144,
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $agent = strtolower($this->option('agent'));

        if ($agent !== 'all' && ! in_array($agent, self::AGENTS)) {
            $this->error("Unknown agent: {$agent}. Valid options: ".implode(', ', self::AGENTS).', all');

            return 1;
        }

        $agents = $agent === 'all' ? self::AGENTS : [$agent];

        if ($this->option('remove')) {
            return $this->removeForAgents($agents);
        }

        return $this->installForAgents($agents);
    }

    // ───────────────────────────────────────────────────────────────
    //  Package Discovery
    // ───────────────────────────────────────────────────────────────

    /**
     * Discover all beartropy packages that expose skills.
     *
     * Always includes self (beartropy/ui from source path).
     * Scans vendor/beartropy/* for sibling packages.
     *
     * @return array<string, array{packageName: string, prefix: string, root: string, staticSkills: string[], llmsDir: string|null}>
     */
    protected function discoverPackages(): array
    {
        $packages = [];

        // Always include self from source path (not vendor)
        $selfRoot = dirname(__DIR__, 2);
        $selfResolved = $this->resolvePackageSkills($selfRoot, 'ui');
        if ($selfResolved) {
            $packages['beartropy/ui'] = $selfResolved;
        }

        // Scan vendor/beartropy/* for sibling packages
        $vendorDir = base_path('vendor/beartropy');
        if (is_dir($vendorDir)) {
            foreach (glob($vendorDir.'/*', GLOB_ONLYDIR) as $packageDir) {
                $slug = basename($packageDir);

                // Skip ui in vendor — we use source path above
                if ($slug === 'ui') {
                    continue;
                }

                $hasSkills = is_dir($packageDir.'/.claude/skills');
                $hasLlms = is_dir($packageDir.'/docs/llms');

                if (! $hasSkills && ! $hasLlms) {
                    continue;
                }

                $resolved = $this->resolvePackageSkills($packageDir, $slug);
                if ($resolved) {
                    $packages['beartropy/'.$slug] = $resolved;
                }
            }
        }

        return $packages;
    }

    /**
     * Resolve skills for a single package root.
     *
     * Checks for skills.json first for explicit overrides, then falls back
     * to convention-based scanning of .claude/skills/bt-{slug}-* directories.
     *
     * @return array{packageName: string, prefix: string, root: string, staticSkills: string[], llmsDir: string|null}|null
     */
    protected function resolvePackageSkills(string $root, string $slug): ?array
    {
        $prefix = 'bt-'.$slug;
        $staticSkills = [];
        $llmsDir = null;
        $excludeSkills = [];

        // Check for explicit skills.json manifest
        $manifestPath = $root.'/skills.json';
        if (File::exists($manifestPath)) {
            $manifest = json_decode(File::get($manifestPath), true);
            if (is_array($manifest)) {
                $prefix = $manifest['prefix'] ?? $prefix;
                $excludeSkills = $manifest['excludeSkills'] ?? [];

                if (isset($manifest['staticSkills'])) {
                    $staticSkills = $manifest['staticSkills'];
                }

                if (isset($manifest['componentDocs'])) {
                    $docsPath = $root.'/'.ltrim($manifest['componentDocs'], '/');
                    if (is_dir($docsPath)) {
                        $llmsDir = $docsPath;
                    }
                }
            }
        }

        // Convention-based scanning for static skills if not explicitly listed
        if (empty($staticSkills)) {
            $skillsDir = $root.'/.claude/skills';
            if (is_dir($skillsDir)) {
                foreach (glob($skillsDir.'/'.$prefix.'-*', GLOB_ONLYDIR) as $dir) {
                    $name = basename($dir);
                    if (File::exists($dir.'/SKILL.md') && ! in_array($name, $excludeSkills)) {
                        $staticSkills[] = $name;
                    }
                }
                sort($staticSkills);
            }
        }

        // Convention-based llms dir detection
        if ($llmsDir === null && is_dir($root.'/docs/llms')) {
            $llmsDir = $root.'/docs/llms';
        }

        // Nothing to offer
        if (empty($staticSkills) && $llmsDir === null) {
            return null;
        }

        return [
            'packageName' => 'beartropy/'.$slug,
            'prefix' => $prefix,
            'root' => $root,
            'staticSkills' => $staticSkills,
            'llmsDir' => $llmsDir,
        ];
    }

    // ───────────────────────────────────────────────────────────────
    //  Skill Content Assembly
    // ───────────────────────────────────────────────────────────────

    /**
     * Get all skill contents from all discovered packages.
     *
     * @return array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>
     */
    protected function getSkillContents(): array
    {
        $packages = $this->discoverPackages();
        $skills = [];
        $seenNames = [];

        foreach ($packages as $packageName => $pkg) {
            $skillsDir = $pkg['root'].'/.claude/skills';

            // Static skills (copied from source)
            foreach ($pkg['staticSkills'] as $skillName) {
                $dir = $skillsDir.'/'.$skillName;
                $path = $dir.'/SKILL.md';

                if (! File::exists($path)) {
                    continue;
                }

                if (isset($seenNames[$skillName])) {
                    $this->warn("Duplicate skill '{$skillName}' from {$packageName} (already defined by {$seenNames[$skillName]}), overwriting.");
                }

                $seenNames[$skillName] = $packageName;
                $skills[$skillName] = [
                    'content' => File::get($path),
                    'sourceDir' => $dir,
                    'isGenerated' => false,
                    'package' => $packageName,
                ];
            }

            // Generated component skill (from docs/llms)
            if ($pkg['llmsDir'] !== null) {
                $componentSkillName = $pkg['prefix'].'-component';
                $staticHeaderPath = $skillsDir.'/'.$componentSkillName.'/SKILL.md';

                $content = $this->buildComponentSkill(
                    $pkg['llmsDir'],
                    File::exists($staticHeaderPath) ? $staticHeaderPath : null,
                    $componentSkillName,
                    $packageName
                );

                if (isset($seenNames[$componentSkillName])) {
                    $this->warn("Duplicate skill '{$componentSkillName}' from {$packageName} (already defined by {$seenNames[$componentSkillName]}), overwriting.");
                }

                $seenNames[$componentSkillName] = $packageName;
                $skills[$componentSkillName] = [
                    'content' => $content,
                    'sourceDir' => null,
                    'isGenerated' => true,
                    'package' => $packageName,
                ];
            }
        }

        return $skills;
    }

    /**
     * Build a component skill by concatenating LLM docs.
     *
     * Uses a static SKILL.md as the header (intent-mapping / selection guides)
     * if available, then appends all per-component LLM reference docs.
     */
    protected function buildComponentSkill(
        string $llmsDir,
        ?string $staticHeaderPath,
        string $skillName,
        string $packageName
    ): string {
        if ($staticHeaderPath !== null && File::exists($staticHeaderPath)) {
            $header = trim(File::get($staticHeaderPath))."\n\n---\n\n# Per-Component Reference\n\nDetailed props, slots, architecture, and examples for every component.\n\n---\n\n";
        } else {
            $label = ucwords(str_replace(['beartropy/', '-'], ['', ' '], $packageName));
            $header = <<<SKILL
---
name: {$skillName}
description: Get detailed information and examples for specific {$label} components
version: 1.0.0
author: Beartropy
tags: [beartropy, components, documentation, examples]
---

# {$label} Component Reference

Complete reference for every component in {$packageName}.

---

SKILL;
        }

        $docs = glob($llmsDir.'/*.md');
        sort($docs);

        $sections = [];
        foreach ($docs as $docPath) {
            $sections[] = trim(File::get($docPath));
        }

        return $header.implode("\n\n---\n\n", $sections)."\n";
    }

    // ───────────────────────────────────────────────────────────────
    //  README Generation
    // ───────────────────────────────────────────────────────────────

    /**
     * Build a dynamic README.md listing all installed skills grouped by package.
     *
     * @param  array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>  $skills
     */
    protected function buildReadme(array $skills): string
    {
        $lines = [];
        $lines[] = '# Beartropy - Claude Code Skills';
        $lines[] = '';
        $lines[] = 'This directory contains Claude Code skills for Beartropy packages.';
        $lines[] = '';

        // Group skills by package
        $grouped = [];
        foreach ($skills as $name => $info) {
            $grouped[$info['package']][$name] = $info;
        }

        foreach ($grouped as $packageName => $packageSkills) {
            $lines[] = '## '.ucwords(str_replace(['beartropy/', '-'], ['', ' '], $packageName)).' (`'.$packageName.'`)';
            $lines[] = '';

            foreach ($packageSkills as $name => $info) {
                $description = $this->extractFrontmatterField($info['content'], 'description')
                    ?? ($info['isGenerated'] ? 'Component documentation and examples' : 'Skill documentation');

                $lines[] = '### `/'.$name.'`';
                $lines[] = $description;
                $lines[] = '';
            }
        }

        $lines[] = '## How to Use';
        $lines[] = '';
        $lines[] = 'Invoke a skill by typing `/` followed by the skill name in Claude Code:';
        $lines[] = '';
        $lines[] = '```';
        $lines[] = '/bt-ui-setup';
        $lines[] = '```';
        $lines[] = '';
        $lines[] = '---';
        $lines[] = '';
        $lines[] = '*Generated by `php artisan beartropy:skills` — do not edit manually.*';
        $lines[] = '';

        return implode("\n", $lines);
    }

    /**
     * Extract a field value from YAML frontmatter.
     */
    protected function extractFrontmatterField(string $content, string $field): ?string
    {
        if (! str_starts_with($content, '---')) {
            return null;
        }

        $end = strpos($content, '---', 3);
        if ($end === false) {
            return null;
        }

        $frontmatter = substr($content, 3, $end - 3);
        if (preg_match('/^'.preg_quote($field, '/').'\s*:\s*(.+)$/m', $frontmatter, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    // ───────────────────────────────────────────────────────────────
    //  Installation
    // ───────────────────────────────────────────────────────────────

    /**
     * Install skills for the given agents.
     *
     * @param  string[]  $agents
     */
    protected function installForAgents(array $agents): int
    {
        $skills = $this->getSkillContents();

        if (empty($skills)) {
            $this->error('No skills found in any beartropy package.');

            return 1;
        }

        $exitCode = 0;

        foreach ($agents as $agent) {
            $result = $this->installForAgent($agent, $skills);

            if ($result !== 0) {
                $exitCode = $result;
            }
        }

        return $exitCode;
    }

    /**
     * Install skills for a single agent.
     *
     * @param  array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>  $skills
     */
    protected function installForAgent(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];

        return match ($config['format']) {
            'modular' => $this->installModular($agent, $skills),
            'single' => $this->installSingle($agent, $skills),
            'multi-file' => $this->installMultiFile($agent, $skills),
        };
    }

    /**
     * Install modular format (Claude Code): one directory per skill with SKILL.md.
     *
     * @param  array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>  $skills
     */
    protected function installModular(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);

        if (is_dir($targetDir)) {
            $existing = $this->findExistingSkills($targetDir);

            if ($existing) {
                if (! $this->option('force')) {
                    $this->warn("[{$agent}] The following skills already exist:");
                    foreach ($existing as $name) {
                        $this->line("  - {$name}");
                    }

                    if (! $this->confirm('Do you want to overwrite them?')) {
                        $this->info('Installation cancelled.');

                        return 0;
                    }
                }

                // Clean up all existing beartropy skills before installing
                foreach ($existing as $name) {
                    File::deleteDirectory($targetDir.'/'.$name);
                }
            }
        }

        File::ensureDirectoryExists($targetDir);

        // Group skills by package for output
        $installed = [];

        foreach ($skills as $skillName => $info) {
            $dest = $targetDir.'/'.$skillName;

            if ($info['isGenerated']) {
                // Generated skill — write SKILL.md directly
                File::ensureDirectoryExists($dest);
                File::put($dest.'/SKILL.md', $info['content']);
            } elseif ($info['sourceDir'] !== null) {
                // Static skill — copy entire directory
                File::copyDirectory($info['sourceDir'], $dest);
            }

            $installed[$info['package']][] = $skillName.($info['isGenerated'] ? ' (generated)' : '');
        }

        // Generate and write README
        File::put($targetDir.'/README.md', $this->buildReadme($skills));

        // Output grouped by package
        $this->info("[{$agent}] Beartropy skills installed successfully!");
        $this->newLine();

        foreach ($installed as $packageName => $names) {
            $this->line("  <fg=cyan>{$packageName}</>:");
            foreach ($names as $name) {
                $this->line("    <fg=green>✓</> {$name}");
            }
        }

        $this->newLine();
        $this->line('Skills are available as <fg=cyan>/bt-*</> slash commands in Claude Code.');

        return 0;
    }

    /**
     * Install single-file format (Codex, Copilot): all skills concatenated into one file.
     *
     * @param  array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>  $skills
     */
    protected function installSingle(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];
        $filenames = [
            'codex' => 'AGENTS.md',
            'copilot' => 'copilot-instructions.md',
        ];
        $filename = $filenames[$agent];
        $targetDir = $config['targetDir'] ? base_path($config['targetDir']) : base_path();
        $targetFile = $targetDir.'/'.$filename;

        if (File::exists($targetFile) && str_contains(File::get($targetFile), 'Beartropy')) {
            if (! $this->option('force')) {
                $this->warn("[{$agent}] {$filename} already contains Beartropy skills.");

                if (! $this->confirm('Do you want to overwrite it?')) {
                    $this->info('Installation cancelled.');

                    return 0;
                }
            }

            // Clean up existing file before writing
            File::delete($targetFile);
        }

        if ($config['targetDir']) {
            File::ensureDirectoryExists($targetDir);
        }

        $sections = [];
        foreach ($skills as $name => $info) {
            $content = $info['content'];
            $clean = $config['stripFrontmatter'] ? $this->stripFrontmatter($content) : $content;
            $sections[] = trim($clean);
        }

        $output = "<!-- Generated by Beartropy - do not edit manually -->\n\n"
            .implode("\n\n---\n\n", $sections)."\n";

        if ($config['maxFileSize'] && strlen($output) > $config['maxFileSize']) {
            $limit = number_format($config['maxFileSize'] / 1024).'KB';
            $actual = number_format(strlen($output) / 1024, 1).'KB';
            $this->warn("[{$agent}] Output is {$actual}, which exceeds the {$limit} limit for {$agent}.");
        }

        File::put($targetFile, $output);

        // Group output by package
        $grouped = [];
        foreach ($skills as $name => $info) {
            $grouped[$info['package']][] = $name;
        }

        $this->info("[{$agent}] Beartropy skills installed to {$filename}");
        $this->newLine();
        foreach ($grouped as $packageName => $names) {
            $this->line("  <fg=cyan>{$packageName}</>:");
            foreach ($names as $name) {
                $this->line("    <fg=green>✓</> {$name}");
            }
        }

        return 0;
    }

    /**
     * Install multi-file format (Cursor, Windsurf): one file per skill.
     *
     * @param  array<string, array{content: string, sourceDir: string|null, isGenerated: bool, package: string}>  $skills
     */
    protected function installMultiFile(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);
        $ext = $config['extension'];

        if (is_dir($targetDir)) {
            $existing = array_merge(
                glob($targetDir."/bt-*.{$ext}") ?: [],
                glob($targetDir."/beartropy-*.{$ext}") ?: []
            );

            if ($existing) {
                if (! $this->option('force')) {
                    $this->warn("[{$agent}] The following skill files already exist:");
                    foreach ($existing as $path) {
                        $this->line('  - '.basename($path));
                    }

                    if (! $this->confirm('Do you want to overwrite them?')) {
                        $this->info('Installation cancelled.');

                        return 0;
                    }
                }

                // Clean up all existing beartropy skill files before installing
                foreach ($existing as $file) {
                    File::delete($file);
                }
            }
        }

        File::ensureDirectoryExists($targetDir);

        $grouped = [];

        foreach ($skills as $name => $info) {
            $content = $info['content'];
            $clean = $config['stripFrontmatter'] ? $this->stripFrontmatter($content) : $content;
            $filename = "{$name}.{$ext}";
            $targetFile = $targetDir.'/'.$filename;

            File::put($targetFile, trim($clean)."\n");

            $fileSize = strlen($clean);
            $sizeWarning = '';

            if ($config['maxFileSize'] && $fileSize > $config['maxFileSize']) {
                $limit = number_format($config['maxFileSize'] / 1024).'KB';
                $actual = number_format($fileSize / 1024, 1).'KB';
                $sizeWarning = " <fg=yellow>(⚠ {$actual} exceeds {$limit} limit)</>";
            }

            $grouped[$info['package']][] = $filename.$sizeWarning;
        }

        $this->info("[{$agent}] Beartropy skills installed to {$config['targetDir']}/");
        $this->newLine();
        foreach ($grouped as $packageName => $names) {
            $this->line("  <fg=cyan>{$packageName}</>:");
            foreach ($names as $name) {
                $this->line("    <fg=green>✓</> {$name}");
            }
        }

        return 0;
    }

    // ───────────────────────────────────────────────────────────────
    //  Removal
    // ───────────────────────────────────────────────────────────────

    /**
     * Remove skills for the given agents.
     *
     * @param  string[]  $agents
     */
    protected function removeForAgents(array $agents): int
    {
        $anyRemoved = false;

        foreach ($agents as $agent) {
            if ($this->removeForAgent($agent)) {
                $anyRemoved = true;
            }
        }

        if (! $anyRemoved) {
            $this->info('No Beartropy skills found to remove.');
        }

        return 0;
    }

    /**
     * Remove skills for a single agent.
     */
    protected function removeForAgent(string $agent): bool
    {
        $config = self::AGENT_CONFIG[$agent];

        return match ($config['format']) {
            'modular' => $this->removeModular($agent),
            'single' => $this->removeSingle($agent),
            'multi-file' => $this->removeMultiFile($agent),
        };
    }

    /**
     * Remove modular skills (Claude Code).
     *
     * Scans for all bt-* and legacy beartropy-* directories.
     */
    protected function removeModular(string $agent): bool
    {
        $targetDir = base_path(self::AGENT_CONFIG[$agent]['targetDir']);

        if (! is_dir($targetDir)) {
            return false;
        }

        $removed = [];

        // Remove all bt-* directories (covers all packages)
        foreach (glob($targetDir.'/bt-*', GLOB_ONLYDIR) ?: [] as $dir) {
            File::deleteDirectory($dir);
            $removed[] = basename($dir);
        }

        // Remove legacy beartropy-* directories
        foreach (glob($targetDir.'/beartropy-*', GLOB_ONLYDIR) ?: [] as $dir) {
            File::deleteDirectory($dir);
            $removed[] = basename($dir);
        }

        // Remove README if it's ours
        $readme = $targetDir.'/README.md';
        if (File::exists($readme) && str_contains(File::get($readme), 'Beartropy')) {
            File::delete($readme);
            $removed[] = 'README.md';
        }

        if (! empty($removed)) {
            $this->info("[{$agent}] Beartropy skills removed:");
            foreach ($removed as $name) {
                $this->line("  <fg=red>✗</> {$name}");
            }

            return true;
        }

        return false;
    }

    /**
     * Remove single-file skills (Codex, Copilot).
     */
    protected function removeSingle(string $agent): bool
    {
        $config = self::AGENT_CONFIG[$agent];
        $filenames = [
            'codex' => 'AGENTS.md',
            'copilot' => 'copilot-instructions.md',
        ];
        $filename = $filenames[$agent];
        $targetDir = $config['targetDir'] ? base_path($config['targetDir']) : base_path();
        $targetFile = $targetDir.'/'.$filename;

        if (File::exists($targetFile) && str_contains(File::get($targetFile), 'Beartropy')) {
            File::delete($targetFile);
            $this->info("[{$agent}] Beartropy skills removed:");
            $this->line("  <fg=red>✗</> {$filename}");

            return true;
        }

        return false;
    }

    /**
     * Remove multi-file skills (Cursor, Windsurf).
     *
     * Scans for all bt-* and legacy beartropy-* files.
     */
    protected function removeMultiFile(string $agent): bool
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);
        $ext = $config['extension'];

        $files = array_merge(
            glob($targetDir."/bt-*.{$ext}") ?: [],
            glob($targetDir."/beartropy-*.{$ext}") ?: []
        );

        $removed = [];

        foreach ($files as $file) {
            File::delete($file);
            $removed[] = basename($file);
        }

        if (! empty($removed)) {
            $this->info("[{$agent}] Beartropy skills removed:");
            foreach ($removed as $name) {
                $this->line("  <fg=red>✗</> {$name}");
            }

            return true;
        }

        return false;
    }

    // ───────────────────────────────────────────────────────────────
    //  Helpers
    // ───────────────────────────────────────────────────────────────

    /**
     * Strip YAML frontmatter from content.
     */
    protected function stripFrontmatter(string $content): string
    {
        if (str_starts_with($content, '---')) {
            $end = strpos($content, '---', 3);
            if ($end !== false) {
                return ltrim(substr($content, $end + 3));
            }
        }

        return $content;
    }

    /**
     * Find existing Beartropy skill directories (bt-* and legacy beartropy-*).
     *
     * @return string[]
     */
    protected function findExistingSkills(string $targetDir): array
    {
        $existing = [];

        foreach (glob($targetDir.'/bt-*', GLOB_ONLYDIR) ?: [] as $dir) {
            $existing[] = basename($dir);
        }

        foreach (glob($targetDir.'/beartropy-*', GLOB_ONLYDIR) ?: [] as $dir) {
            $existing[] = basename($dir);
        }

        return $existing;
    }
}
