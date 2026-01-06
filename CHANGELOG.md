# Changelog

All notable changes to this project will be documented in this file.

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
