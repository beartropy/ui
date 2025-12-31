<?php

namespace Beartropy\Ui\Components;

/**
 * Icon Component.
 *
 * Renders icons from various sets (Heroicons, Lucide, FontAwesome, etc.) or strings.
 */
class Icon extends BeartropyComponent
{
    /**
     * Create a new Icon component instance.
     *
     * @param string      $name      Icon name (e.g. 'home', 'fa-home', emoji).
     * @param string|null $size      Icon size class.
     * @param string      $class     Additional CSS classes.
     * @param bool        $solid     Force solid variant.
     * @param bool        $outline   Force outline variant.
     * @param string|null $set       Icon set override.
     * @param string|null $variant   Variant override.
     * @param string|null $sizeClass Size class? (seemingly redundant with size, but kept for legacy).
     *
     * ## Blade Props
     *
     * ### Magic Attributes (Size)
     * @property bool $xs Extra Small.
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
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
        public string $name,
        public ?string $size = null,
        public string $class = '',
        public bool $solid = false,
        public bool $outline = false,
        public ?string $set = null,
        public ?string $variant = null,
        public ?string $sizeClass = null,
    ) {}

    /**
     * Resolve icon classes and component names.
     *
     * Logic to determine which icon set, variant, and component to render.
     *
     * @param string|null $iconSize Size classes.
     *
     * @return object{allClasses: string, iconComponent: string|null, fa: string|null, set: string|null, variant: string|null, name: string, class: string, sizeClass: string|null}
     */
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
