<?php

namespace Beartropy\Ui\Components;

/**
 * Skeleton component.
 *
 * Displays a loading skeleton placeholder.
 *
 * @property string      $init          Initialization string/logic.
 * @property int         $lines         Number of lines.
 * @property string      $rounded       Border radius preset.
 * @property string      $tag           HTML tag to use.
 * @property string|null $skeletonClass Custom class for the skeleton.
 * @property string|null $shape         Shape type (card, rectangle, none).
 * @property int|null    $rows          Number of rows.
 * @property int|null    $cols          Number of columns.
 */
class Skeleton extends BeartropyComponent
{
    /**
     * Create a new Skeleton component instance.
     *
     * @param string      $init          Init logic.
     * @param int         $lines         Line count.
     * @param string      $rounded       Rounded class or preset.
     * @param string      $tag           Tag name.
     * @param string|null $skeletonClass CSS class.
     * @param string|null $shape         Shape type.
     * @param int|null    $rows          Rows.
     * @param int|null    $cols          Columns.
     */
    public function __construct(
        public string $init,
        public int $lines = 1,
        public string $rounded = 'lg',
        public string $tag = 'div',
        public ?string $skeletonClass = null,
        public ?string $shape = 'card', // card | rectangle | none
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
