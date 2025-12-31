<?php

namespace Beartropy\Ui\Components;

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
    public array $items;


    public string $ulClass;
    public string $titleClass;
    public string $itemClass;
    public string $liClass;
    public string $activeClass;


    /**
     * Create a new Menu component instance.
     *
     * @param array  $items       Menu items.
     * @param string $ulClass     UL class.
     * @param string $titleClass  Title class.
     * @param string $itemClass   Item class.
     * @param string $liClass     LI class.
     * @param string $activeClass Active class.
     */
    public function __construct(
        array $items,
        string $ulClass = 'mt-4 space-y-2 dark:border-slate-800 lg:space-y-4 lg:mt-4 lg:border-slate-200',
        string $titleClass = 'font-medium text-orange-500 font-display dark:text-orange-400',
        string $itemClass = 'transition inline-flex items-center gap-x-2 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400',
        string $liClass = 'relative',
        string $activeClass = 'text-orange-500 dark:text-orange-400 font-semibold'
    ) {
        $this->items      = $items;
        $this->titleClass = $titleClass;
        $this->itemClass  = $itemClass;
        $this->ulClass    = $ulClass;
        $this->liClass    = $liClass;
        $this->activeClass = $activeClass;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('beartropy-ui::menu');
    }
}
