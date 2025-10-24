<?php

namespace App\View\Components;

use Beartropy\Ui\Components\BeartropyComponent;
use Illuminate\View\View;

class FileInput extends BeartropyComponent
{
    /** Identificador del input */
    public string $id;

    /** Nombre del campo (name=...) */
    public ?string $name;

    /** Placeholder/etiqueta inicial */
    public string $placeholder;

    /** Muestra botón Limpiar */
    public bool $clearable;

    /** Deshabilitado */
    public bool $disabled;

    /** Permite selección múltiple */
    public bool $multiple;

    /** Tipos aceptados (accept=...) */
    public ?string $accept;

    public ?string $label = null;
    public ?string $customError = null;
    public ?string $hint = null;

    /**
     * @param  string|null  $id
     * @param  string|null  $name
     * @param  bool         $multiple
     * @param  string|null  $accept
     * @param  string       $placeholder
     * @param  bool         $clearable
     * @param  bool         $disabled
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        bool $multiple = false,
        ?string $accept = null,
        string $placeholder = 'Elegir archivo',
        bool $clearable = true,
        bool $disabled = false,
        ?string $customError = null,
        ?string $label = null,
        ?string $hint = null
    ) {
        $this->id = $id ?? ('input-file-' . uniqid());
        $this->name = $name ?? $this->id;
        $this->multiple = $multiple;
        $this->accept = $accept;
        $this->placeholder = $placeholder;
        $this->clearable = $clearable;
        $this->disabled = $disabled;
        $this->customError = $customError;
        $this->label = $label;
        $this->hint = $hint;
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render(): View
    {
        return view('beartropy-ui::file-input');
    }
}
