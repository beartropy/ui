<?php

namespace Beartropy\Ui\Components;

class Menu extends BeartropyComponent
{
    public array $items;


    public string $ulClass;
    public string $titleClass;
    public string $itemClass;
    public string $liClass;
    public string $activeClass;


    public function __construct
    (
        array $items,
        string $ulClass = 'mt-4 space-y-2 dark:border-slate-800 lg:space-y-4 lg:mt-4 lg:border-slate-200',
        string $titleClass = 'font-medium text-orange-500 font-display dark:text-orange-400',
        string $itemClass = 'transition inline-flex items-center gap-x-2 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400',
        string $liClass = 'relative',
        string $activeClass = 'text-orange-500 dark:text-orange-400 font-semibold'
    )
    {
        $this->items      = $items;
        $this->titleClass = $titleClass;
        $this->itemClass  = $itemClass;
        $this->ulClass    = $ulClass;
        $this->liClass    = $liClass;
        $this->activeClass = $activeClass;
    }


    public function render()
    {
        return view('beartropy-ui::menu');
    }
}
