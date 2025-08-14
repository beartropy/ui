<?php

namespace Beartropy\Ui\Components;

class Lookup extends Input
{
    public function __construct(
        public $options = [],
        public $optionLabel = "name",
        public $optionValue = "id",
        public $label = null,
    ) {
        $this->normalizeOptions($options);
        parent::__construct(label: $label ?? null);
    }

    public function normalizeOptions($options)
    {
        $this->options = collect($options)
            ->map(function ($item) {
                // 1) Lista simple: ["asd", "dsa", 123, ...]
                if (is_scalar($item)) {
                    $val = (string) $item;
                    return ['id' => $val, 'name' => $val];
                }

                // 2) Array/objeto con claves dinámicas (usa $this->optionValue / $this->optionLabel)
                $id   = data_get($item, $this->optionValue);
                $name = data_get($item, $this->optionLabel);

                // 3) Forma "clave => valor" de un solo par: ["ar" => "Argentina"]
                if (is_null($id) && is_null($name) && is_array($item) && count($item) === 1) {
                    $k = array_key_first($item);
                    return ['id' => (string) $k, 'name' => (string) $item[$k]];
                }

                // Si hay id pero falta name, usa id como name
                if (!is_null($id)) {
                    return ['id' => (string) $id, 'name' => (string) ($name ?? $id)];
                }

                // Caso no reconocible: descartar
                return null;
            })
            ->filter()      // quita nulls
            ->values()      // reindexa 0..N
            ->toArray();
    }

    public function render()
    {
        return view('beartropy-ui::lookup');
    }
}
