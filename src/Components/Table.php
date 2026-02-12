<?php

namespace Beartropy\Ui\Components;

/**
 * Table Component.
 *
 * Renders a data table with support for pagination, sorting, and search.
 * Handles data normalization for Collections, Arrays, and associative arrays.
 *
 * @property bool $primary   Primary color.
 * @property bool $secondary Secondary color.
 * @property bool $success   Success color.
 * @property bool $warning   Warning color.
 * @property bool $danger    Danger color.
 * @property bool $info      Info color.
 */
class Table extends BeartropyComponent
{
    /** @var array<int, array<string, mixed>> */
    public array $items;

    /** @var array<string, string>|list<string> */
    public array $columns;

    public int $perPage;

    public bool $sortable;

    public bool $searchable;

    public bool $paginated;

    public bool $striped;

    public bool $allowHtml;

    public ?string $color;

    /**
     * Create a new Table component instance.
     *
     * @param array<int, array<string, mixed>>|\Illuminate\Support\Collection $items     Data items to display.
     * @param array<string, string>|list<string>                              $columns   Columns configuration (label => key, or list of keys).
     * @param int                                                             $perPage   Number of items per page.
     * @param bool                                                            $sortable  Allow column sorting.
     * @param bool                                                            $searchable Allow text search.
     * @param bool                                                            $paginated Show pagination controls.
     * @param bool                                                            $striped   Alternating row background colors.
     * @param bool                                                            $allowHtml Render cell content as HTML (x-html) instead of text (x-text).
     * @param string|null                                                     $color     Table accent color.
     */
    public function __construct(
        array|\Illuminate\Support\Collection $items = [],
        array $columns = [],
        int $perPage = 10,
        bool $sortable = true,
        bool $searchable = true,
        bool $paginated = true,
        bool $striped = false,
        bool $allowHtml = false,
        ?string $color = null
    ) {
        $this->sortable = filter_var($sortable, FILTER_VALIDATE_BOOLEAN);
        $this->searchable = filter_var($searchable, FILTER_VALIDATE_BOOLEAN);
        $this->paginated = filter_var($paginated, FILTER_VALIDATE_BOOLEAN);
        $this->striped = filter_var($striped, FILTER_VALIDATE_BOOLEAN);
        $this->allowHtml = filter_var($allowHtml, FILTER_VALIDATE_BOOLEAN);
        $this->perPage = $perPage;
        $this->color = $color;
        $this->columns = $columns ?: (count($items) ? array_keys($items[0]) : []);
        $this->items = $this->normalizeData($items, $this->columns);
    }

    /**
     * Normalize dataset into a standard array format.
     *
     * Converts Collections and Models to arrays.
     * Maps indexed arrays to associative arrays based on columns.
     *
     * @param array<int, mixed>|\Illuminate\Support\Collection $items   Raw items.
     * @param array<string, string>|list<string>               $columns Column keys/labels.
     *
     * @return array<int, array<string, mixed>> The normalized data array.
     */
    public function normalizeData(array|\Illuminate\Support\Collection $items, array $columns): array
    {
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->all();
        }

        if (is_array($items) && count($items) && is_object($items[0]) && method_exists($items[0], 'toArray')) {
            $items = array_map(function ($item) {
                return $item->toArray();
            }, $items);
        }

        $columnLabels = array_values($columns);
        if (
            is_array($items)
            && count($items)
            && is_array($items[0])
            && array_keys($items[0]) === range(0, count($items[0]) - 1)
            && count($items[0]) === count($columnLabels)
        ) {
            return array_map(function ($row) use ($columnLabels) {
                return array_combine($columnLabels, array_pad($row, count($columnLabels), null));
            }, $items);
        }

        return $items;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::table');
    }
}
