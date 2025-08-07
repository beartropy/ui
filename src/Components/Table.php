<?php

namespace Beartropy\Ui\Components;

class Table extends BeartropyComponent
{

    public $items;
    public $columns;
    public $perPage;
    public $sortable;
    public $searchable;
    public $paginated;
    public $color;

    public function __construct(
        $items = [],
        $columns = [],
        $perPage = 10,
        $sortable = true,
        $searchable = true,
        $paginated = true,
        $color = null
    ){
        $this->columns = $columns ?: (count($items) ? array_keys($items[0]) : []);
        $this->items = $this->normalizeData($items, $this->columns);
        $this->perPage = $perPage;
        $this->sortable = $sortable;
        $this->searchable = $searchable;
        $this->paginated = $paginated;
        $this->color = $color;

    }

    public function normalizeData($items, $columns)
    {
        // 1. Si es Collection: conviértelo a array
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->all();
        }

        // 2. Si es array de modelos Eloquent u objetos con toArray()
        if (is_array($items) && count($items) && is_object($items[0]) && method_exists($items[0], 'toArray')) {
            $items = array_map(function($item) {
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
            return array_map(function($row) use ($columnLabels) {
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
