# Option

A data-only declarative child component for `<x-bt-select>`. Pushes option data to the parent Select component during Blade evaluation. Renders no HTML.

## Basic Usage

```blade
<x-bt-select name="country">
    <x-bt-option value="AR" label="Argentina" />
    <x-bt-option value="US" label="United States" />
    <x-bt-option value="BR" label="Brazil" />
</x-bt-select>
```

## With Icons

```blade
<x-bt-select name="status">
    <x-bt-option value="active" label="Active" icon="check-circle" />
    <x-bt-option value="inactive" label="Inactive" icon="x-circle" />
</x-bt-select>
```

## With Avatars

```blade
<x-bt-select name="user">
    <x-bt-option value="1" label="Ana" avatar="https://example.com/ana.jpg" />
    <x-bt-option value="2" label="Carlos" avatar="https://example.com/carlos.jpg" />
</x-bt-select>
```

## With Descriptions

```blade
<x-bt-select name="plan">
    <x-bt-option value="free" label="Free" description="Up to 3 projects" />
    <x-bt-option value="pro" label="Pro" description="Unlimited projects" />
</x-bt-select>
```

## Label Defaults to Value

If no `label` is provided, the `value` is used as the display label:

```blade
<x-bt-option value="Argentina" />  {{-- label = "Argentina" --}}
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| value | `string` | (required) | Option value |
| label | `?string` | same as value | Display label |
| icon | `?string` | `null` | Icon name, emoji, or raw SVG |
| avatar | `?string` | `null` | Avatar URL or emoji |
| description | `?string` | `null` | Secondary description text |
