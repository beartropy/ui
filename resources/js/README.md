# Beartropy UI - JavaScript Development

## Structure

```
resources/js/
├── index.js                 # Entry point (imports all modules)
├── beartropy-ui.js          # Bundled output (auto-generated)
├── modules/
│   ├── dialog.js
│   ├── modal.js
│   ├── toast.js
│   ├── table.js
│   ├── datetime-picker.js
│   ├── time-picker.js
│   ├── tag-input.js
│   └── confirm.js
```

## Development

### Install Dependencies
```bash
npm install
```

### Build Commands
```bash
# Build once
npm run build

# Watch mode (auto-rebuild on file changes)
npm run watch
```

### Editing Workflow
1. Edit files in `resources/js/modules/`
2. Run `npm run watch` (or `npm run build`)
3. The bundled `beartropy-ui.js` is automatically updated

## Module Format

All modules use ES6 exports:

```javascript
// Example module
export function myFunction() {
    // ...
}

export const myConstant = { ... };
```

The entry point (`index.js`) imports and exposes them on `window.$beartropy`:

```javascript
import { myFunction } from './modules/my-module.js';
window.$beartropy.myFunction = myFunction;
```

## Benefits

- ✅ **Auto-rebuild**: Watch mode for instant feedback
- ✅ **Modern syntax**: ES6 modules
- ✅ **Smaller bundle**: Optimized output
- ✅ **Better organization**: One file per feature
- ✅ **No breaking changes**: Same API

## Important

⚠️ **DO NOT edit `beartropy-ui.js` directly** - it's auto-generated!

Always edit files in `resources/js/modules/` and rebuild.
