---
name: bt-ui-docs-maintenance
description: Update documentation and AI integrations (skills + MCP) when adding or modifying Beartropy UI components
version: 1.0.0
author: Beartropy
tags: [beartropy, docs, maintenance, internal, mcp, skills]
---

# Beartropy Docs & Integrations Maintenance

You are maintaining the Beartropy UI documentation and AI integration layer. Component knowledge is exposed through **two independent channels** that must stay in sync:

| Channel | Mechanism | Audience |
|---|---|---|
| **Skills** (`beartropy:skills`) | Artisan command copies/generates files into the user's project (`.claude/skills/`, `.cursor/rules/`, etc.) | AI agents with skill files installed |
| **MCP** (Laravel Boost) | Two MCP tools auto-registered when Boost is present — agents call them on demand | AI agents connected to the app's Boost MCP server |

---

## File Locations

```
docs/
├── llms/{name}.md          ← LLM reference (architecture, props, slots, presets)
└── components/{name}.md    ← User reference (usage examples, prop table, tips)

src/
├── Mcp/Tools/
│   ├── ComponentDocs.php   ← Reads docs/llms + docs/components dynamically
│   └── ListComponents.php  ← Hardcoded CATEGORIES const (forms vs ui)
└── Commands/
    └── InstallSkills.php   ← Generates bt-ui-component skill from docs/llms/*.md
```

---

## When Adding a New Component

### 1. Create both doc files

**`docs/llms/{name}.md`** — LLM-optimized reference. Follow this structure:

```markdown
# x-bt-{name} — AI Reference

## Component Tag
\`\`\`blade
<x-bt-{name} />
\`\`\`

## Architecture
- `{ClassName}` → extends `BeartropyComponent`
- Renders: `{name}.blade.php`
- Presets: `resources/views/presets/{name}.php`
- Sizes: global `resources/views/presets/sizes.php`

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| ... | ... | ... | ... |

## Slots

| Slot | Purpose | Default |
|------|---------|---------|
| ... | ... | ... |

## Available Colors
(list from preset file)

## Available Sizes
(list from sizes.php)

## Usage Examples
\`\`\`blade
...
\`\`\`

## Common Patterns
...
```

**`docs/components/{name}.md`** — Human-readable reference. Follow this structure:

```markdown
# {Component Name}

Brief one-line description.

## Basic Usage

\`\`\`blade
<x-bt-{name} ... />
\`\`\`

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| ... | ... | ... | ... |

## Slots

| Slot | Description |
|------|-------------|
| ... | ... |

## Colors
...

## Sizes
...

## Examples

### {Pattern Name}
\`\`\`blade
...
\`\`\`
```

Use existing doc files as reference — pick a component with similar complexity (e.g., `toggle.md` for form components, `modal.md` for UI components with slots).

### 2. Update MCP category map

Edit `src/Mcp/Tools/ListComponents.php` and add the component name to the appropriate category in the `CATEGORIES` constant:

```php
protected const CATEGORIES = [
    'forms' => [
        // ... add here if it's a form input component
    ],
    'ui' => [
        // ... add here if it's a display/layout component
    ],
];
```

**Category guidelines:**
- **forms** — components that capture user input (`input`, `select`, `toggle`, `checkbox`, etc.)
- **ui** — components that display content or control layout (`alert`, `modal`, `card`, `badge`, etc.)

Keep each list in **alphabetical order**.

### 3. Skills — no code change needed

`InstallSkills::buildComponentSkill()` auto-discovers all `docs/llms/*.md` files via glob. Creating the LLM doc file is sufficient. Users pick it up by re-running `beartropy:skills`.

### 4. Checklist

- [ ] `docs/llms/{name}.md` created
- [ ] `docs/components/{name}.md` created
- [ ] `src/Mcp/Tools/ListComponents.php` — component added to `CATEGORIES`
- [ ] Verify: `php artisan tinker` → `(new \Beartropy\Ui\Mcp\Tools\ListComponents)->handle(new \Laravel\Mcp\Request([]))` shows the new component
- [ ] Verify: `php artisan tinker` → `(new \Beartropy\Ui\Mcp\Tools\ComponentDocs)->handle(new \Laravel\Mcp\Request(['component' => '{name}']))` returns docs

---

## When Modifying an Existing Component

### Props/slots/behavior changed

1. Update `docs/llms/{name}.md` — keep props table, architecture section, and examples accurate
2. Update `docs/components/{name}.md` — update the user-facing prop table and examples

### No other code changes needed

- **MCP:** `ComponentDocs` reads files at request time — changes are instant
- **Skills:** Users re-run `beartropy:skills` to regenerate — the command re-reads all `docs/llms/*.md`
- **CATEGORIES:** Only needs updating if a component is added or removed, not modified

---

## When Removing a Component

1. Delete `docs/llms/{name}.md`
2. Delete `docs/components/{name}.md`
3. Remove from `CATEGORIES` in `src/Mcp/Tools/ListComponents.php`
4. Skills auto-exclude it on next `beartropy:skills` run (glob won't find the deleted file)

---

## How Each Channel Works

### Skills (`InstallSkills` command)

```
docs/llms/*.md  ──glob──▶  buildComponentSkill()  ──▶  bt-ui-component/SKILL.md
                           (concatenates all docs)      (written to user's project)
```

- Static skills (`bt-ui-setup`, `bt-ui-form`, `bt-ui-livewire`, `bt-ui-patterns`) are copied as-is from `.claude/skills/`
- `bt-ui-component` is **generated** by concatenating all `docs/llms/*.md` files
- Supports 5 agents: Claude, Codex, Copilot, Cursor, Windsurf — each with format-specific output

### MCP (Boost integration)

```
BeartropyUiServiceProvider::boot()
  └─ class_exists(BoostServiceProvider) ?
       └─ config(['boost.mcp.tools.include' => [...]])
            ├─ ComponentDocs  ──reads──▶  docs/llms/{name}.md + docs/components/{name}.md
            └─ ListComponents ──reads──▶  CATEGORIES const + docs/llms/*.md (for validation)
```

- Tools are only loaded when `laravel/boost` is installed (conditional `class_exists` check)
- `ComponentDocs` concatenates both doc files separated by `---` and returns as `Response::text()`
- `ListComponents` returns JSON grouped by category, with an `other` fallback for unmapped components
- `laravel/mcp` is NOT a dependency of `beartropy/ui` — the tool classes use `Illuminate\Contracts\JsonSchema\JsonSchema` (the contract interface), not the concrete MCP class

---

## Quick Reference

| Action | docs/llms | docs/components | ListComponents::CATEGORIES | InstallSkills | ComponentDocs |
|---|---|---|---|---|---|
| Add component | Create | Create | Add entry | Auto (glob) | Auto (file read) |
| Modify component | Update | Update | No change | Auto (glob) | Auto (file read) |
| Remove component | Delete | Delete | Remove entry | Auto (glob) | Auto (file read) |
