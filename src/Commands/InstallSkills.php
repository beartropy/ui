<?php

namespace Beartropy\Ui\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Artisan Command: Install Beartropy UI Claude Code Skills.
 *
 * Copies skill definitions into the project's `.claude/skills/` directory
 * so Claude Code discovers them as `/beartropy-*` slash commands.
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
        {--force : Overwrite existing skills without prompting}
        {--remove : Remove all installed Beartropy skills}';

    /**
     * @var string
     */
    protected $description = 'Install Beartropy UI Claude Code skills into your project';

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
        $targetDir = base_path('.claude/skills');

        if ($this->option('remove')) {
            return $this->removeSkills($targetDir);
        }

        return $this->installSkills($targetDir);
    }

    /**
     * Install all skills into the project.
     */
    protected function installSkills(string $targetDir): int
    {
        $packageRoot = dirname(__DIR__, 2);
        $sourceDir = $packageRoot.'/.claude/skills';
        $llmsDir = $packageRoot.'/docs/llms';

        if (! is_dir($sourceDir)) {
            $this->error('Package skills directory not found at: '.$sourceDir);

            return 1;
        }

        // Check for existing skills (unless --force)
        if (! $this->option('force') && is_dir($targetDir)) {
            $existing = $this->findExistingSkills($targetDir);

            if ($existing) {
                $this->warn('The following skills already exist:');
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

        // Copy static skills
        foreach ($this->staticSkills as $skill) {
            $src = $sourceDir.'/'.$skill;
            $dest = $targetDir.'/'.$skill;

            if (is_dir($src)) {
                File::copyDirectory($src, $dest);
                $installed[] = $skill;
            }
        }

        // Generate beartropy-component skill dynamically
        $componentSkillDir = $targetDir.'/beartropy-component';
        File::ensureDirectoryExists($componentSkillDir);

        $skillContent = $this->buildComponentSkill($llmsDir);
        File::put($componentSkillDir.'/SKILL.md', $skillContent);
        $installed[] = 'beartropy-component (generated from '.count(glob($llmsDir.'/*.md')).' component docs)';

        // Copy README
        $readmeSrc = $sourceDir.'/README.md';
        if (File::exists($readmeSrc)) {
            File::copy($readmeSrc, $targetDir.'/README.md');
        }

        $this->info('Beartropy UI skills installed successfully!');
        $this->newLine();
        foreach ($installed as $name) {
            $this->line("  <fg=green>✓</> {$name}");
        }
        $this->newLine();
        $this->line('Skills are available as <fg=cyan>/beartropy-*</> slash commands in Claude Code.');

        return 0;
    }

    /**
     * Remove all installed Beartropy skills.
     */
    protected function removeSkills(string $targetDir): int
    {
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
        if (File::exists($readme)) {
            // Only remove if it's ours (contains "Beartropy")
            if (str_contains(File::get($readme), 'Beartropy')) {
                File::delete($readme);
                $removed[] = 'README.md';
            }
        }

        if (empty($removed)) {
            $this->info('No Beartropy skills found to remove.');

            return 0;
        }

        $this->info('Beartropy UI skills removed:');
        foreach ($removed as $name) {
            $this->line("  <fg=red>✗</> {$name}");
        }

        return 0;
    }

    /**
     * Build the beartropy-component SKILL.md from LLM docs.
     */
    protected function buildComponentSkill(string $llmsDir): string
    {
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

When a user asks about a specific component:
1. Find the relevant section below
2. Provide the props, slots, and examples they need
3. Show practical usage patterns
4. Mention related components that work well together

---

SKILL;

        $docs = glob($llmsDir.'/*.md');
        sort($docs);

        $sections = [];
        foreach ($docs as $docPath) {
            $sections[] = trim(File::get($docPath));
        }

        return $header."\n".implode("\n\n---\n\n", $sections)."\n";
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
