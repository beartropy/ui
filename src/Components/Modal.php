<?php

namespace Beartropy\Ui\Components;

use Illuminate\Contracts\View\View;

/**
 * Modal Component.
 *
 * Renders a modal dialog.
 *
 * ## Blade Props
 *
 * ### Slots
 * @slot default Modal content.
 * @slot title   Modal title.
 * @slot footer  Modal footer buttons/actions.
 *
 * ### Magic Attributes (Size)
 * @property bool $sm  Small.
 * @property bool $md  Medium (default).
 * @property bool $lg  Large.
 * @property bool $xl  Extra Large.
 * @property bool $2xl Double Extra Large.
 * @property bool $3xl Triple Extra Large.
 * @property bool $4xl Quadruple Extra Large.
 * @property bool $5xl Quintuple Extra Large.
 * @property bool $6xl Sextuple Extra Large.
 * @property bool $7xl Septuple Extra Large.
 * @property bool $full Full width.
 *
 * ### Magic Attributes (Blur)
 * @property bool $blur-none No blur.
 * @property bool $blur-sm   Small blur.
 * @property bool $blur-md   Medium blur.
 * @property bool $blur-lg   Large blur.
 * @property bool $blur-xl   Extra Large blur.
 */
class Modal extends BeartropyComponent
{
    public function render(): View
    {
        return view('beartropy-ui::modal');
    }
}
