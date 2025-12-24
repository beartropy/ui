# Changelog

All notable changes to this project will be documented in this file.

## [v0.9.44] - 2025-12-24

### Fixed
- Fixed `Icon` component not forwarding attributes (e.g., event listeners) to the underlying element.

## [v0.9.43] - 2025-12-19

### Fixed
- Fixed toast component bug where notification would disappear even when hovered (implemented global timer registry to bypass Alpine proxy issues).
