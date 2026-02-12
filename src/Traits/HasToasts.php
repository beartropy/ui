<?php

namespace Beartropy\Ui\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasToasts.
 *
 * Provides a fluent interface for dispatching toast notifications from Livewire components.
 */
trait HasToasts
{
    /** @var object|null Proxy object for fluent interface */
    protected $_beartropy_toast_proxy;

    /**
     * Get the toast proxy instance.
     *
     * Allows usage like `$this->toast()->success('Message')`.
     *
     * @return object Proxy instance with success(), error(), info(), warning() methods.
     */
    public function toast(): object
    {
        if (!isset($this->_beartropy_toast_proxy)) {
            $this->_beartropy_toast_proxy = new class($this) {
                public function __construct(protected object $component) {}

                public function success(string $title, string $message = '', int $duration = 4000, ?string $position = null, ?string $action = null, ?string $actionUrl = null): void
                {
                    $this->send('success', $title, $message, $duration, $position, $action, $actionUrl);
                }

                public function error(string $title, string $message = '', int $duration = 4000, ?string $position = null, ?string $action = null, ?string $actionUrl = null): void
                {
                    $this->send('error', $title, $message, $duration, $position, $action, $actionUrl);
                }

                public function info(string $title, string $message = '', int $duration = 4000, ?string $position = null, ?string $action = null, ?string $actionUrl = null): void
                {
                    $this->send('info', $title, $message, $duration, $position, $action, $actionUrl);
                }

                public function warning(string $title, string $message = '', int $duration = 4000, ?string $position = null, ?string $action = null, ?string $actionUrl = null): void
                {
                    $this->send('warning', $title, $message, $duration, $position, $action, $actionUrl);
                }

                protected function send(string $type, string $title, string $message, int $duration, ?string $position, ?string $action, ?string $actionUrl): void
                {
                    $payload = [
                        'id' => (string) Str::uuid(),
                        'type' => $type,
                        'title' => $title,
                        'message' => $message,
                        'single' => $message === '',
                        'duration' => $duration,
                        'position' => $position,
                        'action' => $action,
                        'actionUrl' => $actionUrl,
                    ];
                    $this->component->dispatch('beartropy-add-toast', $payload);
                }
            };
        }

        return $this->_beartropy_toast_proxy;
    }
}
