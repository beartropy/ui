<?php

namespace Beartropy\Ui\Traits;

/**
 * Trait HasDialogs.
 *
 * Provides methods to dispatch dialog events (success, info, warning, error, confirm)
 * from Livewire components to the frontend.
 */
trait HasDialogs
{
    /**
     * Fluent accessor for dialog methods.
     *
     * Allows usage like `$this->dialog()->success(...)`.
     *
     * @return static
     */
    public function dialog(): static
    {
        return $this;
    }

    /**
     * Dispatch the dialog event to the frontend.
     *
     * @param array $payload Dialog configuration payload.
     * @return void
     */
    protected function dispatchDialog(array $payload): void
    {
        $payload['componentId'] = $payload['componentId']
            ?? (method_exists($this, 'getId') ? $this->getId() : null);

        $this->dispatch('bt-dialog', $payload);
    }

    /**
     * Show a success dialog.
     *
     * @param string      $title       Dialog title.
     * @param string|null $description Dialog body text.
     * @param array       $options     Additional options (size, buttons, callbacks).
     * @return void
     */
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

    /**
     * Show an info dialog.
     *
     * @param string      $title       Dialog title.
     * @param string|null $description Dialog body text.
     * @param array       $options     Additional options.
     * @return void
     */
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

    /**
     * Show a warning dialog.
     *
     * @param string      $title       Dialog title.
     * @param string|null $description Dialog body text.
     * @param array       $options     Additional options.
     * @return void
     */
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

    /**
     * Show an error dialog.
     *
     * @param string      $title       Dialog title.
     * @param string|null $description Dialog body text.
     * @param array       $options     Additional options.
     * @return void
     */
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
     * Show a confirmation dialog.
     *
     * Usage Example:
     * ```php
     * $this->dialog()->confirm([
     *     'title'       => 'Are you sure?',
     *     'description' => 'This action cannot be undone.',
     *     'accept'      => ['label' => 'Yes, do it', 'method' => 'deleteParams', 'params' => [1]],
     *     'reject'      => ['label' => 'No, cancel'],
     * ]);
     * ```
     *
     * @param array $config Configuration array for approval/rejection actions.
     * @return void
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

    /**
     * Show a delete confirmation dialog (Danger/Destructive style).
     *
     * @param string      $title       Dialog title.
     * @param string|null $description Dialog body text.
     * @param array       $options     Options including 'method' and 'params' for the action.
     * @return void
     */
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
