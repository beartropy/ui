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
     */
    public function dialog(): static
    {
        return $this;
    }

    /**
     * Dispatch the dialog event to the frontend.
     *
     * @param array<string, mixed> $payload Dialog configuration payload.
     */
    protected function dispatchDialog(array $payload): void
    {
        $payload['componentId'] = $payload['componentId']
            ?? (method_exists($this, 'getId') ? $this->getId() : null);

        $this->dispatch('bt-dialog', $payload);
    }

    /**
     * Build a standard dialog payload.
     *
     * @param string               $type        Dialog type (success, info, warning, error).
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param string               $icon        Icon name.
     * @param array<string, mixed> $options     Additional options (size, buttons, callbacks).
     *
     * @return array<string, mixed>
     */
    protected function buildDialogPayload(string $type, string $title, ?string $description, string $icon, array $options = []): array
    {
        return array_merge([
            'type'              => $type,
            'title'             => $title,
            'description'       => $description,
            'icon'              => $icon,
            'size'              => $options['size'] ?? null,
            'accept'            => [
                'label'  => $options['accept_label'] ?? __('beartropy-ui::ui.ok'),
                'method' => $options['accept_method'] ?? null,
                'params' => $options['accept_params'] ?? [],
            ],
            'reject'            => null,
            'allowOutsideClick' => $options['allowOutsideClick'] ?? false,
            'allowEscape'       => $options['allowEscape'] ?? false,
        ], $options);
    }

    /**
     * Show a success dialog.
     *
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param array<string, mixed> $options     Additional options (size, buttons, callbacks).
     */
    public function success(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog($this->buildDialogPayload('success', $title, $description, 'check-circle', $options));
    }

    /**
     * Show an info dialog.
     *
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param array<string, mixed> $options     Additional options.
     */
    public function info(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog($this->buildDialogPayload('info', $title, $description, 'information-circle', $options));
    }

    /**
     * Show a warning dialog.
     *
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param array<string, mixed> $options     Additional options.
     */
    public function warning(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog($this->buildDialogPayload('warning', $title, $description, 'exclamation-triangle', $options));
    }

    /**
     * Show an error dialog.
     *
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param array<string, mixed> $options     Additional options.
     */
    public function error(string $title, ?string $description = null, array $options = []): void
    {
        $this->dispatchDialog($this->buildDialogPayload('error', $title, $description, 'x-circle', $options));
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
     * @param array<string, mixed> $config Configuration array for approval/rejection actions.
     */
    public function confirm(array $config): void
    {
        $accept = $config['accept'] ?? [];
        $reject = $config['reject'] ?? [];

        $this->dispatchDialog([
            'type'              => 'confirm',
            'title'             => $config['title'] ?? __('beartropy-ui::ui.are_you_sure'),
            'description'       => $config['description'] ?? null,
            'icon'              => $config['icon'] ?? 'question-mark-circle',
            'size'              => $config['size'] ?? null,
            'accept'            => [
                'label'  => $accept['label'] ?? __('beartropy-ui::ui.confirm'),
                'method' => $accept['method'] ?? null,
                'params' => $accept['params'] ?? [],
            ],
            'reject'            => [
                'label'  => $reject['label'] ?? __('beartropy-ui::ui.cancel'),
                'method' => $reject['method'] ?? null,
                'params' => $reject['params'] ?? [],
            ],
            'allowOutsideClick' => $config['allowOutsideClick'] ?? false,
            'allowEscape'       => $config['allowEscape'] ?? false,
        ]);
    }

    /**
     * Show a delete confirmation dialog (Danger/Destructive style).
     *
     * @param string               $title       Dialog title.
     * @param string|null          $description Dialog body text.
     * @param array<string, mixed> $options     Options including 'method' and 'params' for the action.
     */
    public function delete(
        string $title,
        ?string $description = null,
        array $options = [],
    ): void {
        $method = $options['method'] ?? null;
        $params = $options['params'] ?? [];

        $this->dispatchDialog([
            'type'              => 'danger',
            'title'             => $title,
            'description'       => $description,
            'icon'              => 'x-circle',
            'size'              => $options['size'] ?? null,
            'accept'            => [
                'label'  => $options['accept_label'] ?? __('beartropy-ui::ui.delete'),
                'method' => $method,
                'params' => $params,
            ],
            'reject'            => [
                'label'  => $options['reject_label'] ?? __('beartropy-ui::ui.cancel'),
                'method' => $options['reject_method'] ?? null,
                'params' => $options['reject_params'] ?? [],
            ],
            'allowOutsideClick' => false,
            'allowEscape'       => false,
        ]);
    }
}
