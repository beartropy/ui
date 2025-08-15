<?php

namespace Beartropy\Ui\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasConfirms
 *
 * Uso típico:
 * $this->confirm()
 *   ->title('¿Eliminar?')->message('No se puede deshacer.')
 *   ->danger()->cancel()->confirmLabel('Eliminar', 'danger')
 *   ->wire('deleteUser', [$id])
 *   ->open();
 *
 * Requiere en el layout:
 * <x-confirm id="beartropy-confirm" styled />
 * y un host JS que escuche 'bt-confirm' y ejecute los botones.
 */
trait HasConfirms
{
    /** @var object|null */
    protected $_beartropy_confirm_proxy;

    /**
     * Entry point de la API fluida.
     */
    public function confirm(): object
    {
        if (!isset($this->_beartropy_confirm_proxy)) {
            $this->_beartropy_confirm_proxy = new class($this) {
                /** @var mixed Componente Livewire */
                protected $component;

                // ---- Estado del confirm (defaults) ----
                protected string $target = 'beartropy-confirm';
                protected ?string $title = null;
                protected ?string $message = null;
                protected ?string $icon = null;
                protected bool $styled = true;
                protected string $size = 'md';
                protected bool $closeOnBackdrop = true;
                protected bool $closeOnEscape = true;
                protected string $effect = 'zoom';
                protected int $duration = 200;
                protected string $easing = 'ease-out';
                protected float $overlayOpacity = 0.6;
                protected bool $overlayBlur = false;
                protected string $placement = 'top';
                protected ?string $panelClass = 'mt-32';

                /** Estilo por defecto del botón de confirmación */
                protected string $defaultVariant = 'primary';
                protected string $defaultColor   = 'blue';

                /** @var array<int, array<string, mixed>> */
                protected array $buttons = [];

                public function __construct($component)
                {
                    $this->component = $component;
                }

                // -------- Configuración general --------
                public function target(string $id): self { $this->target = $id; return $this; }
                public function title(?string $title): self { $this->title = $title; return $this; }
                public function message(?string $message): self { $this->message = $message; return $this; }
                public function icon(?string $icon): self { $this->icon = $icon; return $this; }
                public function styled(bool $styled = true): self { $this->styled = $styled; return $this; }
                public function size(string $size = 'md'): self { $this->size = $size; return $this; }
                public function closeOnBackdrop(bool $v = true): self { $this->closeOnBackdrop = $v; return $this; }
                public function closeOnEscape(bool $v = true): self { $this->closeOnEscape = $v; return $this; }
                public function effect(string $e): self { $this->effect = $e; return $this; } // 'zoom','fade','slide-up','slide-down','slide-left','slide-right'
                public function transition(int $ms): self { $this->duration = $ms; return $this; }
                public function easing(string $e): self { $this->easing = $e; return $this; } // ej. 'cubic-bezier(0.2,0.8,0.2,1)'
                public function overlay(float $opacity = 0.6, bool $blur = false): self { $this->overlayOpacity = $opacity; $this->overlayBlur = $blur; return $this; }
                public function placement(string $pos = 'center'): self { $this->placement = $pos; return $this; }
                public function panelClass(?string $cls): self { $this->panelClass = $cls; return $this; }
                public function centered(bool $centered = false): self { ($centered) ? $this->panelClass('mt-32') : $this->panelClass(''); ($centered) ? $this->placement('center') : $this->placement('top'); return $this; }

                // -------- Variantes rápidas --------
                public function variant(string $variant): self { $this->defaultVariant = $variant; return $this; }
                public function danger(): self
                {
                    $this->defaultVariant = 'danger';
                    $this->defaultColor   = 'red';
                    return $this;
                }
                public function primary(string $color = 'blue'): self
                {
                    $this->defaultVariant = 'primary';
                    $this->defaultColor   = $color;
                    return $this;
                }
                public function success(): self   { return $this->primary('green'); }
                public function warning(): self   { return $this->primary('amber'); }
                public function info(): self      { return $this->primary('blue'); }
                public function soft(): self { return $this->variant('soft'); }

                // -------- Botones --------
                /**
                 * Agrega un botón arbitrario.
                 * Keys soportadas:
                 *  - label, variant, mode(wire|emit|close), wire, params[], emit, payload[], dismissAfter(bool), close(bool), spinner(bool)
                 */

                protected function tokenFor(?string $variant, ?string $color): string
                {
                    $v = $variant ?? 'soft';
                    $c = $color ?? 'gray';

                    return match ($v) {
                        'primary' => match ($c) {
                            'blue'   => 'btc-primary-blue',
                            'gray'   => 'btc-primary-gray',
                            'green'  => 'btc-primary-green',
                            'amber'  => 'btc-primary-amber',
                            default  => 'btc-primary-gray',
                        },
                        'danger'   => 'btc-danger-red',
                        'ghost'    => 'btc-ghost',
                        'outline'  => 'btc-outline',
                        default    => 'btc-soft',
                    };
                }

                public function button(array $button): self
                {
                    $btn = array_merge([
                        'label'        => 'OK',
                        'variant'      => 'soft',
                        'mode'         => 'close',
                        'wire'         => null,
                        'params'       => [],
                        'emit'         => null,
                        'payload'      => [],
                        'dismissAfter' => false,
                        'close'        => false,
                        'spinner'      => false,
                        'token'        => null,
                    ], $button);

                    if (!$btn['token']) {
                        $btn['token'] = $this->tokenFor($btn['variant'] ?? null, $btn['color'] ?? null);
                    }

                    $this->buttons[] = $btn;
                    return $this;
                }

                public function cancel(?string $label = null, string $variant = 'ghost', string $color = 'gray'): self
                {
                    return $this->button([
                        'label'   => $label ?? ($this->labels['cancel'] ?? 'Cancelar'),
                        'variant' => $variant,
                        'color'   => $color,
                        'mode'    => 'close',
                        'close'   => true,
                        'role'    => 'cancel',
                    ]);
                }

                public function confirmLabel(?string $label = null, ?string $variant = null, ?string $color = null): self
                {
                    return $this->button([
                        'label'   => $label ?? ($this->labels['confirm'] ?? 'Confirmar'),
                        'variant' => $variant ?? $this->defaultVariant,
                        'color'   => $color   ?? $this->defaultColor,
                        'mode'    => 'close',
                        'role'    => 'confirm',
                    ]);
                }


                public function yesNo(
                    string $yes = 'Sí',
                    string $no = 'No',
                    string $yesVariant = 'primary',
                    string $noVariant = 'ghost'
                ): self {
                    $this->cancel($no, $noVariant);
                    $this->confirmLabel($yes, $yesVariant);
                    return $this;
                }

                public function proceedCancel(
                    string $proceed = 'Continuar',
                    string $cancel = 'Cancelar',
                    string $proceedVariant = 'primary',
                    string $cancelVariant = 'ghost'
                ): self {
                    $this->cancel($cancel, $cancelVariant);
                    $this->confirmLabel($proceed, $proceedVariant);
                    return $this;
                }

                // -------- Wiring de la acción principal --------
                /**
                 * Setea el ÚLTIMO botón agregado como acción wire.
                 */
                public function wire(string $method, array $params = [], bool $dismissAfter = true, bool $spinner = true): self
                {
                    if (empty($this->buttons)) {
                        $this->confirmLabel();
                    }
                    $idx = \count($this->buttons) - 1;
                    $this->buttons[$idx]['mode'] = 'wire';
                    $this->buttons[$idx]['wire'] = $method;
                    $this->buttons[$idx]['params'] = $params;
                    $this->buttons[$idx]['dismissAfter'] = $dismissAfter;
                    $this->buttons[$idx]['spinner'] = $spinner;
                    return $this;
                }

                /**
                 * Setea el ÚLTIMO botón agregado como evento emit.
                 */
                public function emit(string $event, array $payload = [], bool $dismissAfter = true): self
                {
                    if (empty($this->buttons)) {
                        $this->confirmLabel();
                    }
                    $idx = \count($this->buttons) - 1;
                    $this->buttons[$idx]['mode'] = 'emit';
                    $this->buttons[$idx]['emit'] = $event;
                    $this->buttons[$idx]['payload'] = $payload;
                    $this->buttons[$idx]['dismissAfter'] = $dismissAfter;
                    return $this;
                }

                // -------- Presets útiles --------
                public function delete(int|string|null $id = null, string $title = '¿Eliminar?', string $message = 'Esta acción no se puede deshacer.'): self
                {
                    $this->danger()
                         ->title($title)
                         ->message($message)
                         ->cancel()
                         ->confirmLabel('Eliminar', 'danger');

                    if ($id !== null) {
                        $this->wire('delete', [$id]);
                    }
                    return $this;
                }

                // -------- Disparo --------
                public function open(): void
                {
                    // Normaliza botones
                    $buttons = array_map(function ($b) {
                        $b['label']        = $b['label']        ?? 'OK';
                        $b['variant']      = $b['variant']      ?? 'soft';
                        $b['mode']         = $b['mode']         ?? ($b['wire'] ? 'wire' : ($b['emit'] ? 'emit' : 'close'));
                        $b['params']       = $b['params']       ?? [];
                        $b['dismissAfter'] = $b['dismissAfter'] ?? false;
                        $b['close']        = $b['close']        ?? false;
                        $b['spinner']      = $b['spinner']      ?? false;
                        return $b;
                    }, $this->buttons);

                    // Obtiene el id del componente Livewire actual (v3 usa getId)
                    $componentId = null;
                    if (method_exists($this->component, 'getId')) {
                        $componentId = $this->component->getId();
                    } elseif (property_exists($this->component, 'id')) {
                        $componentId = $this->component->id;
                    }

                    $payload = [
                        'id'              => (string) Str::uuid(),
                        'target'          => $this->target,
                        'title'           => $this->title,
                        'message'         => $this->message,
                        'icon'            => $this->icon,
                        'styled'          => $this->styled,
                        'size'            => $this->size,
                        'closeOnBackdrop' => $this->closeOnBackdrop,
                        'closeOnEscape'   => $this->closeOnEscape,
                        'buttons'         => $buttons,
                        'componentId'     => $componentId,
                        'effect'         => $this->effect,
                        'duration'       => $this->duration,
                        'easing'         => $this->easing,
                        'overlayOpacity' => $this->overlayOpacity,
                        'overlayBlur'    => $this->overlayBlur,
                        'placement' => $this->placement,
                        'panelClass' => $this->panelClass,
                    ];

                    // Dispatch a la ventana (escuchado por el host <x-confirm>)
                    $this->component->dispatch('bt-confirm', $payload);

                    // Limpia estado para siguientes usos
                    $this->resetState();
                }

                protected function resetState(): void
                {
                    $this->target = 'beartropy-confirm';
                    $this->title = null;
                    $this->message = null;
                    $this->icon = null;
                    $this->styled = true;
                    $this->size = 'md';
                    $this->closeOnBackdrop = true;
                    $this->closeOnEscape = true;
                    $this->defaultVariant = 'danger';
                    $this->buttons = [];
                }
            };
        }

        return $this->_beartropy_confirm_proxy;
    }
}
