<?php

namespace Beartropy\Ui\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Artisan Command: Install Beartropy UI AI coding skills.
 *
 * Installs skill definitions for multiple AI coding tools:
 * Claude Code, Codex, Copilot, Cursor, and Windsurf.
 *
 * The `beartropy-component` skill is generated dynamically by concatenating
 * all per-component LLM reference docs from `docs/llms/`.
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
    protected $description = 'Install Beartropy UI skills for AI coding tools';

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
     * Skills that are copied as-is from the package.
     *
     * @var string[]
     */
    protected array $staticSkills = [
        'beartropy-setup',
        'beartropy-form',
        'beartropy-livewire',
        'beartropy-patterns',
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

    /**
     * Install skills for the given agents.
     *
     * @param  string[]  $agents
     */
    protected function installForAgents(array $agents): int
    {
        $packageRoot = dirname(__DIR__, 2);
        $sourceDir = $packageRoot.'/.claude/skills';

        if (! is_dir($sourceDir)) {
            $this->error('Package skills directory not found at: '.$sourceDir);

            return 1;
        }

        $skills = $this->getSkillContents();
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
     * @param  array<string, string>  $skills
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
     * @param  array<string, string>  $skills
     */
    protected function installModular(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);
        $packageRoot = dirname(__DIR__, 2);
        $sourceDir = $packageRoot.'/.claude/skills';

        if (! $this->option('force') && is_dir($targetDir)) {
            $existing = $this->findExistingSkills($targetDir);

            if ($existing) {
                $this->warn("[{$agent}] The following skills already exist:");
                foreach ($existing as $name) {
                    $this->line("  - {$name}");
                }

                if (! $this->confirm('Do you want to overwrite them?')) {
                    $this->info('Installation cancelled.');

                    return 0;
                }
            }
        }

        File::ensureDirectoryExists($targetDir);

        $installed = [];

        foreach ($this->staticSkills as $skill) {
            $src = $sourceDir.'/'.$skill;
            $dest = $targetDir.'/'.$skill;

            if (is_dir($src)) {
                File::copyDirectory($src, $dest);
                $installed[] = $skill;
            }
        }

        $componentSkillDir = $targetDir.'/beartropy-component';
        File::ensureDirectoryExists($componentSkillDir);
        File::put($componentSkillDir.'/SKILL.md', $skills['beartropy-component']);
        $componentDocCount = count(glob(dirname(__DIR__, 2).'/docs/llms/*.md'));
        $installed[] = "beartropy-component (generated from {$componentDocCount} component docs)";

        $readmeSrc = $sourceDir.'/README.md';
        if (File::exists($readmeSrc)) {
            File::copy($readmeSrc, $targetDir.'/README.md');
        }

        $this->info("[{$agent}] Beartropy UI skills installed successfully!");
        $this->newLine();
        foreach ($installed as $name) {
            $this->line("  <fg=green>✓</> {$name}");
        }
        $this->newLine();
        $this->line('Skills are available as <fg=cyan>/beartropy-*</> slash commands in Claude Code.');

        return 0;
    }

    /**
     * Install single-file format (Codex, Copilot): all skills concatenated into one file.
     *
     * @param  array<string, string>  $skills
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

        if (! $this->option('force') && File::exists($targetFile)) {
            if (str_contains(File::get($targetFile), 'Beartropy')) {
                $this->warn("[{$agent}] {$filename} already contains Beartropy skills.");

                if (! $this->confirm('Do you want to overwrite it?')) {
                    $this->info('Installation cancelled.');

                    return 0;
                }
            }
        }

        if ($config['targetDir']) {
            File::ensureDirectoryExists($targetDir);
        }

        $sections = [];
        foreach ($skills as $name => $content) {
            $clean = $config['stripFrontmatter'] ? $this->stripFrontmatter($content) : $content;
            $sections[] = trim($clean);
        }

        $output = "<!-- Generated by Beartropy UI - do not edit manually -->\n\n"
            .implode("\n\n---\n\n", $sections)."\n";

        if ($config['maxFileSize'] && strlen($output) > $config['maxFileSize']) {
            $limit = number_format($config['maxFileSize'] / 1024).'KB';
            $actual = number_format(strlen($output) / 1024, 1).'KB';
            $this->warn("[{$agent}] Output is {$actual}, which exceeds the {$limit} limit for {$agent}.");
        }

        File::put($targetFile, $output);

        $this->info("[{$agent}] Beartropy UI skills installed to {$filename}");
        $this->newLine();
        foreach (array_keys($skills) as $name) {
            $this->line("  <fg=green>✓</> {$name}");
        }

        return 0;
    }

    /**
     * Install multi-file format (Cursor, Windsurf): one file per skill.
     *
     * @param  array<string, string>  $skills
     */
    protected function installMultiFile(string $agent, array $skills): int
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);
        $ext = $config['extension'];

        if (! $this->option('force') && is_dir($targetDir)) {
            $existing = glob($targetDir."/beartropy-*.{$ext}");

            if ($existing) {
                $this->warn("[{$agent}] The following skill files already exist:");
                foreach ($existing as $path) {
                    $this->line('  - '.basename($path));
                }

                if (! $this->confirm('Do you want to overwrite them?')) {
                    $this->info('Installation cancelled.');

                    return 0;
                }
            }
        }

        File::ensureDirectoryExists($targetDir);

        $installed = [];

        foreach ($skills as $name => $content) {
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

            $installed[] = $filename.$sizeWarning;
        }

        $this->info("[{$agent}] Beartropy UI skills installed to {$config['targetDir']}/");
        $this->newLine();
        foreach ($installed as $name) {
            $this->line("  <fg=green>✓</> {$name}");
        }

        return 0;
    }

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
     */
    protected function removeModular(string $agent): bool
    {
        $targetDir = base_path(self::AGENT_CONFIG[$agent]['targetDir']);
        $allSkills = array_merge($this->staticSkills, ['beartropy-component']);
        $removed = [];

        foreach ($allSkills as $skill) {
            $path = $targetDir.'/'.$skill;
            if (is_dir($path)) {
                File::deleteDirectory($path);
                $removed[] = $skill;
            }
        }

        $readme = $targetDir.'/README.md';
        if (File::exists($readme) && str_contains(File::get($readme), 'Beartropy')) {
            File::delete($readme);
            $removed[] = 'README.md';
        }

        if (! empty($removed)) {
            $this->info("[{$agent}] Beartropy UI skills removed:");
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
            $this->info("[{$agent}] Beartropy UI skills removed:");
            $this->line("  <fg=red>✗</> {$filename}");

            return true;
        }

        return false;
    }

    /**
     * Remove multi-file skills (Cursor, Windsurf).
     */
    protected function removeMultiFile(string $agent): bool
    {
        $config = self::AGENT_CONFIG[$agent];
        $targetDir = base_path($config['targetDir']);
        $ext = $config['extension'];
        $files = glob($targetDir."/beartropy-*.{$ext}");
        $removed = [];

        foreach ($files as $file) {
            File::delete($file);
            $removed[] = basename($file);
        }

        if (! empty($removed)) {
            $this->info("[{$agent}] Beartropy UI skills removed:");
            foreach ($removed as $name) {
                $this->line("  <fg=red>✗</> {$name}");
            }

            return true;
        }

        return false;
    }

    /**
     * Get all skill contents keyed by skill name.
     *
     * @return array<string, string>
     */
    protected function getSkillContents(): array
    {
        $packageRoot = dirname(__DIR__, 2);
        $sourceDir = $packageRoot.'/.claude/skills';
        $llmsDir = $packageRoot.'/docs/llms';
        $skills = [];

        foreach ($this->staticSkills as $skill) {
            $path = $sourceDir.'/'.$skill.'/SKILL.md';
            if (File::exists($path)) {
                $skills[$skill] = File::get($path);
            }
        }

        $skills['beartropy-component'] = $this->buildComponentSkill($llmsDir);

        return $skills;
    }

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
     * Build the beartropy-component SKILL.md from LLM docs.
     *
     * Uses the static SKILL.md as the header (which includes intent-mapping
     * and component selection guides), then appends all per-component LLM
     * reference docs.
     */
    protected function buildComponentSkill(string $llmsDir): string
    {
        $packageRoot = dirname(__DIR__, 2);
        $staticSkill = $packageRoot.'/.claude/skills/beartropy-component/SKILL.md';

        if (File::exists($staticSkill)) {
            $header = trim(File::get($staticSkill))."\n\n---\n\n# Per-Component Reference\n\nDetailed props, slots, architecture, and examples for every component.\n\n---\n\n";
        } else {
            $header = <<<'SKILL'
---
name: beartropy-component
description: Get detailed information and examples for specific Beartropy UI components
version: 2.0.0
author: Beartropy
tags: [beartropy, ui, components, documentation, examples]
---

# Beartropy Component Reference

You are an expert in Beartropy UI components. Below is the complete reference for every component in the library.

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

    /**
     * Find existing beartropy skill directories.
     *
     * @return string[]
     */
    protected function findExistingSkills(string $targetDir): array
    {
        $allSkills = array_merge($this->staticSkills, ['beartropy-component']);
        $existing = [];

        foreach ($allSkills as $skill) {
            if (is_dir($targetDir.'/'.$skill)) {
                $existing[] = $skill;
            }
        }

        return $existing;
    }
}
