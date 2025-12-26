<?php

namespace Beartropy\Ui\Components;

class Icon extends BeartropyComponent
{
    public function __construct(
        public string $name,
        public ?string $size = null,
        public string $class = '',
        public bool $solid = false,
        public bool $outline = false,
        public ?string $set = null,
        public ?string $variant = null,
        public ?string $sizeClass = null,
    ) {}

    public function getClasses($iconSize)
    {
        // Icon set: primero el override, si no, el default
        $this->set = $this->set ?? config('beartropyui.icons.set', 'heroicons');
        $this->variant = $this->variant;
        // Variante: primero override, si no, el default

        if (is_null($this->variant) && $this->solid) {
            $this->variant = 'solid';
        } elseif (is_null($this->variant) && $this->outline) {
            $this->variant = 'outline';
        } else {
            $this->variant = $this->variant ?? config('beartropyui.icons.variant', 'outline');
        }

        if ($this->set === 'heroicons') {
            if (str_contains($this->name, 'heroicon-o-')) {
                $this->variant = 'outline';
                $this->name = str_replace('heroicon-o-', '', $this->name);
            } elseif (str_contains($this->name, 'heroicon-s-')) {
                $this->variant = 'solid';
                $this->name = str_replace('heroicon-s-', '', $this->name);
            }
        }

        $allClasses = trim(($iconSize ?? '') . ' ' . ($this->class ?? ''));

        if ($this->set === 'heroicons') {
            $iconComponent = $this->variant === 'solid'
                ? 'heroicon-s-' . $this->name
                : 'heroicon-o-' . $this->name;
        } elseif ($this->set === 'lucide') {
            $iconComponent = 'lucide-' . $this->name;
        } elseif ($this->set === 'fontawesome') {
            $fa = $this->name . ' ' . $allClasses;
        } elseif ($this->set === 'beartropy') {
            $iconComponent = 'beartropy-ui-svg::beartropy-' . $this->name;
        } else {
            $iconComponent = null;
        }

        return (object) [
            'allClasses' => $allClasses,
            'iconComponent' => $iconComponent ?? null,
            'fa' => $fa ?? null,
            'set' => $this->set,
            'variant' => $this->variant,
            'name' => $this->name,
            'class' => $this->class,
            'sizeClass' => $this->sizeClass,
        ];
    }

    public function render()
    {

        return view('beartropy-ui::icon');
    }
}
