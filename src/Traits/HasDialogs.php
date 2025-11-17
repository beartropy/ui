<?php

namespace Beartropy\Ui\Traits;

trait HasDialogs
{
    /**
     * Azúcar sintáctico para $this->dialog()->success(...)
     */
    public function dialog(): static
    {
        return $this;
    }

    protected function dispatchDialog(array $payload): void
    {
        $payload['componentId'] = $payload['componentId']
            ?? (method_exists($this, 'getId') ? $this->getId() : null);

        $this->dispatch('bt-dialog', $payload);
    }

    public function success(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog(array_merge([
            'type'        => 'success',
            'title'       => $title,
            'description' => $description,
            'icon'        => 'check-circle',
            'size'        => $options['size'] ?? null,
            'accept'      => [
                'label'  => $options['accept_label'] ?? 'OK',
                'method' => $options['accept_method'] ?? null,
                'params' => $options['accept_params'] ?? [],
            ],
            'reject'      => null,
            // por defecto no se cierra por fuera / escape
            'allowOutsideClick' => $options['allowOutsideClick'] ?? false,
            'allowEscape'       => $options['allowEscape'] ?? false,
        ], $options));
    }

    public function info(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog(array_merge([
            'type'        => 'info',
            'title'       => $title,
            'description' => $description,
            'icon'        => 'information-circle',
            'size'        => $options['size'] ?? null,
            'accept'      => [
                'label'  => $options['accept_label'] ?? 'OK',
                'method' => $options['accept_method'] ?? null,
                'params' => $options['accept_params'] ?? [],
            ],
            'reject'      => null,
            'allowOutsideClick' => $options['allowOutsideClick'] ?? false,
            'allowEscape'       => $options['allowEscape'] ?? false,
        ], $options));
    }

    public function warning(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog(array_merge([
            'type'        => 'warning',
            'title'       => $title,
            'description' => $description,
            'icon'        => 'exclamation-triangle',
            'size'        => $options['size'] ?? null,
            'accept'      => [
                'label'  => $options['accept_label'] ?? 'OK',
                'method' => $options['accept_method'] ?? null,
                'params' => $options['accept_params'] ?? [],
            ],
            'reject'      => null,
            'allowOutsideClick' => $options['allowOutsideClick'] ?? false,
            'allowEscape'       => $options['allowEscape'] ?? false,
        ], $options));
    }

    public function error(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog(array_merge([
            'type'        => 'error',
            'title'       => $title,
            'description' => $description,
            'icon'        => 'x-circle',
            'size'        => $options['size'] ?? null,
            'accept'      => [
                'label'  => $options['accept_label'] ?? 'OK',
                'method' => $options['accept_method'] ?? null,
                'params' => $options['accept_params'] ?? [],
            ],
            'reject'      => null,
            'allowOutsideClick' => $options['allowOutsideClick'] ?? false,
            'allowEscape'       => $options['allowEscape'] ?? false,
        ], $options));
    }

    /**
     * Confirm genérico
     *
     * $this->dialog()->confirm([
     *     'title' => 'Are you Sure?',
     *     'description' => 'Save the information?',
     *     'icon' => 'question',
     *     'accept' => [
     *          'label' => 'Yes, save it',
     *          'method' => 'save',
     *          'params' => ['Saved'],
     *     ],
     *     'reject' => [
     *          'label' => 'No, cancel',
     *          'method' => 'cancel',
     *     ],
     * ]);
     */
    public function confirm(array $config): void
    {
        $accept = $config['accept'] ?? [];
        $reject = $config['reject'] ?? [];

        $this->dispatchDialog([
            'type'        => 'confirm',
            'title'       => $config['title'] ?? 'Are you sure?',
            'description' => $config['description'] ?? null,
            'icon'        => $config['icon'] ?? 'question-mark-circle',
            'size'        => $options['size'] ?? null,
            'accept' => [
                'label'  => $accept['label'] ?? 'Confirm',
                'method' => $accept['method'] ?? null,
                'params' => $accept['params'] ?? [],
            ],
            'reject' => [
                'label'  => $reject['label'] ?? 'Cancel',
                'method' => $reject['method'] ?? null,
                'params' => $reject['params'] ?? [],
            ],

            // por defecto confirm NO se cierra por escape / fuera
            'allowOutsideClick' => $config['allowOutsideClick'] ?? false,
            'allowEscape'       => $config['allowEscape'] ?? false,
        ]);
    }

    public function delete(
        string $title,
        ?string $description = null,
        array $options = []
    ): void {
        $method = $options['method'] ?? null;
        $params = $options['params'] ?? [];

        $this->dispatchDialog([
            'type'        => 'danger',
            'title'       => $title,
            'description' => $description,
            'icon'        => 'x-circle',
            'size'        => $options['size'] ?? null,
            'accept' => [
                'label'  => $options['accept_label'] ?? 'Eliminar',
                'method' => $method,
                'params' => $params,
            ],
            'reject' => [
                'label'  => $options['reject_label'] ?? 'Cancelar',
                'method' => $options['reject_method'] ?? null,
                'params' => $options['reject_params'] ?? [],
            ],

            // Para deletes, nunca permitir cerrar afuera
            'allowOutsideClick' => false,
            'allowEscape'       => false,
        ]);
    }

}
