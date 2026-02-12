# Alert

A contextual alert component for user feedback with customizable colors, icons, and dismissibility.

## Basic Usage

```blade
<x-bt-alert success>Your changes have been saved.</x-bt-alert>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string\|null` | `null` | Optional title/heading displayed above slot content |
| `icon` | `string\|null` | `null` | Custom icon name (overrides preset icon) |
| `noIcon` | `bool` | `false` | Hides the icon entirely |
| `dismissible` | `bool` | `false` | Adds a dismiss button |
| `color` | `string\|null` | `null` | Alert color (alternative to magic attribute) |
| `class` | `string` | `''` | Additional CSS classes |

## Colors

Set the color using a magic attribute or the `color` prop.

### Semantic Colors (with preset icons)

```blade
<x-bt-alert success>Saved.</x-bt-alert>        {{-- icon: check-circle --}}
<x-bt-alert info>FYI.</x-bt-alert>              {{-- icon: exclamation-circle --}}
<x-bt-alert warning>Watch out.</x-bt-alert>     {{-- icon: exclamation-triangle --}}
<x-bt-alert error>Something broke.</x-bt-alert> {{-- icon: x-circle --}}
```

### Named Colors (no preset icon)

```blade
<x-bt-alert red>Red alert.</x-bt-alert>
<x-bt-alert blue>Blue alert.</x-bt-alert>
<x-bt-alert purple>Purple alert.</x-bt-alert>
```

All 26 colors: `beartropy`, `success`, `info`, `warning`, `error`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`.

You can also use the `color` prop for dynamic values:

```blade
<x-bt-alert :color="$alertType">Dynamic alert.</x-bt-alert>
```

## Title

```blade
{{-- Title + content --}}
<x-bt-alert success title="Payment Received">
    Your payment of $49.99 has been processed.
</x-bt-alert>

{{-- Title only (compact) --}}
<x-bt-alert warning title="Please review your settings." />
```

## Icons

Semantic colors (`success`, `info`, `warning`, `error`) include a preset icon. You can override it or add one to named colors:

```blade
{{-- Override preset icon --}}
<x-bt-alert success icon="shield-check">Secured.</x-bt-alert>

{{-- Add icon to named color --}}
<x-bt-alert blue icon="envelope">Email sent.</x-bt-alert>

{{-- Hide icon entirely --}}
<x-bt-alert :noIcon="true" success>No icon.</x-bt-alert>
```

## Dismissible

```blade
<x-bt-alert :dismissible="true" info title="Notice">
    Click the X to dismiss this alert.
</x-bt-alert>
```

The dismiss button uses Alpine.js to hide the alert with an opacity transition.

## Slot Content

Use the default slot for rich HTML content:

```blade
<x-bt-alert info title="Update Available">
    <p>Version 2.0 is now available.</p>
    <div class="mt-3">
        <x-bt-button sm solid blue label="Update Now" />
    </div>
</x-bt-alert>
```

## Dark Mode

All colors include dark mode styles automatically. No extra configuration needed.
