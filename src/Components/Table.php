<?php

namespace Beartropy\Ui\Components;

/**
 * Table Component.
 *
 * Renders a data table with support for pagination, sorting, and search.
 * Handles data normalization for Collections, Arrays, and associative arrays.
 */
class Table extends BeartropyComponent
{
    public $items;
    public $columns;
    public $perPage;
    public $sortable;
    public $searchable;
    public $paginated;
    public $color;

    /**
     * Create a new Table component instance.
     *
     * @param array|\Illuminate\Support\Collection $items      Data items to display.
     * @param array                                $columns    Columns configuration (label => key, or list of keys).
     * @param int                                  $perPage    Number of items per page.
     * @param bool                                 $sortable   Allow sorting (not implemented recursively currently).
     * @param bool                                 $searchable Allow searching.
     * @param bool                                 $paginated  Show pagination.
     * @param string|null                          $color      Table accent color.
     *
     * ## Blade Props
     *
     * ### Slots
     * @slot header Custom header row content.
     * @slot row    Custom row content loop.
     * @slot footer Custom footer content.
     *
     * ### Magic Attributes (Color)
     * @property bool $primary   Primary color.
     * @property bool $secondary Secondary color.
     * @property bool $success   Success color.
     * @property bool $warning   Warning color.
     * @property bool $danger    Danger color.
     * @property bool $info      Info color.
     */
    public function __construct(
        $items = [],
        $columns = [],
        $perPage = 10,
        $sortable = true,
        $searchable = true,
        $paginated = true,
        $color = null
    ) {
        $this->columns = $columns ?: (count($items) ? array_keys($items[0]) : []);
        $this->items = $this->normalizeData($items, $this->columns);
        $this->perPage = $perPage;
        $this->sortable = $sortable;
        $this->searchable = $searchable;
        $this->paginated = $paginated;
        $this->color = $color;
    }

    /**
     * Normalize dataset into a standard array format.
     *
     * Converts Collections and Models to arrays.
     * Maps indexed arrays to associative arrays based on columns.
     *
     * @param mixed $items   Raw items.
     * @param array $columns Column keys/labels.
     *
     * @return array The normalized data array.
     */
    public function normalizeData($items, $columns)
    {
        // 1. Si es Collection: conviértelo a array
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->all();
        }

        // 2. Si es array de modelos Eloquent u objetos con toArray()
        if (is_array($items) && count($items) && is_object($items[0]) && method_exists($items[0], 'toArray')) {
            $items = array_map(function ($item) {
                return $item->toArray();
            }, $items);
        }

        // 3. Si es array de arrays planos (detecta keys 0,1,2...) y columns está definido
        $columnLabels = array_values($columns);
        if (
            is_array($items)
            && count($items)
            && is_array($items[0])
            && array_keys($items[0]) === range(0, count($items[0]) - 1)
            && count($items[0]) === count($columnLabels)
        ) {
            // Mapear por orden
            return array_map(function ($row) use ($columnLabels) {
                return array_combine($columnLabels, array_pad($row, count($columnLabels), null));
            }, $items);
        }

        // 4. Si ya es asociativo, no tocar
        return $items;
    }


    public function render()
    {
        return view('beartropy-ui::table');
    }
}
