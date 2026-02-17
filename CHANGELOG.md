# Changelog

All notable changes to this project will be documented in this file.

## [v1.0.5] - 2026-02-17

### Added
- **ThemeHead**: New `<x-bt-theme-head />` component — a blocking inline script for `<head>` that applies the saved dark/light theme before CSS renders, preventing the light-to-dark FOUC on page load and during `wire:navigate` navigation.

### Changed
- **ToggleTheme**: `initTheme()` now guards against duplicate `livewire:navigated` listeners when `<x-bt-theme-head />` is present.

## [v1.0.4] - 2026-02-16

### Added
- **Datetime**: Added `size` prop to support magic size attributes.

## [v1.0.3] - 2026-02-14

### Changed
- **InstallSkills**: Cross-package skill discovery — auto-discovers skills from all installed `beartropy/*` packages via convention-based scanning and optional `skills.json` manifest.
- **InstallSkills**: Installation now cleans up stale skills before writing the new set, ensuring `--force` produces a clean state.
- **InstallSkills**: README.md is now dynamically generated from discovered skills instead of copied from a static file.
- **InstallSkills**: Output grouped by package with generic `/bt-*` slash command hint.
- **Skills**: Renamed skill directories from `beartropy-*` to `bt-ui-*` prefix for consistency with the convention-based discovery pattern.
- **MCP Tools**: Updated namespace references from `beartropy` to `bt` prefix.
- **Docs**: Updated AI assistant documentation and project README.

## [v1.0.2] - 2026-02-13

### Added
- **MCP**: Add `beartropy-project-context` tool that returns project configuration (version, prefix, icon set, component defaults).

### Changed
- **MCP**: Make `ListComponents::CATEGORIES` constant public for cross-tool reuse.

## [v1.0.1] - 2026-02-13

### Added
- **MCP Integration**: Added Laravel Boost MCP tools (`beartropy-component-docs`, `beartropy-list-components`) for automatic component documentation access.
- **Skills**: Added multi-agent support to `beartropy:skills` command (Claude, Codex, Copilot, Cursor, Windsurf).
- **Skills**: Rewritten all published skills with improved v2.0.0 content.
- **Dev Skills**: Added internal `release` and `beartropy-docs-maintenance` skills for maintainers.

## [v0.11.22] - 2026-02-02

### Added
- **ToggleTheme**: Fix custom icons not showing

## [v0.11.21] - 2026-01-30

### Added
- **ChatInput**: Added `border-color` prop to allow using a specific preset's border style independently.

## [v0.11.20] - 2026-01-30

### Added
- **ChatInput**: Added `white` preset to `chat-input` configuration.

## [v0.11.19] - 2026-01-28

### Fixed
- **Slider**: Fixed external trigger events not working across Livewire components by using native `addEventListener` in `init()`.

## [v0.11.18] - 2026-01-28

### Added
- **Slider**: Added `name` prop and external trigger support via `$dispatch` events (`open-slider`, `close-slider`, `toggle-slider`).

## [v0.11.17] - 2026-01-16

### Changed
- Updated modal to appear at approx. 1/3 from top by default, centering only when requested.

## [v0.11.16] - 2026-01-14

### Fixed
- Fixed base dropdown positioning.

## [v0.11.15] - 2026-01-12

### Fixed
- Improved `chat-input` textarea stability with hysteresis and debounced layout changes.

## [v0.11.14] - 2026-01-12

### Fixed
- Fixed glitch in `chat-input` textarea resize preventing internal scroll.

## [v0.11.13] - 2026-01-11

### Changed
- 

## [v0.11.12] - 2026-01-11

### Changed
- Added `x-cloak` to `chat-input` component to prevent flash of unstyled content.

## [v0.11.11] - 2026-01-11

### Changed
- Removed `placeholder:text-sm` from all chat-input color presets so placeholder text matches input text size.

## [v0.11.10] - 2026-01-10

### Changed
- Updated chat input preset wrapper backgrounds to use bg-{color}-300/50 instead of bg-white.

## [v0.11.9] - 2026-01-10

### Changed
- Refined ChatInput styles (rounded look, responsive layout, conditional border) and updated all presets.

## [v0.11.8] - 2026-01-06

### Changed
- Fix Dialog Button Visibility

## [v0.11.7] - 2026-01-06

### Changed
- Added safelist for dialog button colors to ensure visibility in light mode. 

## [v0.11.6] - 2026-01-06

### Changed
- Fixed dialog button visibility in light mode (success, warning) and added missing `confirm` variant. 

## [v0.11.5] - 2026-01-02

### Fixed
- Added `x-cloak` to slider component to prevent initial loading glitch.

## [v0.11.4] - 2026-01-02

### Changed
- Added comprehensive feature tests for UI components.
- Updated documentation.

## [v0.11.3] - 2025-12-31

### Changed
- Added docs

## [v0.11.2] - 2025-12-30

### Changed
- **Modal**: Enabled attribute merging for `title` and `footer` slots to allow custom classes and attributes.
- **Modal**: Refactored `modal-root` component for better attribute handling and cleaner code.

## [v0.11.1] - 2025-12-26

### Changed
- Fixed chat input layout clearing issue 

## [v0.11.0] - 2025-12-25

### Added
- **ChatInput**: Added `tools` and `actions` slots for enhanced customization.
- **ChatInput**: Added `stacked`, `submitOnEnter`, and `action` props for layout control and behavior.
- **ChatInput**: Implemented auto-expanding textarea with dynamic single-line/stacked layout state.
- **Icon**: Added support for `beartropy` custom icon set.

### Changed
- Refactored internal Livewire setup.

## [v0.10.0] - 2025-12-24

### Added
- Added `ChatInput` component.

## [v0.9.44] - 2025-12-24

### Fixed
- Fixed `Icon` component not forwarding attributes (e.g., event listeners) to the underlying element.

## [v0.9.43] - 2025-12-19

### Fixed
- Fixed toast component bug where notification would disappear even when hovered (implemented global timer registry to bypass Alpine proxy issues).
