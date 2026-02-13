# Table

A data table component with client-side sorting, searching, and pagination powered by Alpine.js.

## Basic Usage

```blade
<x-bt-table :items="$users" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `items` | `array\|Collection` | `[]` | Data items to display (arrays, Collections, or objects with `toArray()`) |
| `columns` | `array` | auto-detected | Column configuration — list of keys or `['key' => 'Label']` map |
| `per-page` | `int` | `10` | Number of rows per page |
| `sortable` | `bool` | `true` | Enable column header sorting |
| `searchable` | `bool` | `true` | Show a search input to filter rows |
| `paginated` | `bool` | `true` | Show pagination controls |
| `striped` | `bool` | `false` | Alternating row background colors |
| `allow-html` | `bool` | `false` | Render cell content as HTML instead of text (use only with trusted data) |
| `color` | `string\|null` | `null` | Table color preset |

## Columns

Columns are auto-detected from the first data item's keys when not specified.

```blade
{{-- Auto-detected columns --}}
<x-bt-table :items="$users" />

{{-- Explicit columns (key list) --}}
<x-bt-table :items="$users" :columns="['name', 'email', 'role']" />

{{-- Custom column labels --}}
<x-bt-table :items="$users" :columns="['name' => 'Full Name', 'email' => 'Email Address']" />
```

## Sorting

Sorting is enabled by default. Click any column header to sort ascending; click again for descending.

```blade
{{-- Sortable (default) --}}
<x-bt-table :items="$data" />

{{-- Disable sorting --}}
<x-bt-table :items="$data" :sortable="false" />
```

Sorting is numeric-aware: numbers sort numerically, strings sort lexicographically.

## Search

A search input is shown by default. It filters across all visible columns (case-insensitive).

```blade
{{-- Searchable (default) --}}
<x-bt-table :items="$data" />

{{-- Disable search --}}
<x-bt-table :items="$data" :searchable="false" />
```

## Pagination

Pagination is enabled by default with 10 items per page. Smart page numbers with ellipsis are shown for large datasets.

```blade
{{-- Custom page size --}}
<x-bt-table :items="$data" :per-page="25" />

{{-- Disable pagination --}}
<x-bt-table :items="$shortList" :paginated="false" />
```

## Striped Rows

```blade
<x-bt-table :items="$data" :striped="true" />
```

Adds alternating row backgrounds for easier readability.

## HTML Content

By default, cell content is rendered as text (`x-text`) for XSS safety. Enable HTML rendering only with trusted data:

```blade
<x-bt-table :items="$data" :allow-html="true" />
```

## Data Normalization

The component accepts multiple data formats:

```blade
{{-- Associative arrays --}}
<x-bt-table :items="[['name' => 'Alice'], ['name' => 'Bob']]" />

{{-- Laravel Collections --}}
<x-bt-table :items="$collection" />

{{-- Indexed arrays with column mapping --}}
<x-bt-table
    :items="[['Alice', 'alice@test.com'], ['Bob', 'bob@test.com']]"
    :columns="['Name', 'Email']"
/>
```

Collections and objects with `toArray()` are automatically converted.

## Color

```blade
<x-bt-table :items="$data" color="beartropy" />
```

## Minimal Table

Disable all interactive features for a simple data display:

```blade
<x-bt-table
    :items="$data"
    :sortable="false"
    :searchable="false"
    :paginated="false"
/>
```

## Configuration

Default color can be set in `config/beartropyui.php`:

```php
'component_defaults' => [
    'table' => [
        'color' => env('BEARTROPY_UI_TABLE_COLOR', 'beartropy'),
    ],
],
```

## Dark Mode

All table styles include dark mode support automatically. No extra configuration needed.
