# Card

A content container with optional title, footer, and collapsibility.

## Basic Usage

```blade
<x-bt-card>
    <p>Card content here.</p>
</x-bt-card>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string\|null` | `null` | Card title header |
| `footer` | `string\|null` | `null` | Card footer content |
| `color` | `string\|null` | `null` | Color preset: `beartropy`, `modal`, `neutral` |
| `size` | `string\|null` | `null` | Card size/padding |
| `collapsable` | `bool` | `false` | Whether the card content can be toggled |
| `noBorder` | `bool` | `false` | Remove border and shadow |
| `defaultOpen` | `bool` | `true` | Initial visibility state (if collapsable) |

## Colors

Three color presets control card styling:

```blade
<x-bt-card color="beartropy">Default — padded, styled title and footer</x-bt-card>
<x-bt-card color="modal">Modal — semibold title, footer with border-top</x-bt-card>
<x-bt-card color="neutral">Neutral — minimal, no slot/title styling</x-bt-card>
```

## Title

```blade
<x-bt-card title="Account Settings">
    <p>Manage your account preferences.</p>
</x-bt-card>
```

## Footer

Footer can be a prop or a named slot:

```blade
{{-- As prop --}}
<x-bt-card title="Info" footer="Last updated: 5 min ago">
    <p>Content here.</p>
</x-bt-card>

{{-- As slot --}}
<x-bt-card title="Profile">
    <p>Edit your profile.</p>
    <x-slot:footer>
        <x-bt-button ghost label="Cancel" />
        <x-bt-button solid beartropy label="Save" />
    </x-slot:footer>
</x-bt-card>
```

## No Border

Remove the border and shadow for embedding inside other containers:

```blade
<x-bt-card :noBorder="true">
    <p>Borderless card content.</p>
</x-bt-card>
```

## Collapsible

Make the card content collapsible. Requires a `title` for the clickable header:

```blade
{{-- Starts open (default) --}}
<x-bt-card title="Details" :collapsable="true">
    <p>Click the title to collapse.</p>
</x-bt-card>

{{-- Starts collapsed --}}
<x-bt-card title="Advanced" :collapsable="true" :defaultOpen="false">
    <p>Click to expand.</p>
</x-bt-card>
```

The chevron icon rotates to indicate state. Footer is also hidden when collapsed.

## Livewire Loading

Add `wire:target` to show a loading spinner overlay during a Livewire action:

```blade
<x-bt-card wire:target="save" title="Form">
    <p>Content with loading overlay.</p>
</x-bt-card>
```

## Slots

| Slot | Description |
|------|-------------|
| default | Card body content |
| footer | Card footer content (alternative to `footer` prop) |

## Configuration

Default color can be set in `config/beartropyui.php`:

```php
'component_defaults' => [
    'card' => [
        'color' => env('BEARTROPY_UI_CARD_COLOR', 'beartropy'),
    ],
],
```

## Dark Mode

All color presets include dark mode styles automatically.
