<?php

namespace Beartropy\Ui\Components;

use Illuminate\Support\Js;

class Confirm extends BeartropyComponent
{
    public string $id;
    public ?string $title;
    public ?string $message;
    public ?string $icon;
    public bool $styled;
    public string $size;
    public bool $closeOnBackdrop;
    public bool $closeOnEscape;
    /** @var array<int, array<string, mixed>> */
    public array $buttons;

    public function __construct(
        string $id,
        ?string $title = null,
        ?string $message = null,
        ?string $icon = null,
        bool $styled = false,
        string $size = 'md',
        bool $closeOnBackdrop = true,
        bool $closeOnEscape = true,
        array $buttons = []
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->styled = $styled;
        $this->size = $size;
        $this->closeOnBackdrop = $closeOnBackdrop;
        $this->closeOnEscape = $closeOnEscape;
        $this->buttons = $this->normalizeButtons($buttons);
    }

    /** @return array<int, array<string, mixed>> */
    protected function normalizeButtons(array $buttons): array
    {
        return array_map(function ($b) {
            $b['label']        = $b['label']        ?? 'OK';
            $b['variant']      = $b['variant']      ?? 'soft';   // solid | soft | outline | ghost
            $b['mode']         = $b['mode']         ?? (isset($b['wire']) ? 'wire' : (isset($b['emit']) ? 'emit' : 'close'));
            $b['params']       = $b['params']       ?? [];
            $b['dismissAfter'] = $b['dismissAfter'] ?? false;
            $b['close']        = $b['close']        ?? false;
            $b['spinner']      = $b['spinner']      ?? false;
            return $b;
        }, $buttons);
    }

    /** Convierte params PHP a lista JS para interpolar en wire:click */
    public static function jsArgs(array $params): string
    {
        // Usa Illuminate\Support\Js para serializar; unimos por coma sin brackets
        $encoded = array_map(fn($v) => Js::from($v), $params);
        return implode(', ', $encoded);
    }

    public function render()
    {
        return view('beartropy-ui::components.confirm', [
            'jsArgs' => fn(array $a) => static::jsArgs($a),
        ]);
    }
}
