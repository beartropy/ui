# x-bt-table — AI Reference

## Component Tag
```blade
<x-bt-table />
```

## Architecture
- `Table` → extends `BeartropyComponent`
- Renders: `table.blade.php`
- Presets: `resources/views/presets/table.php` (color classes for thead, rows, pagination)
- Sizes: global `resources/views/presets/sizes.php`
- **Alpine JS**: `resources/js/modules/table.js` exports `beartropyTable(cfg)`, registered as `Alpine.data('beartropyTable')` and `window.$beartropy.beartropyTable` in `resources/js/index.js`
- Blade passes a config object to `$beartropy.beartropyTable({...})` containing all server-side values; JS handles all runtime logic including sorting, filtering, pagination

## Props (constructor)

| Prop | PHP Type | Default | Blade Attribute |
|------|----------|---------|-----------------|
| items | `array\|Collection` | `[]` | `:items="$data"` |
| columns | `array` | auto-detected | `:columns="$cols"` |
| perPage | `int` | `10` | `:per-page="25"` |
| sortable | `bool` | `true` | `:sortable="false"` |
| searchable | `bool` | `true` | `:searchable="false"` |
| paginated | `bool` | `true` | `:paginated="false"` |
| striped | `bool` | `false` | `:striped="true"` |
| allowHtml | `bool` | `false` | `:allow-html="true"` |
| color | `?string` | `null` | `color="beartropy"` |

## Magic Attributes

### Colors (mutually exclusive)
`primary`, `beartropy`, `red`, `blue`, `green`, `yellow`, `purple`, `pink`, `gray`, `orange`, `amber`, `lime`, `emerald`, `teal`, `cyan`, `sky`, `indigo`, `violet`, `rose`, `fuchsia`, `slate`, `stone`, `zinc`, `neutral`

## Data Normalization

The constructor calls `normalizeData()` which handles:
- **Eloquent Collections**: converted via `->all()`
- **Objects with `toArray()`**: each item mapped to array
- **Indexed arrays** (sequential 0,1,2... keys): mapped to associative arrays using column labels
- **Associative arrays**: passed through as-is

## Columns

When no `columns` prop is provided, columns are auto-detected from the first item's keys.

Columns can be:
- **List of strings**: `['id', 'name', 'email']` — used as both key and label
- **Associative array**: `['username' => 'User', 'role' => 'Role']` — key = data key, value = display label

## Alpine JS Module (`resources/js/modules/table.js`)

The `beartropyTable(cfg)` function receives a config object from Blade and returns an Alpine data object.

### Config object passed from Blade

```js
{
    data,       // array — normalized items from PHP
    columns,    // array|object — column keys or key→label map
    perPage,    // int
    sortable,   // bool
    searchable, // bool
    paginated,  // bool
}
```

### Computed getters
- `filtered` — applies search filter across all columns
- `sorted` — sorts filtered results by `sortBy` column (numeric-aware)
- `paginatedRows` — slices sorted results for current page
- `totalPages` — total page count
- `start` — current page start index

### Key methods
- `toggleSort(col)` — toggles sort column/direction, resets page to 1
- `gotoPage(p)` — navigates to page p
- `nextPage()` / `prevPage()` — page navigation
- `pagesToShow()` — returns array of page numbers with `'...'` ellipsis for large page counts
- `colLabel(col)` — returns display label for a column key
- `init()` — watches `search` to reset page to 1

## XSS Hardening

- By default, cells use `x-text` (safe text rendering)
- When `:allow-html="true"`, cells use `x-html` (renders HTML — use only with trusted data)

## Striped Rows

When `:striped="true"`, alternating rows get `even:bg-gray-50 dark:even:bg-gray-800/50` classes.

## Accessibility

- `<th>`: `role="columnheader"`, `:aria-sort="ascending|descending|none"` when sortable
- Sort direction arrows: `aria-hidden="true"`
- Empty state `<td>`: `role="status"`
- Pagination nav: `aria-label`
- Previous/Next buttons: `aria-label`, `type="button"`
- Page number buttons: `:aria-current="page"` on active, `type="button"`
- Pagination SVG icons: `aria-hidden="true"`
- Ellipsis buttons: `aria-hidden="true"`

## Localization

All user-facing strings use `__('beartropy-ui::ui.*')`:
- Search placeholder: `search`
- Empty state: `no_results`
- Pagination: `table_showing`, `table_to`, `table_of`, `table_results`
- Navigation: `table_previous`, `table_next`

## Preset Structure (table.php)
```
colors → {color} → {
    searchbox, thead, th, row, td, table,
    pagination_container, pagination_button, pagination_active,
    pagination_icon, pagination_disabled, pagination_ellipsis,
    pagination_info
}
```

## Common Patterns

```blade
{{-- Basic --}}
<x-bt-table :items="$users" />

{{-- Custom columns with labels --}}
<x-bt-table :items="$users" :columns="['name' => 'Name', 'email' => 'Email']" />

{{-- Non-sortable, non-searchable --}}
<x-bt-table :items="$data" :sortable="false" :searchable="false" />

{{-- Striped rows --}}
<x-bt-table :items="$data" :striped="true" />

{{-- HTML content (trusted data only) --}}
<x-bt-table :items="$data" :allow-html="true" />

{{-- Custom page size --}}
<x-bt-table :items="$data" :per-page="25" />

{{-- No pagination --}}
<x-bt-table :items="$data" :paginated="false" />

{{-- Colored --}}
<x-bt-table :items="$data" color="beartropy" />

{{-- Collection data --}}
<x-bt-table :items="$users->toArray()" />
```

## Key Notes
- Alpine JS logic lives in `resources/js/modules/table.js`, not inline in the Blade template
- `filter_var($prop, FILTER_VALIDATE_BOOLEAN)` is used for bool props — string `"true"`/`"false"` from Blade attributes work correctly
- Columns are auto-detected from the first data item's keys when not explicitly provided
- Indexed arrays (sequential keys) are automatically mapped to associative arrays using column labels
- Search filters across all columns, case-insensitive
- Sorting is numeric-aware (numbers sort numerically, strings sort lexicographically)
- `toggleSort()` resets page to 1 to prevent viewing an empty page after sort changes
- The `$watch('search')` in `init()` also resets page to 1
- Cell content uses `x-text` by default for XSS safety; opt into `x-html` with `:allow-html="true"`
