<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Menu component.
 *
 * Renders a menu list.
 *
 * @property array  $items       Menu items.
 * @property string $ulClass     CSS class for UL element.
 * @property string $titleClass  CSS class for titles.
 * @property string $itemClass   CSS class for items.
 * @property string $liClass     CSS class for LI elements.
 * @property string $activeClass CSS class for active items.
 */
class Menu extends BeartropyComponent
{
    /**
     * Create a new Menu component instance.
     *
     * @param array  $items       Menu items.
     * @param string $ulClass     UL class.
     * @param string $titleClass  Title class.
     * @param string $itemClass   Item class.
     * @param string $liClass     LI class.
     * @param string $activeClass Active class.
     *
     * ## Blade Props
     *
     * ### View Properties
     * @property int  $level  Recursion level (internal).
     * @property bool $mobile Mobile mode flag.
     */
    public function __construct(
        public array $items,
        public string $ulClass = 'mt-4 space-y-2 dark:border-slate-800 lg:space-y-4 lg:mt-4 lg:border-slate-200',
        public string $titleClass = 'font-medium text-orange-500 font-display dark:text-orange-400',
        public string $itemClass = 'transition inline-flex items-center gap-x-2 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400',
        public string $liClass = 'relative',
        public string $activeClass = 'text-orange-500 dark:text-orange-400 font-semibold',
    ) {}

    public function render(): View
    {
        return view('beartropy-ui::menu');
    }
}
