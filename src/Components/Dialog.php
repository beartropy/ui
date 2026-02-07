<?php

namespace Beartropy\Ui\Components;

/**
 * Dialog Component.
 *
 * Renders a dialog modal.
 */
class Dialog extends BeartropyComponent
{

    /**
     * Create a new Dialog component instance.
     *
     * @param string|null $size Dialog size (default: md).
     *
     * ## Blade Props
     *
     * ### View Properties (via Alpine/JS)
     * @property string $type        Dialog type (info, success, warning, error, confirm, danger).
     * @property string $title       Dialog title.
     * @property string $description Dialog description.
     * @property string $icon        Icon name (check-circle, x-circle, etc.).
     * @property object $accept      Accept button config {label, method, params}.
     * @property object $reject      Reject button config {label, method, params}.
     *
     * ### Magic Attributes (Size)
     * @property bool $sm Small.
     * @property bool $md Medium (default).
     * @property bool $lg Large.
     * @property bool $xl Extra Large.
     * @property bool $2xl Double Extra Large.
     */
    public function __construct(
        public ?string $size = 'md'
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::dialog');
    }
}
