<?php

namespace Beartropy\Ui\Components;

/**
 * Skeleton component.
 *
 * Displays a loading skeleton placeholder.
 *
 * @property string|null $init    Livewire wire:init method name.
 * @property int         $lines   Number of lines.
 * @property string      $rounded Border radius preset (none, sm, md, lg, xl, full).
 * @property string      $tag     HTML tag to use.
 * @property string|null $shape   Shape type (card, rectangle, image, table, none).
 * @property int|null    $rows    Number of rows (table shape).
 * @property int|null    $cols    Number of columns (table shape).
 */
class Skeleton extends BeartropyComponent
{
    /**
     * Create a new Skeleton component instance.
     *
     * @param string|null $init    Livewire wire:init method name.
     * @param int         $lines   Line count.
     * @param string      $rounded Rounded preset.
     * @param string      $tag     Tag name.
     * @param string|null $shape   Shape type (card, rectangle, image, table, none).
     * @param int|null    $rows    Rows (table shape).
     * @param int|null    $cols    Columns (table shape).
     */
    public function __construct(
        public ?string $init = null,
        public int $lines = 1,
        public string $rounded = 'lg',
        public string $tag = 'div',
        public ?string $shape = 'card',
        public ?int $rows = null,
        public ?int $cols = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::skeleton');
    }
}
