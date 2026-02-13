---
name: release
description: Release a new version of Beartropy UI — bumps version, updates changelog, commits, tags, and pushes
version: 1.0.0
author: Beartropy
tags: [beartropy, release, versioning, git, changelog]
---

# Release Beartropy UI

You are releasing a new version of the Beartropy UI package. The user invokes this skill with `/release {type}` where `{type}` is one of: `major`, `minor`, or `patch`.

**Working directory:** `/var/www/beartropy/ui`

---

## Step-by-step procedure

### 1. Validate the argument

The first argument MUST be one of: `major`, `minor`, `patch`.

If missing or invalid, stop and ask the user:
> Please specify a release type: `/release major`, `/release minor`, or `/release patch`

### 2. Determine the new version

Read the current version from `composer.json` (the `"version"` field). Parse it as `MAJOR.MINOR.PATCH` and bump according to the argument:

| Type | Rule | Example (from 1.0.0) |
|---|---|---|
| `major` | MAJOR+1, reset MINOR and PATCH to 0 | `2.0.0` |
| `minor` | MINOR+1, reset PATCH to 0 | `1.1.0` |
| `patch` | PATCH+1 | `1.0.1` |

The tag name is `v{NEW_VERSION}` (e.g., `v1.0.1`).

### 3. Gather unreleased changes

Run `git log` from the last tag to HEAD to collect commit messages:

```bash
git log $(git describe --tags --abbrev=0)..HEAD --oneline --no-merges
```

Present the commits to the user and ask them to confirm or edit the changelog entry. Group changes under the appropriate heading:

- `### Added` — new features or components
- `### Changed` — modifications to existing behavior
- `### Fixed` — bug fixes
- `### Removed` — removed features or deprecated code

Only include headings that have entries. Follow the existing CHANGELOG.md format exactly:

```markdown
## [vX.Y.Z] - YYYY-MM-DD

### Added
- **ComponentName**: Description of what was added.

### Fixed
- **ComponentName**: Description of what was fixed.
```

### 4. Update CHANGELOG.md

Insert the new version entry at the top of the file, immediately after the `# Changelog` header and the blank line that follows it. Preserve all existing entries below.

### 5. Update composer.json

Change the `"version"` field to the new version string (without the `v` prefix).

### 6. Review changes with the user

Show the user a summary of what will be committed:
- The new version number
- The changelog entry
- The composer.json version change

Ask for confirmation before proceeding with git operations.

### 7. Configure git user

```bash
cd /var/www/beartropy/ui && git config user.name "beartropy" && git config user.email "beartropy@gmail.com"
```

### 8. Commit all changes

The commit message MUST match the changelog entry content. Use the version header as the first line, then the full changelog body:

```
Release vX.Y.Z

### Added
- **ComponentName**: Description of what was added.

### Fixed
- **ComponentName**: Description of what was fixed.
```

Use a heredoc to preserve formatting:

```bash
cd /var/www/beartropy/ui && git add -A && git commit -m "$(cat <<'EOF'
Release vX.Y.Z

{paste the exact changelog body here, without the ## [vX.Y.Z] - date header}
EOF
)"
```

### 9. Push the commit

```bash
cd /var/www/beartropy/ui && git push
```

### 10. Create and push the tag

```bash
cd /var/www/beartropy/ui && git tag vX.Y.Z && git push origin vX.Y.Z
```

### 11. Create a GitHub release

Create a GitHub release using `gh`, with the changelog entry as the release notes:

```bash
cd /var/www/beartropy/ui && gh release create vX.Y.Z --title "vX.Y.Z" --notes "$(cat <<'EOF'
{paste the exact changelog body here — the ### Added/Changed/Fixed sections}
EOF
)"
```

### 12. Confirm success

Report the release summary:
- Version: `vX.Y.Z`
- Tag: pushed
- GitHub release: created
- Changelog: updated

Remind the user:
- If the docs site uses Packagist, the new version will be available after Packagist syncs (usually within minutes).
- Run `./composer-swap.sh prod` in the docs project and `composer update beartropy/ui` to pull the new version.
