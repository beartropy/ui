# Debug Breakpoints

A development utility that displays the current Tailwind CSS breakpoint and viewport width in pixels, fixed to the bottom-right corner of the screen. Only renders when the app environment matches the `env` prop.

## Basic Usage

```blade
<x-bt-debug-breakpoints />
```

Shows a small red floating button. Click to expand and see the current breakpoint (XS, SM, MD, LG, XL, 2XL) and viewport width.

## Environment

By default, the component only renders when `app()->environment('local')`. Pass a custom environment to change this:

```blade
{{-- Only show in staging --}}
<x-bt-debug-breakpoints env="staging" />
```

In production, the component renders nothing — zero HTML output.

## Start Expanded

```blade
<x-bt-debug-breakpoints :expanded="true" />
```

The expanded/minimized state is persisted to `localStorage`, so the prop only sets the initial state on first visit.

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| expanded | `bool` | `false` | Initial expansion state (overridden by localStorage) |
| env | `string` | `'local'` | Environment to render in |

## Behavior

- **Minimized**: Small red floating button (50% opacity, grows on hover)
- **Expanded**: Red bar showing breakpoint label + pixel width + minimize button
- **localStorage**: Expansion state persists across page loads via `debug_breakpoints_expanded` key
- **Resize**: Width updates in real time via `@resize.window`
- **FOUC prevention**: Starts with `display: none`, Alpine reveals it immediately

## Accessibility

- Both toggle buttons have `aria-label` (localized)
- `aria-expanded` reflects current state
- Decorative SVGs have `aria-hidden="true"`
