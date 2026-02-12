# x-bt-debug-breakpoints — AI Reference

## Component Tag
```blade
<x-bt-debug-breakpoints />
<x-bt-debug-breakpoints env="staging" :expanded="true" />
```

## Architecture
- `DebugBreakpoints` extends `BeartropyComponent` (inherits `HasPresets`, `HasErrorBag`)
- Renders `debug-breakpoints.blade.php` — inline Alpine.js, no separate JS module
- No preset file — styling is hardcoded (red color scheme, dev-only tool)
- Environment-gated: wrapped in `@if (app()->environment($env))`
- Renders zero HTML in non-matching environments

## Props (Constructor)

| Prop | Type | Default | Blade Attribute |
|------|------|---------|--------------------|
| expanded | `bool` | `false` | `:expanded="true"` |
| env | `string` | `'local'` | `env="staging"` |

## Alpine.js State
```js
{
    expanded: boolean,  // From localStorage or $expanded prop
    width: number,      // window.innerWidth, updated on resize

    toggle() {
        // Flip expanded, persist to localStorage
    }
}
```

## Template Structure
```
@if (app()->environment($env))
div.fixed.bottom-0.right-0.z-[100][x-data][style="display:none"][x-show="true"]
├── div[x-show="expanded"]  (expanded bar)
│   ├── div.font-bold  (breakpoint labels: XS|SM|MD|LG|XL|2XL with responsive visibility)
│   ├── div.w-px  (separator)
│   ├── div  (width display: <span x-text="width"> px)
│   ├── div.w-px  (separator)
│   └── button[@click="toggle()"][aria-label="Minimize"]  (minimize icon)
└── button[x-show="!expanded"][@click="toggle()"][aria-label="Show debug info"]  (floating circle)
@endif
```

## Breakpoint Detection
Uses Tailwind responsive visibility classes on `<span>` elements:
- `block sm:hidden` → XS
- `hidden sm:block md:hidden` → SM
- `hidden md:block lg:hidden` → MD
- `hidden lg:block xl:hidden` → LG
- `hidden xl:block 2xl:hidden` → XL
- `hidden 2xl:block` → 2XL

## localStorage
- Key: `debug_breakpoints_expanded`
- Values: `'true'` or `'false'` (strings)
- Read on init, written on every toggle
- Overrides the `$expanded` prop after first interaction

## Common Patterns

```blade
{{-- Default (local only, starts minimized) --}}
<x-bt-debug-breakpoints />

{{-- Start expanded --}}
<x-bt-debug-breakpoints :expanded="true" />

{{-- Show in staging --}}
<x-bt-debug-breakpoints env="staging" />
```

## Key Notes
- FOUC prevention: `style="display: none"` + `x-show="true"` ensures Alpine controls visibility
- Zero output in non-matching environments — safe to include in layouts unconditionally
- `x-init="$watch('width', value => value)"` is a no-op watcher (kept for potential future use)
- Red color scheme is intentional — stands out as a dev tool, not mistaken for UI
- `z-[100]` ensures it floats above most app content
- Lang keys: `beartropy-ui::ui.minimize`, `beartropy-ui::ui.show_debug_info`
