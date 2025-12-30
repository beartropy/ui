# Changelog

All notable changes to this project will be documented in this file.

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
